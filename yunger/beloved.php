<?php

//	Variables

	$api_key = getenv("BUNGIE_KEY");

	$bungieEndpoint = 'https://www.bungie.net/Platform/';
	$endpointType = 'Destiny2/';

	$membershipType = '1/';
	$destinyMembershipId = '4611686018448992398/';
	$warlock = '2305843009265182798/';
	$hunter = '2305843009265182796/';
	$titan = '2305843009969224918/';
	
	$characterEquipment = '205';
	$itemPlugObjectives = '309';
	$components = "?components=" . $characterEquipment . "," . $itemPlugObjectives;

	$weapon = '6917529798698579887'; // Changes depending on the weapon (2 different beloved's will have different id's here)
	$pveTracker = '905869860'; // Seems to be consistent across players and characters
	$crucibleTracker = '3244015567'; // Seems to be consistent across players and characters
	$trialsMementoTracker = '3915764595'; // Seems to be consistent across players and characters

	$url = $bungieEndpoint . $endpointType . $membershipType . "Profile/" . $destinyMembershipId . $components;

	$jsonKeyName = 'beloved_kills';
	$jsonFileName = 'kill-counts.json';

////////////////////////////////////////////////////////////////////////////////////

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
		$gunTracker = $data["Response"]["itemComponents"]["plugObjectives"]["data"][$weapon]["objectivesPerPlug"][$crucibleTracker]["0"]["progress"];
		if ($gunTracker !== null) {	
			$weaponKillsFormatted = number_format($gunTracker);
			echo $weaponKillsFormatted;
			$jsonData = file_get_contents($jsonFileName);
			$data1 = json_decode($jsonData, true);			
			$data1[$jsonKeyName] = $gunTracker;			
			$jsonData = json_encode($data1);
			file_put_contents($jsonFileName, $jsonData);			
		} else {
			$weaponKillCounts = file_get_contents($jsonFileName);
			$weaponKillCountsDecoded = json_decode($weaponKillCounts, true);
			$weaponKills = $weaponKillCountsDecoded[$jsonKeyName];
			$weaponKillsFormatted = number_format($weaponKills);
			$finalKillCount = "" . $weaponKillsFormatted . "";
			echo $finalKillCount;
		}
	}
		
?>
