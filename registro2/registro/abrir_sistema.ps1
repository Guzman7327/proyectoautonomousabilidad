# Script para abrir el Sistema Turístico Ecuador
# Ejecutar: .\abrir_sistema.ps1

Write-Host "🚀 Abriendo Sistema Turístico Ecuador..." -ForegroundColor Green

# URLs base
$baseUrl = "http://localhost/registro/registro"

# Páginas principales
$paginas = @(
    "index.php",
    "dashboard.php", 
    "sistema_integrado.php",
    "login.php",
    "admin.php"
)

# Formularios RF
$formularios = @(
    "formulario_rf7.php",
    "formulario_rf8.php", 
    "formulario_rf9.php",
    "formulario_rf10.php"
)

# Evaluaciones
$evaluaciones = @(
    "evaluacion_rf7.html",
    "evaluacion_rf8.html",
    "evaluacion_rf9.html", 
    "evaluacion_rf10.html"
)

Write-Host "📋 Abriendo páginas principales..." -ForegroundColor Yellow
foreach ($pagina in $paginas) {
    $url = "$baseUrl/$pagina"
    Write-Host "  Abriendo: $url" -ForegroundColor Cyan
    Start-Process $url
    Start-Sleep -Milliseconds 500
}

Write-Host "📋 Abriendo formularios RF..." -ForegroundColor Yellow
foreach ($formulario in $formularios) {
    $url = "$baseUrl/$formulario"
    Write-Host "  Abriendo: $url" -ForegroundColor Cyan
    Start-Process $url
    Start-Sleep -Milliseconds 500
}

Write-Host "📊 Abriendo evaluaciones..." -ForegroundColor Yellow
foreach ($evaluacion in $evaluaciones) {
    $url = "$baseUrl/$evaluacion"
    Write-Host "  Abriendo: $url" -ForegroundColor Cyan
    Start-Process $url
    Start-Sleep -Milliseconds 500
}

Write-Host "✅ ¡Sistema abierto completamente!" -ForegroundColor Green
Write-Host "📝 Total de páginas abiertas: $($paginas.Count + $formularios.Count + $evaluaciones.Count)" -ForegroundColor White

# Mostrar información del sistema
Write-Host "`n📊 INFORMACIÓN DEL SISTEMA:" -ForegroundColor Magenta
Write-Host "  🗂️  Directorio: C:\xampp\htdocs\registro\registro" -ForegroundColor White
Write-Host "  🗄️  Base de datos: registro" -ForegroundColor White
Write-Host "  📋 Tablas: 28 tablas creadas" -ForegroundColor White
Write-Host "  📊 Datos: 43 registros de prueba" -ForegroundColor White

Write-Host "`n🎯 URLs PRINCIPALES:" -ForegroundColor Magenta
Write-Host "  🏠 Página Principal: $baseUrl/index.php" -ForegroundColor White
Write-Host "  📊 Dashboard: $baseUrl/dashboard.php" -ForegroundColor White
Write-Host "  🔧 Sistema Integrado: $baseUrl/sistema_integrado.php" -ForegroundColor White

Write-Host "`n💡 CONSEJOS:" -ForegroundColor Yellow
Write-Host "  • Usa el Sistema Integrado para navegar por todos los módulos" -ForegroundColor White
Write-Host "  • El Dashboard muestra estadísticas y acceso rápido" -ForegroundColor White
Write-Host "  • Los formularios RF están completamente funcionales" -ForegroundColor White
Write-Host "  • Las evaluaciones muestran análisis detallados" -ForegroundColor White

Write-Host "`n🚀 ¡Disfruta explorando el Sistema Turístico Ecuador!" -ForegroundColor Green

