# Script para abrir todos los enlaces principales del Portal Turístico Ecuador
# Sistema integrado completamente configurado

Write-Host "Abriendo Portal Turistico Ecuador - Enlaces Principales" -ForegroundColor Green
Write-Host "========================================================" -ForegroundColor Cyan
Write-Host ""

# URLs principales del sistema
$urls = @(
    "http://localhost/registro/registro/pagina_principal.php",
    "http://localhost/registro/registro/sistema_integrado.php", 
    "http://localhost/registro/registro/dashboard.php",
    "http://localhost/registro/registro/index.html"
)

$nombres = @(
    "Pagina Principal (Entrada)",
    "Sistema Integrado Completo",
    "Dashboard Principal", 
    "Pagina Publica HTML"
)

Write-Host "Abriendo páginas principales del sistema:" -ForegroundColor Yellow
Write-Host ""

for ($i = 0; $i -lt $urls.Length; $i++) {
    Write-Host "[$($i+1)] Abriendo: $($nombres[$i])" -ForegroundColor White
    Write-Host "    URL: $($urls[$i])" -ForegroundColor Gray
    Start-Process $urls[$i]
    Start-Sleep -Seconds 1
}

Write-Host ""
Write-Host "Todas las páginas principales han sido abiertas!" -ForegroundColor Green
Write-Host ""
Write-Host "Enlaces del sistema:" -ForegroundColor Cyan
Write-Host "- Página Principal: https://portal-turistico-ecuador.com/pagina_principal.php" -ForegroundColor White
Write-Host "- Sistema Integrado: https://portal-turistico-ecuador.com/sistema_integrado.php" -ForegroundColor White  
Write-Host "- Dashboard: https://portal-turistico-ecuador.com/dashboard.php" -ForegroundColor White
Write-Host ""
Write-Host "Sistema completamente integrado y funcional!" -ForegroundColor Green

Read-Host "Presiona Enter para cerrar..."
