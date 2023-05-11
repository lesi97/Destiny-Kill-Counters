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
        $matadorTracker = $data["Response"]["itemComponents"]["plugObjectives"]["data"]["6917529875871677239"]["objectivesPerPlug"]["3244015567"]["0"]["progress"];
        $matadorKillsComma = number_format($matadorTracker);
        $terrorMatador = "terror currently has " . $matadorKillsComma . " kills on his matador, to see the perks, type !matadorperks";
		if ($matadorTracker !== 0) {			
			echo "terror currently has " . $matadorKillsComma . " kills on his matador, to see the perks, type !matadorperks.";			
			$jsonData = file_get_contents("terrorKills.json");
			$data1 = json_decode($jsonData, true);
			$data1['matador_kills'] = $matadorTracker;
			$jsonData = json_encode($data1);
			file_put_contents("terrorKills.json", $jsonData);			
		} else {			
			$matadorJson = file_get_contents("terrorKills.json");
			$matadorData = json_decode($matadorJson, true);
			$matadorKills = $matadorData["matador_kills"];
			$matadorKillsComma = number_format($matadorKills);
			$terrorMatador = "terror currently has " . $matadorKillsComma . " kills on his matador, to see the perks, type !matadorperks";
			echo $terrorMatador;
		}
	}		
?>