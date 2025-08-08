# Script final para abrir el Sistema Turistico Ecuador
# Ejecutar: .\abrir_sistema_final.ps1

Write-Host "Abriendo Sistema Turistico Ecuador - Version Final..." -ForegroundColor Green

# URLs base
$baseUrl = "http://localhost/registro/registro"

# Paginas principales (incluyendo la nueva pagina principal)
$paginas = @(
    "pagina_principal.php",
    "index.php",
    "dashboard.php", 
    "sistema_integrado.php"
)

# Formularios RF mejorados
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

Write-Host "Abriendo pagina principal mejorada..." -ForegroundColor Yellow
Start-Process "$baseUrl/pagina_principal.php"
Start-Sleep -Milliseconds 1000

Write-Host "Abriendo paginas principales..." -ForegroundColor Yellow
foreach ($pagina in $paginas) {
    if ($pagina -ne "pagina_principal.php") {
        $url = "$baseUrl/$pagina"
        Write-Host "  Abriendo: $url" -ForegroundColor Cyan
        Start-Process $url
        Start-Sleep -Milliseconds 500
    }
}

Write-Host "Abriendo formularios RF mejorados..." -ForegroundColor Yellow
foreach ($formulario in $formularios) {
    $url = "$baseUrl/$formulario"
    Write-Host "  Abriendo: $url" -ForegroundColor Cyan
    Start-Process $url
    Start-Sleep -Milliseconds 500
}

Write-Host "Abriendo evaluaciones..." -ForegroundColor Yellow
foreach ($evaluacion in $evaluaciones) {
    $url = "$baseUrl/$evaluacion"
    Write-Host "  Abriendo: $url" -ForegroundColor Cyan
    Start-Process $url
    Start-Sleep -Milliseconds 500
}

Write-Host "Sistema abierto completamente!" -ForegroundColor Green
Write-Host "Total de paginas abiertas: $($paginas.Count + $formularios.Count + $evaluaciones.Count)" -ForegroundColor White

# Mostrar informacion del sistema mejorado
Write-Host "`nINFORMACION DEL SISTEMA MEJORADO:" -ForegroundColor Magenta
Write-Host "  Directorio: C:\xampp\htdocs\registro\registro" -ForegroundColor White
Write-Host "  Base de datos: registro" -ForegroundColor White
Write-Host "  Tablas: 28 tablas creadas" -ForegroundColor White
Write-Host "  Datos: 43 registros de prueba" -ForegroundColor White
Write-Host "  Interfaz: Mejorada con efectos modernos" -ForegroundColor White

Write-Host "`nURLs PRINCIPALES MEJORADAS:" -ForegroundColor Magenta
Write-Host "  Pagina Principal: $baseUrl/pagina_principal.php" -ForegroundColor White
Write-Host "  Dashboard: $baseUrl/dashboard.php" -ForegroundColor White
Write-Host "  Sistema Integrado: $baseUrl/sistema_integrado.php" -ForegroundColor White

Write-Host "`nNOVEDADES:" -ForegroundColor Yellow
Write-Host "  • Nueva pagina principal sin login requerido" -ForegroundColor White
Write-Host "  • Interfaz mejorada con efectos visuales" -ForegroundColor White
Write-Host "  • Navegacion moderna y responsive" -ForegroundColor White
Write-Host "  • Animaciones y transiciones suaves" -ForegroundColor White
Write-Host "  • Acceso directo a todos los modulos RF" -ForegroundColor White

Write-Host "`n¡Disfruta explorando el Sistema Turistico Ecuador mejorado!" -ForegroundColor Green

