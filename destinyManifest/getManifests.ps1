$downloadsFolder = (New-Object -ComObject Shell.Application).NameSpace('shell:Downloads').Self.Path
New-Item -Path "$downloadsFolder\Destiny_Manifest" -ItemType Directory
New-Item -Path "$downloadsFolder\Destiny_Manifest\UTF16Files" -ItemType Directory
Set-Location -Path "$downloadsFolder\Destiny_Manifest"
$source = "$downloadsFolder\Destiny_Manifest\UTF16Files\"
$destination = "$downloadsFolder\Destiny_Manifest\"

$destinyEndpoint = "https://bungie.net/"
$destinyManifest = Invoke-WebRequest -Uri "https://bungie.net/Platform/Destiny2/Manifest" | ConvertFrom-Json
$jsonWorldComponentContentPaths = $destinyManifest.Response.jsonWorldComponentContentPaths.en

foreach ($key in $jsonWorldComponentContentPaths.PSObject.Properties.Name) {
    $url = $destinyEndpoint + $jsonWorldComponentContentPaths.key
    $response = Invoke-RestMethod -Uri $url -Method Get | Out-File -FilePath "$downloadsFolder\Destiny_Manifest\UTF16Files\$key.json"
}

Get-ChildItem -Path $source -Recurse -File *.json | ForEach-Object {
    $content = Get-Content -Encoding Unicode -Path $_.FullName
    [System.IO.File]::WriteAllLines((Join-Path -Path $destination -ChildPath $_.Name), $content, [System.Text.Encoding]::UTF8)
}

Remove-Item -Path $source -Force -Recurse
