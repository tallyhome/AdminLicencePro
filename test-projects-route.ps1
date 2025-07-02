try {
    $response = Invoke-WebRequest -Uri 'http://127.0.0.1:8000/client/projects' -Method GET -UseBasicParsing
    Write-Host "Status: $($response.StatusCode)"
    Write-Host "Content-Length: $($response.Content.Length)"
    if ($response.StatusCode -ne 200) {
        Write-Host "Response: $($response.Content)"
    }
} catch {
    Write-Host "Error: $($_.Exception.Message)"
    if ($_.Exception.Response) {
        Write-Host "Status Code: $($_.Exception.Response.StatusCode)"
        try {
            $reader = New-Object System.IO.StreamReader($_.Exception.Response.GetResponseStream())
            $responseBody = $reader.ReadToEnd()
            Write-Host "Response Body: $responseBody"
            $reader.Close()
        } catch {
            Write-Host "Could not read response body"
        }
    }
}