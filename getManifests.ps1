$downloadsFolder = (New-Object -ComObject Shell.Application).NameSpace('shell:Downloads').Self.Path
New-Item -Path "$downloadsFolder\Destiny_Manifest" -ItemType Directory
$destinyManifestsFolder = "$downloadsFolder\Destiny_Manifest"
Set-Location -Path $destinyManifestsFolder

$destinyEndpoint = "https://bungie.net/"
$destinyManifest = Invoke-WebRequest -Uri "https://bungie.net/Platform/Destiny2/Manifest" | ConvertFrom-Json
$jsonWorldComponentContentPaths = $destinyManifest.Response.jsonWorldComponentContentPaths.en

$index = 0
$totalArray = $jsonWorldComponentContentPaths.PSObject.Properties.Count
$total = $totalArray | Measure-Object -Sum | Select-Object -ExpandProperty Sum

foreach ($key in $jsonWorldComponentContentPaths.PSObject.Properties.Name) {
    $index++
    $url = $destinyEndpoint + ($jsonWorldComponentContentPaths | Select-Object -ExpandProperty $key)
    $response = Invoke-RestMethod -Uri $url -Method Get
    $response | ConvertTo-Json -Compress | Out-File -FilePath "$destinyManifestsFolder\$key.json"
    Write-Progress -Activity "Downloading JSON files" -Status "$index out of $total completed" -PercentComplete (($index / $total) * 100)
}

$files = Get-ChildItem $destinyManifestsFolder -File
$total = $files.Count
$index = 0

foreach ($file in $files) {
    $index++
    $content = Get-Content $file.FullName
    $utf8NoBomEncoding = New-Object System.Text.UTF8Encoding $false
    [System.IO.File]::WriteAllLines($file.FullName, $content, $utf8NoBomEncoding)
    Write-Progress -Activity "Encoding files" -Status "$index out of $total completed" -PercentComplete (($index / $total) * 100)
}