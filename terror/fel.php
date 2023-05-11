<?php

    $url = 'https://www.bungie.net/Platform/Destiny2/3/Profile/4611686018467358417/Character/2305843009301476854/?components=309,205';
    $api_key = getenv("BUNGIE_KEY");

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'x-api-key: ' . $api_key
    ));
	$response = curl_exec($ch);
    curl_close($ch);

    if ($response !== false) {
        $data = json_decode($response, true);
        $felTracker = $data["Response"]["itemComponents"]["plugObjectives"]["data"]["6917529190261952418"]["objectivesPerPlug"]["3244015567"]["0"]["progress"];
        $felKillsComma = number_format($felTracker);
        $terrorFel = "terror currently has " . $felKillsComma . " kills on his felwinter";

		if ($felTracker !== null) {
			echo "terror currently has " . $felKillsComma . " kills on his felwinter.";			
			$jsonData = file_get_contents("terrorKills.json");
			$data1 = json_decode($jsonData, true);
			$data1['felwinter_kills'] = $felTracker;
			$jsonData = json_encode($data1);
			file_put_contents("terrorKills.json", $jsonData);
		} else {			
			$felJson = file_get_contents("terrorKills.json");
			$felData = json_decode($felJson, true);
			$felKills = $felData["felwinter_kills"];
			$felKillsComma = number_format($felKills);
			$terrorFel = "terror currently has " . $felKillsComma . " kills on his felwinter";
			echo $terrorFel;
		}
	}

?>