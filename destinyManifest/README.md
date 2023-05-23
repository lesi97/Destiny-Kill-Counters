I usually get the Manifest in Powershell cause why not idk
Example below:

```
$manifest = Invoke-WebRequest -Uri "https://bungie.net/Platform/Destiny2/Manifest/"
$sockets = Invoke-WebRequest -Uri "https://bungie.net/common/destiny2_content/json/en/DestinySocketTypeDefinition-edde1f3a-f202-49d0-8a57-b8fe0295fec9.json"
$sockets.Content | Out-File -FilePath "E:\Downloads\currentSockets.json"
```

By default, the JSON is encoded in UTF-16 LE BOM, the PHP scripts in this repo need it to be encoded in UTF-8, you can do this by opening the file in Notepad++, select Encoding > UTF-8 and Save.
