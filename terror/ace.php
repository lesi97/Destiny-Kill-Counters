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

    $current_dateTime = date("Y-m-d H:i:s");

    if ($response !== false) {
        $data = json_decode($response, true);
        $aceTracker = $data["Response"]["itemComponents"]["plugObjectives"]["data"]["6917529207684081719"]["objectivesPerPlug"]["38912240"]["0"]["progress"];
        $aceKillsComma = number_format($aceTracker);
        $terrorAce = "terror currently has " . $aceKillsComma . " kills on his ace";

        if ($aceTracker !== 0) {			
			echo "terror currently has " . $aceKillsComma . " kills on his ace.";			
			$jsonData = file_get_contents("terrorKills.json");
			$data1 = json_decode($jsonData, true);
			$data1['ace_kills'] = $aceTracker;
			$jsonData = json_encode($data1);
			file_put_contents("terrorKills.json", $jsonData);			
		} else {			
			$aceJson = file_get_contents("terrorKills.json");
			$aceData = json_decode($aceJson, true);
			$aceKills = $aceData["ace_kills"];
			$aceKillsComma = number_format($aceKills);
			$terrorAce = "terror currently has " . $aceKillsComma . " kills on his ace";
			echo $terrorAce;
		}
	}
?>