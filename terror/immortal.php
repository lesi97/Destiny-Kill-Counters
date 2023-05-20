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
        $immortalTracker = $data["Response"]["itemComponents"]["plugObjectives"]["data"]["6917529880229623656"]["objectivesPerPlug"]["38912240"]["0"]["progress"];
        $immortalKillsComma = number_format($immortalTracker);
        $terrorimmortal = "terror currently has " . $immortalKillsComma . " kills on his immortal";

		if ($immortalTracker !== null) {
			echo "terror currently has " . $immortalKillsComma . " kills on his immortal, to see the perks type !immortalperks";
			
			$jsonData = file_get_contents("terrorKills.json");
			$data1 = json_decode($jsonData, true);
			
			// Update the value of the ace_kills key
			$data1['immortal_kills'] = $immortalTracker;
			
			// Encode the updated array into JSON and write it back to the file
			$jsonData = json_encode($data1);
			file_put_contents("terrorKills.json", $jsonData);
		} else {
			
			$immortalJson = file_get_contents("terrorKills.json");
			$immortalData = json_decode($immortalJson, true);
			$immortalKills = $immortalData["immortal_kills"];
			$immortalKillsComma = number_format($immortalKills);
			$terrorimmortal = "terror currently has " . $immortalKillsComma . " kills on his immortal, to see the perks type !immortalperks";
			echo $terrorimmortal;
		}
	}

?>
