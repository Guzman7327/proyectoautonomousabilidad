# Script para abrir el Sistema Turistico Ecuador
# Ejecutar: .\abrir_sistema_simple.ps1

Write-Host "Abriendo Sistema Turistico Ecuador..." -ForegroundColor Green

# URLs base
$baseUrl = "http://localhost/registro/registro"

# Paginas principales
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

Write-Host "Abriendo paginas principales..." -ForegroundColor Yellow
foreach ($pagina in $paginas) {
    $url = "$baseUrl/$pagina"
    Write-Host "  Abriendo: $url" -ForegroundColor Cyan
    Start-Process $url
    Start-Sleep -Milliseconds 500
}

Write-Host "Abriendo formularios RF..." -ForegroundColor Yellow
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

# Mostrar informacion del sistema
Write-Host "`nINFORMACION DEL SISTEMA:" -ForegroundColor Magenta
Write-Host "  Directorio: C:\xampp\htdocs\registro\registro" -ForegroundColor White
Write-Host "  Base de datos: registro" -ForegroundColor White
Write-Host "  Tablas: 28 tablas creadas" -ForegroundColor White
Write-Host "  Datos: 43 registros de prueba" -ForegroundColor White

Write-Host "`nURLs PRINCIPALES:" -ForegroundColor Magenta
Write-Host "  Pagina Principal: $baseUrl/index.php" -ForegroundColor White
Write-Host "  Dashboard: $baseUrl/dashboard.php" -ForegroundColor White
Write-Host "  Sistema Integrado: $baseUrl/sistema_integrado.php" -ForegroundColor White

Write-Host "`nCONSEJOS:" -ForegroundColor Yellow
Write-Host "  • Usa el Sistema Integrado para navegar por todos los modulos" -ForegroundColor White
Write-Host "  • El Dashboard muestra estadisticas y acceso rapido" -ForegroundColor White
Write-Host "  • Los formularios RF estan completamente funcionales" -ForegroundColor White
Write-Host "  • Las evaluaciones muestran analisis detallados" -ForegroundColor White

Write-Host "`n¡Disfruta explorando el Sistema Turistico Ecuador!" -ForegroundColor Green

