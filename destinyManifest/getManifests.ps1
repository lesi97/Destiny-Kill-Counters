$downloadsFolder = (New-Object -ComObject Shell.Application).NameSpace('shell:Downloads').Self.Path
New-Item -Path "$downloadsFolder\Destiny_Manifest" -ItemType Directory
$desintyManifestsFolder = "$downloadsFolder\Destiny_Manifest"
Set-Location -Path $desintyManifestsFolder

$destinyEndpoint = "https://bungie.net/"
$destinyManifest = Invoke-WebRequest -Uri "https://bungie.net/Platform/Destiny2/Manifest" | ConvertFrom-Json
$jsonWorldComponentContentPaths = $destinyManifest.Response.jsonWorldComponentContentPaths.en

foreach ($key in $jsonWorldComponentContentPaths.PSObject.Properties.Name) {
    $url = $destinyEndpoint + $jsonWorldComponentContentPaths.key
    $response = Invoke-RestMethod -Uri $url -Method Get | Out-File -FilePath "$desintyManifestsFolder\$key.json"
}

Get-ChildItem $desintyManifestsFolder -File | ForEach-Object {
    $content = Get-Content $_.FullName -Encoding Unicode
    Set-Content -Path $_.FullName -Value $content -Encoding UTF8 -Force
}
