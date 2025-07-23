# Importaciones estándar de Python
import datetime
import json
import traceback
from functools import wraps

# Importaciones de terceros
import bcrypt
import jwt
import psycopg2
from flask import Flask, jsonify, session, url_for, request, render_template, redirect, g, send_from_directory
from flask_cors import CORS

app = Flask(__name__)
app.secret_key = "clave_secreta"
CORS(app)

SECRET_KEY = 'supersecreto'

# --- BASE DE DATOS ---
def get_conn():
       try:
           return psycopg2.connect(
               host="localhost",
               database="turismo",
               user="postgres",         # <--- Cambia esto por tu usuario real
               password="123456789"     # <--- Cambia esto por tu contraseña real
           )
       except Exception as e:
           print("🚫 Error de conexión:", e)
           raise

@app.route('/rutas')
def rutas():
    return render_template('rutas.html')

@app.route("/rutas/nueva", methods=["GET", "POST"])
def nueva_ruta():
    mensaje = None
    if request.method == "POST":
        nombre = request.form.get("nombre")
        descripcion = request.form.get("descripcion")
        provincia = request.form.get("provincia")
        ciudad = request.form.get("ciudad")
        tipo = request.form.get("tipo")
        dificultad = request.form.get("dificultad")
        distancia_km = request.form.get("distancia_km")
        duracion_horas = request.form.get("duracion_horas")
        accesible = bool(request.form.get("accesible"))
        imagen_url = request.form.get("imagen_url")

        if not nombre:
            mensaje = "El nombre de la ruta es obligatorio."
        else:
            try:
                conn = get_conn()
                cur = conn.cursor()
                cur.execute("""
                    INSERT INTO rutas (nombre, descripcion, provincia, ciudad, tipo, dificultad, distancia_km, duracion_horas, accesible, imagen_url)
                    VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
                """, (nombre, descripcion, provincia, ciudad, tipo, dificultad, distancia_km, duracion_horas, accesible, imagen_url))
                conn.commit()
                cur.close()
                conn.close()
                mensaje = "Ruta guardada exitosamente."
            except Exception as e:
                mensaje = f"Error al guardar la ruta: {e}"

    return render_template("nueva_ruta.html", mensaje=mensaje)

# --- AUTENTICACIÓN CON JWT (API) ---
def token_requerido(f):
    @wraps(f)
    def decorador(*args, **kwargs):
        token = None
        if 'Authorization' in request.headers:
            token = request.headers['Authorization'].split(' ')[1]
        if not token:
            return jsonify({'mensaje': 'Token requerido'}), 401
        try:
            data = jwt.decode(token, SECRET_KEY, algorithms=["HS256"])
            g.usuario = data['usuario']
            g.rol = data['rol']
        except Exception:
            return jsonify({'mensaje': 'Token inválido'}), 401
        return f(*args, **kwargs)
    return decorador

def solo_admin(f):
    @wraps(f)
    def decorador(*args, **kwargs):
        if getattr(request, 'rol', None) != 'admin':
            return jsonify({'mensaje': 'Solo administradores'}), 403
        return f(*args, **kwargs)
    return decorador

# --- FORMULARIO REGISTRO (CLÁSICO Y JSON) ---

@app.route("/registro", methods=["GET", "POST"])
def registro():
    if request.method == "POST":
        try:
            # Obtener datos del formulario extendido
            nombre = request.form.get("nombre")
            cedula = request.form.get("cedula")
            email = request.form.get("email")
            telefono = request.form.get("telefono")
            usuario = request.form.get("usuario")
            clave = request.form.get("clave")
            confirmar_clave = request.form.get("confirmar_clave")
            aceptar_terminos = request.form.get("aceptar_terminos")
            recibir_notificaciones = request.form.get("recibir_notificaciones")

            # Validaciones básicas
            if not (nombre and cedula and email and usuario and clave and confirmar_clave and aceptar_terminos):
                return "Faltan datos obligatorios", 400
            if clave != confirmar_clave:
                return "Las contraseñas no coinciden", 400
            if len(clave) < 8:
                return "La contraseña debe tener al menos 8 caracteres", 400
            # Puedes agregar más validaciones según tus necesidades

            rol = "usuario"
            hashed = bcrypt.hashpw(clave.encode(), bcrypt.gensalt())

            conn = get_conn()
            cur = conn.cursor()
            cur.execute("""
                INSERT INTO usuarios (usuario, clave, rol, nombre, cedula, email, telefono, notificaciones)
                VALUES (%s, %s, %s, %s, %s, %s, %s, %s)
            """, (
                usuario, hashed, rol, nombre, cedula, email, telefono, bool(recibir_notificaciones)
            ))
            conn.commit()
            cur.close()
            conn.close()

            return redirect("/login")
        except Exception as e:
            traceback.print_exc()
            return "Error interno", 500

    return render_template("registro.html")


# --- FORMULARIO LOGIN HTML ---
@app.route("/login", methods=["GET", "POST"])
def login():
    mensaje = None
    if request.method == "POST":
        usuario = request.form.get("usuario")
        clave = request.form.get("clave")

        if not usuario or not clave:
            mensaje = "Por favor, ingresa usuario y contraseña."
            return render_template("login.html", mensaje=mensaje)

        conn = get_conn()
        cur = conn.cursor()
        cur.execute("SELECT id, clave, rol FROM usuarios WHERE usuario = %s", (usuario,))
        row = cur.fetchone()
        cur.close()
        conn.close()

        if row and clave and bcrypt.checkpw(clave.encode(), row[1].tobytes()):
            session["usuario_id"] = row[0]
            session["rol"] = row[2]
            return redirect("/")
        else:
            mensaje = "Credenciales inválidas"
            return render_template("login.html", mensaje=mensaje)

    return render_template("login.html", mensaje=mensaje)

# --- CERRAR SESIÓN ---
@app.route("/logout")
def logout():
    session.clear()
    return redirect("/login")

# --- ADMIN (HTML) ---
@app.route("/admin")
def vista_admin():
    if session.get("rol") != "admin":
        return redirect("/login")
    conn = get_conn()
    cur = conn.cursor()
    cur.execute("SELECT id, usuario FROM usuarios")
    usuarios = cur.fetchall()
    cur.close()
    conn.close()
    return render_template("admin.html", usuarios=usuarios)

# --- API LOGIN CON JWT ---
@app.route("/api/login", methods=["POST"])
def api_login():
    data = request.get_json()
    usuario = data["usuario"]
    clave = data["clave"]

    conn = get_conn()
    cur = conn.cursor()
    cur.execute("SELECT clave, rol FROM usuarios WHERE usuario = %s", (usuario,))
    row = cur.fetchone()
    cur.close()
    conn.close()

    if row and bcrypt.checkpw(clave.encode(), row[0].tobytes()):
        token = jwt.encode({
            'usuario': usuario,
            'rol': row[1],
            'exp': datetime.datetime.utcnow() + datetime.timedelta(hours=8)
        }, SECRET_KEY, algorithm="HS256")
        return jsonify({"mensaje": f"Bienvenido, {usuario}", "rol": row[1], "token": token}), 200
    else:
        return jsonify({"mensaje": "Credenciales incorrectas"}), 401

# --- API: LISTADO DE USUARIOS ---
@app.route("/api/admin/usuarios", methods=["GET"])
@token_requerido
@solo_admin
def listar_usuarios():
    conn = get_conn()
    cur = conn.cursor()
    cur.execute("SELECT id, usuario FROM usuarios")
    usuarios = cur.fetchall()
    cur.close()
    conn.close()
    return jsonify([{"id": u[0], "usuario": u[1]} for u in usuarios])

# Servir video
@app.route('/videos/<filename>')
def serve_video(filename):
    return send_from_directory('static/videos', filename)

# Servir subtítulos
@app.route('/subtitulos/<filename>')
def serve_subtitulos(filename):
    return send_from_directory('static/videos', filename)

# Servir transcripción
@app.route('/transcripciones/<filename>')
def serve_transcripcion(filename):
    return send_from_directory('static/transcripciones', filename)

# Endpoint opcional para alerta visual
@app.route('/api/alerta_visual')
def alerta_visual():
    mensaje = request.args.get('mensaje', '¡Alerta visual!')
    return jsonify({'mensaje': mensaje})

@app.route('/cambiar_idioma')
def cambiar_idioma():
    lang = request.args.get('lang', 'es')
    session['lang'] = lang
    return redirect(request.referrer or url_for('inicio'))

@app.route("/")
def inicio():
    try:
        lang = session.get('lang', 'es')
        return render_template("index.html",
            video_url='#',  # Temporal sin video
            subtitulos_url='#',  # Temporal sin subtítulos
            transcripcion_url='#',  # Temporal sin transcripción
            lang=lang
        )
    except Exception as e:
        print(f"Error en índice: {e}")
        return render_template("test.html")  # Fallback a página de prueba

@app.route("/contacto", methods=["GET", "POST"])
def contacto():
    mensaje = None
    if request.method == "POST":
        nombre = request.form.get("nombre")
        email = request.form.get("email")
        asunto = request.form.get("asunto")
        mensaje_form = request.form.get("mensaje")
        captcha = request.form.get("captcha")
        # Validaciones básicas
        if not (nombre and email and asunto and mensaje_form and captcha):
            mensaje = "Por favor, completa todos los campos obligatorios."
            return render_template("contacto.html", mensaje=mensaje)
        if captcha.strip() == "" or not captcha.isdigit():
            mensaje = "Respuesta de verificación incorrecta."
            return render_template("contacto.html", mensaje=mensaje)
        if len(mensaje_form) < 10:
            mensaje = "El mensaje debe tener al menos 10 caracteres."
            return render_template("contacto.html", mensaje=mensaje)
        try:
            conn = get_conn()
            cur = conn.cursor()
            cur.execute("""
                INSERT INTO mensajes_contacto (nombre, email, mensaje)
                VALUES (%s, %s, %s)
            """, (nombre, email, mensaje_form))
            conn.commit()
            cur.close()
            conn.close()
            mensaje = "¡Mensaje enviado exitosamente! Te responderemos pronto."
        except Exception as e:
            mensaje = f"Error al guardar el mensaje: {e}"
        return render_template("contacto.html", mensaje=mensaje)
    return render_template("contacto.html", mensaje=mensaje)

@app.route("/registro_admin", methods=["GET", "POST"])
def registro_admin():
    mensaje = None
    if request.method == "POST":
        usuario = request.form.get("usuario")
        clave = request.form.get("clave")
        email = request.form.get("email")

        if not (usuario and clave and email):
            mensaje = "Por favor, completa todos los campos."
            return render_template("registro_admin.html", mensaje=mensaje)
        if len(clave) < 8:
            mensaje = "La contraseña debe tener al menos 8 caracteres."
            return render_template("registro_admin.html", mensaje=mensaje)

        rol = "admin"
        hashed = bcrypt.hashpw(clave.encode(), bcrypt.gensalt())

        try:
            conn = get_conn()
            cur = conn.cursor()
            cur.execute("""
                INSERT INTO usuarios (usuario, clave, rol, email)
                VALUES (%s, %s, %s, %s)
            """, (usuario, hashed, rol, email))
            conn.commit()
            cur.close()
            conn.close()
            mensaje = "Administrador registrado exitosamente."
            return render_template("registro_admin.html", mensaje=mensaje)
        except Exception as e:
            traceback.print_exc()
            mensaje = "Error interno al registrar administrador."
            return render_template("registro_admin.html", mensaje=mensaje)

    return render_template("registro_admin.html", mensaje=mensaje)

@app.route("/recuperar", methods=["GET", "POST"])
def recuperar():
    mensaje = None
    if request.method == "POST":
        email = request.form.get("email")
        if not email:
            mensaje = "Por favor, ingresa tu correo electrónico."
            return render_template("recuperar.html", mensaje=mensaje)
        # Aquí normalmente enviarías un correo real
        mensaje = "Instrucciones enviadas a tu correo electrónico."
        return render_template("recuperar.html", mensaje=mensaje)
    return render_template("recuperar.html", mensaje=mensaje)

@app.route("/guardar_registro", methods=["GET", "POST"])
def guardar_registro():
    mensaje = None
    if request.method == "POST":
        nombre = request.form.get("nombre")
        email = request.form.get("email")
        fecha = request.form.get("fecha")
        ciudad = request.form.get("ciudad")
        pais = request.form.get("pais")
        suscripcion = request.form.get("suscripcion")
        # Validación básica
        if not (nombre and email and fecha and ciudad and pais):
            mensaje = "Por favor, completa todos los campos obligatorios."
            return render_template("guardar_registro.html", mensaje=mensaje)
        try:
            conn = get_conn()
            cur = conn.cursor()
            cur.execute("""
                INSERT INTO destinos (nombre, email, fecha, ciudad, pais, suscripcion)
                VALUES (%s, %s, %s, %s, %s, %s)
            """, (nombre, email, fecha, ciudad, pais, bool(suscripcion)))
            conn.commit()
            cur.close()
            conn.close()
            mensaje = "Registro guardado exitosamente."
        except Exception as e:
            mensaje = f"Error al guardar el registro: {e}"
        return render_template("guardar_registro.html", mensaje=mensaje)
    return render_template("guardar_registro.html", mensaje=mensaje)

@app.route("/editar_registro", methods=["GET", "POST"])
def editar_registro():
    mensaje = None
    if request.method == "POST":
        destino_id = request.form.get("id")
        nombre = request.form.get("nombre")
        email = request.form.get("email")
        fecha = request.form.get("fecha")
        ciudad = request.form.get("ciudad")
        pais = request.form.get("pais")
        suscripcion = request.form.get("suscripcion")
        if not (destino_id and nombre and email and fecha and ciudad and pais):
            mensaje = "Por favor, completa todos los campos obligatorios."
            return render_template("editar_registro.html", mensaje=mensaje)
        try:
            conn = get_conn()
            cur = conn.cursor()
            cur.execute("""
                UPDATE destinos
                SET nombre=%s, email=%s, fecha=%s, ciudad=%s, pais=%s, suscripcion=%s
                WHERE id=%s
            """, (nombre, email, fecha, ciudad, pais, bool(suscripcion), destino_id))
            conn.commit()
            cur.close()
            conn.close()
            mensaje = "Registro actualizado exitosamente."
        except Exception as e:
            mensaje = f"Error al actualizar el registro: {e}"
        return render_template("editar_registro.html", mensaje=mensaje)
    return render_template("editar_registro.html", mensaje=mensaje)

@app.route("/busqueda_avanzada", methods=["GET", "POST"])
def busqueda_avanzada():
    mensaje = None
    resultados = []
    if request.method == "POST":
        nombre = request.form.get("nombre")
        tipo = request.form.get("tipo")
        provincia = request.form.get("provincia")
        ciudad = request.form.get("ciudad")
        caracteristicas = request.form.getlist("caracteristicas")
        precio_min = request.form.get("precio_min")
        precio_max = request.form.get("precio_max")
        incluir_gratis = request.form.get("incluir_gratis")
        orden = request.form.get("orden")

        query = "SELECT * FROM destinos WHERE 1=1"
        params = []
        if nombre:
            query += " AND nombre ILIKE %s"
            params.append(f"%{nombre}%")
        if tipo:
            query += " AND tipo = %s"
            params.append(tipo)
        if provincia:
            query += " AND provincia = %s"
            params.append(provincia)
        if ciudad:
            query += " AND ciudad ILIKE %s"
            params.append(f"%{ciudad}%")
        # Puedes agregar más filtros según tus necesidades
        try:
            conn = get_conn()
            cur = conn.cursor()
            cur.execute(query, params)
            resultados = cur.fetchall()
            cur.close()
            conn.close()
            mensaje = f"Se encontraron {len(resultados)} resultados."
        except Exception as e:
            mensaje = "Error al realizar la búsqueda."
            print(e)
        return render_template("busqueda_avanzada.html", mensaje=mensaje, resultados=resultados)

    return render_template("busqueda_avanzada.html", mensaje=mensaje, resultados=None)

@app.route("/test_db")
def test_db():
    try:
        conn = get_conn()
        cur = conn.cursor()
        cur.execute("SELECT * FROM destinos LIMIT 5;")
        rows = cur.fetchall()
        cur.close()
        conn.close()
        return f"Conexión exitosa. Primeros registros: {rows}"
    except Exception as e:
        return f"Error de conexión o tabla: {e}"

@app.route("/formularios", methods=["GET", "POST"])
def formularios():
    mensaje = None
    tipo = request.form.get("tipo_formulario") if request.method == "POST" else None
    resultado_busqueda = None

    if request.method == "POST":
        # REGISTRO USUARIO
        if tipo == "registro":
            nombre = request.form.get("nombre")
            cedula = request.form.get("cedula")
            email = request.form.get("email")
            telefono = request.form.get("telefono")
            usuario = request.form.get("usuario")
            clave = request.form.get("clave")
            confirmar_clave = request.form.get("confirmar_clave")
            aceptar_terminos = request.form.get("aceptar_terminos")
            recibir_notificaciones = request.form.get("recibir_notificaciones")
            if not (nombre and cedula and email and usuario and clave and confirmar_clave and aceptar_terminos):
                mensaje = "Faltan datos obligatorios en registro."
            elif clave != confirmar_clave:
                mensaje = "Las contraseñas no coinciden."
            else:
                try:
                    rol = "usuario"
                    hashed = bcrypt.hashpw(clave.encode(), bcrypt.gensalt())
                    conn = get_conn()
                    cur = conn.cursor()
                    cur.execute("""
                        INSERT INTO usuarios (usuario, clave, rol, nombre, cedula, email, telefono, notificaciones)
                        VALUES (%s, %s, %s, %s, %s, %s, %s, %s)
                    """, (usuario, hashed, rol, nombre, cedula, email, telefono, bool(recibir_notificaciones)))
                    conn.commit()
                    cur.close()
                    conn.close()
                    mensaje = "Usuario registrado exitosamente."
                except Exception as e:
                    mensaje = f"Error al registrar usuario: {e}"
        # LOGIN
        elif tipo == "login":
            usuario = request.form.get("usuario")
            clave = request.form.get("clave")
            if not usuario or not clave:
                mensaje = "Por favor, ingresa usuario y contraseña."
            else:
                conn = get_conn()
                cur = conn.cursor()
                cur.execute("SELECT id, clave, rol FROM usuarios WHERE usuario = %s", (usuario,))
                row = cur.fetchone()
                cur.close()
                conn.close()
                if row and clave and bcrypt.checkpw(clave.encode(), row[1].tobytes()):
                    mensaje = f"Bienvenido, {usuario}. Rol: {row[2]}"
                else:
                    mensaje = "Credenciales inválidas."
        # CONTACTO
        elif tipo == "contacto":
            nombre = request.form.get("nombre")
            email = request.form.get("email")
            asunto = request.form.get("asunto")
            mensaje_form = request.form.get("mensaje")
            captcha = request.form.get("captcha")
            if not (nombre and email and asunto and mensaje_form and captcha):
                mensaje = "Por favor, completa todos los campos obligatorios en contacto."
            elif captcha.strip().lower() != "quito":
                mensaje = "Respuesta de verificación incorrecta."
            elif len(mensaje_form) < 10:
                mensaje = "El mensaje debe tener al menos 10 caracteres."
            else:
                mensaje = "¡Mensaje enviado exitosamente! Te responderemos pronto."
        # REGISTRO ADMIN
        elif tipo == "registro_admin":
            usuario = request.form.get("usuario")
            clave = request.form.get("clave")
            email = request.form.get("email")
            if not (usuario and clave and email):
                mensaje = "Por favor, completa todos los campos en registro admin."
            elif len(clave) < 8:
                mensaje = "La contraseña debe tener al menos 8 caracteres."
            else:
                try:
                    rol = "admin"
                    hashed = bcrypt.hashpw(clave.encode(), bcrypt.gensalt())
                    conn = get_conn()
                    cur = conn.cursor()
                    cur.execute("""
                        INSERT INTO usuarios (usuario, clave, rol, email)
                        VALUES (%s, %s, %s, %s)
                    """, (usuario, hashed, rol, email))
                    conn.commit()
                    cur.close()
                    conn.close()
                    mensaje = "Administrador registrado exitosamente."
                except Exception as e:
                    mensaje = f"Error al registrar admin: {e}"
        # RECUPERAR
        elif tipo == "recuperar":
            email = request.form.get("email")
            if not email:
                mensaje = "Por favor, ingresa tu correo electrónico."
            else:
                mensaje = "Instrucciones enviadas a tu correo electrónico."
        # GUARDAR DESTINO
        elif tipo == "guardar_registro":
            nombre = request.form.get("nombre")
            tipo_dest = request.form.get("tipo")
            provincia = request.form.get("provincia")
            ciudad = request.form.get("ciudad")
            latitud = request.form.get("latitud")
            longitud = request.form.get("longitud")
            direccion = request.form.get("direccion")
            if not (nombre and tipo_dest and provincia):
                mensaje = "Por favor, completa los campos obligatorios en guardar destino."
            else:
                try:
                    conn = get_conn()
                    cur = conn.cursor()
                    cur.execute("""
                        INSERT INTO destinos (nombre, tipo, provincia, ciudad, latitud, longitud, direccion)
                        VALUES (%s, %s, %s, %s, %s, %s, %s)
                    """, (nombre, tipo_dest, provincia, ciudad, latitud, longitud, direccion))
                    conn.commit()
                    cur.close()
                    conn.close()
                    mensaje = "Destino guardado exitosamente."
                except Exception as e:
                    mensaje = f"Error al guardar destino: {e}"
        # EDITAR DESTINO
        elif tipo == "editar_registro":
            destino_id = request.form.get("id")
            nombre = request.form.get("nombre")
            tipo_dest = request.form.get("tipo")
            provincia = request.form.get("provincia")
            ciudad = request.form.get("ciudad")
            latitud = request.form.get("latitud")
            longitud = request.form.get("longitud")
            direccion = request.form.get("direccion")
            if not (destino_id and nombre and tipo_dest and provincia):
                mensaje = "Por favor, completa los campos obligatorios en editar destino."
            else:
                try:
                    conn = get_conn()
                    cur = conn.cursor()
                    cur.execute("""
                        UPDATE destinos SET nombre=%s, tipo=%s, provincia=%s, ciudad=%s, latitud=%s, longitud=%s, direccion=%s WHERE id=%s
                    """, (nombre, tipo_dest, provincia, ciudad, latitud, longitud, direccion, destino_id))
                    conn.commit()
                    cur.close()
                    conn.close()
                    mensaje = "Destino actualizado exitosamente."
                except Exception as e:
                    mensaje = f"Error al actualizar destino: {e}"
        # BUSQUEDA AVANZADA
        elif tipo == "busqueda_avanzada":
            nombre = request.form.get("nombre")
            tipo_dest = request.form.get("tipo")
            provincia = request.form.get("provincia")
            ciudad = request.form.get("ciudad")
            query = "SELECT * FROM destinos WHERE 1=1"
            params = []
            if nombre:
                query += " AND nombre ILIKE %s"
                params.append(f"%{nombre}%")
            if tipo_dest:
                query += " AND tipo = %s"
                params.append(tipo_dest)
            if provincia:
                query += " AND provincia = %s"
                params.append(provincia)
            if ciudad:
                query += " AND ciudad ILIKE %s"
                params.append(f"%{ciudad}%")
            try:
                conn = get_conn()
                cur = conn.cursor()
                cur.execute(query, params)
                resultado_busqueda = cur.fetchall()
                cur.close()
                conn.close()
                mensaje = f"Se encontraron {len(resultado_busqueda)} resultados."
            except Exception as e:
                mensaje = f"Error en búsqueda: {e}"
    return render_template("formularios.html", mensaje=mensaje, tipo=tipo, resultados=resultado_busqueda)

# --- PREFERENCIAS DE ACCESIBILIDAD (GUARDAR Y CARGAR) ---
@app.route('/api/preferencias_accesibilidad', methods=['GET', 'POST'])
def preferencias_accesibilidad():
    if 'usuario_id' not in session:
        return jsonify({'mensaje': 'No autenticado'}), 401
    usuario_id = session['usuario_id']
    conn = get_conn()
    cur = conn.cursor()
    if request.method == 'POST':
        if not request.is_json or not request.json:
            return jsonify({'mensaje': 'JSON inválido'}), 400
        preferencias = request.json.get('preferencias')
        cur.execute("UPDATE usuarios SET preferencias_accesibilidad = %s WHERE id = %s", (json.dumps(preferencias), usuario_id))
        conn.commit()
        cur.close()
        conn.close()
        return jsonify({'mensaje': 'Preferencias guardadas'})
    else:
        cur.execute("SELECT preferencias_accesibilidad FROM usuarios WHERE id = %s", (usuario_id,))
        row = cur.fetchone()
        cur.close()
        conn.close()
        return jsonify({'preferencias': row[0] if row else None})

# --- LOG DE ACCESIBILIDAD ---
@app.route('/api/accesibilidad_log', methods=['POST'])
def registrar_log_accesibilidad():
    if 'usuario_id' not in session:
        return jsonify({'mensaje': 'No autenticado'}), 401
    usuario_id = session['usuario_id']
    if not request.is_json or not request.json:
        return jsonify({'mensaje': 'JSON inválido'}), 400
    data = request.json
    accion = data.get('accion')
    conn = get_conn()
    cur = conn.cursor()
    cur.execute("INSERT INTO accesibilidad_log (usuario_id, accion) VALUES (%s, %s)", (usuario_id, accion))
    conn.commit()
    cur.close()
    conn.close()
    return jsonify({'mensaje': 'Log registrado'})

# --- CRUD DESTINOS TURÍSTICOS ---

@app.route('/api/destinos', methods=['GET'])
def listar_destinos():
    conn = get_conn()
    cur = conn.cursor()
    cur.execute("SELECT id, nombre, tipo, provincia, ciudad, direccion, latitud, longitud, descripcion, caracteristicas, precio, es_gratis FROM destinos")
    destinos = cur.fetchall()
    cur.close()
    conn.close()
    return jsonify([
        {
            'id': d[0], 'nombre': d[1], 'tipo': d[2], 'provincia': d[3], 'ciudad': d[4],
            'direccion': d[5], 'latitud': float(d[6]) if d[6] else None, 'longitud': float(d[7]) if d[7] else None,
            'descripcion': d[8], 'caracteristicas': d[9], 'precio': float(d[10]) if d[10] else None, 'es_gratis': d[11]
        } for d in destinos
    ])

@app.route('/api/destinos', methods=['POST'])
def crear_destino():
    if not request.is_json or not request.json:
        return jsonify({'mensaje': 'JSON inválido'}), 400
    data = request.json
    conn = get_conn()
    cur = conn.cursor()
    cur.execute("""
        INSERT INTO destinos (nombre, tipo, provincia, ciudad, direccion, latitud, longitud, descripcion, caracteristicas, precio, es_gratis)
        VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
        RETURNING id
    """, (
        data.get('nombre'), data.get('tipo'), data.get('provincia'), data.get('ciudad'), data.get('direccion'),
        data.get('latitud'), data.get('longitud'), data.get('descripcion'), json.dumps(data.get('caracteristicas')), data.get('precio'), data.get('es_gratis')
    ))
    row = cur.fetchone()
    if not row:
        cur.close()
        conn.close()
        return jsonify({'mensaje': 'No se pudo crear el destino'}), 500
    destino_id = row[0]
    conn.commit()
    cur.close()
    conn.close()
    return jsonify({'id': destino_id, 'mensaje': 'Destino creado'}), 201

@app.route('/api/destinos/<int:destino_id>', methods=['PUT'])
def editar_destino(destino_id):
    if not request.is_json or not request.json:
        return jsonify({'mensaje': 'JSON inválido'}), 400
    data = request.json
    conn = get_conn()
    cur = conn.cursor()
    cur.execute("""
        UPDATE destinos SET nombre=%s, tipo=%s, provincia=%s, ciudad=%s, direccion=%s, latitud=%s, longitud=%s, descripcion=%s, caracteristicas=%s, precio=%s, es_gratis=%s
        WHERE id=%s
    """, (
        data.get('nombre'), data.get('tipo'), data.get('provincia'), data.get('ciudad'), data.get('direccion'),
        data.get('latitud'), data.get('longitud'), data.get('descripcion'), json.dumps(data.get('caracteristicas')), data.get('precio'), data.get('es_gratis'), destino_id
    ))
    conn.commit()
    cur.close()
    conn.close()
    return jsonify({'mensaje': 'Destino actualizado'})

@app.route('/api/destinos/<int:destino_id>', methods=['DELETE'])
def eliminar_destino(destino_id):
    conn = get_conn()
    cur = conn.cursor()
    cur.execute("DELETE FROM destinos WHERE id=%s", (destino_id,))
    conn.commit()
    cur.close()
    conn.close()
    return jsonify({'mensaje': 'Destino eliminado'})

# --- GUARDAR MENSAJES DE CONTACTO ---
@app.route('/api/contacto', methods=['POST'])
def guardar_mensaje_contacto():
    if not request.is_json or not request.json:
        return jsonify({'mensaje': 'JSON inválido'}), 400
    data = request.json
    nombre = data.get('nombre')
    email = data.get('email')
    mensaje = data.get('mensaje')
    conn = get_conn()
    cur = conn.cursor()
    cur.execute("INSERT INTO mensajes_contacto (nombre, email, mensaje) VALUES (%s, %s, %s)", (nombre, email, mensaje))
    conn.commit()
    cur.close()
    conn.close()
    return jsonify({'mensaje': 'Mensaje guardado correctamente'})

@app.route("/test")
def test():
    return render_template("test.html")

# --- MAIN ---
if __name__ == "__main__":
    app.run(debug=True, port=5000)
