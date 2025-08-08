# Script para abrir la página principal del Portal Turístico Ecuador
# Sistema completo implementado

Write-Host "Abriendo Portal Turistico Ecuador - Pagina Principal..." -ForegroundColor Green
Write-Host "Sistema completo con 4 modulos implementados" -ForegroundColor Cyan
Write-Host "URL principal: http://localhost/registro/registro/" -ForegroundColor Yellow

# Abrir página principal en el navegador
Start-Process "http://localhost/registro/registro/index.html"

# Esperar un momento
Start-Sleep -Seconds 2

Write-Host "Pagina principal abierta exitosamente!" -ForegroundColor Green
Write-Host "" 
Write-Host "URLs adicionales del sistema:" -ForegroundColor Cyan
Write-Host "- Sistema Integrado: http://localhost/registro/registro/sistema_integrado.php" -ForegroundColor White
Write-Host "- Dashboard: http://localhost/registro/registro/dashboard.php" -ForegroundColor White
Write-Host "- Reservas y Pagos: http://localhost/registro/registro/formulario_rf7.php" -ForegroundColor White
Write-Host "- Login: http://localhost/registro/registro/login.php" -ForegroundColor White
Write-Host ""
Write-Host "Sistema listo para uso!" -ForegroundColor Green

# Mantener la ventana abierta
Read-Host "Presiona Enter para cerrar..."
