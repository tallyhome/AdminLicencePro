Write-Host "=== Test des Services AdminLicence ==="
Write-Host ""

# Test API AdminLicence
try {
    $response = Invoke-RestMethod -Uri 'http://127.0.0.1:8000/api/test' -Method GET
    Write-Host "✓ API AdminLicence: Opérationnel"
    Write-Host "  Réponse: $($response | ConvertTo-Json -Compress)"
} catch {
    Write-Host "✗ API AdminLicence: Erreur - $($_.Exception.Message)"
}

# Test Dashboard Web
try {
    $response = Invoke-WebRequest -Uri 'http://127.0.0.1:8000' -Method GET -UseBasicParsing
    Write-Host "✓ Dashboard Web: Opérationnel (HTTP $($response.StatusCode))"
} catch {
    Write-Host "✗ Dashboard Web: Erreur - $($_.Exception.Message)"
}

# Test API v1
try {
    $response = Invoke-RestMethod -Uri 'http://127.0.0.1:8000/api/v1/test' -Method GET
    Write-Host "✓ API v1: Opérationnel"
} catch {
    Write-Host "✗ API v1: Erreur - $($_.Exception.Message)"
}

# Test API Traductions
try {
    $response = Invoke-RestMethod -Uri 'http://127.0.0.1:8000/api/translations' -Method GET
    Write-Host "✓ API Traductions: Opérationnel"
} catch {
    Write-Host "✗ API Traductions: Erreur - $($_.Exception.Message)"
}

Write-Host ""
Write-Host "=== Résumé des Tests ==="