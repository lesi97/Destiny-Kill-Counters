<?php

/*
Twitch Nightbot Command:
!addcom !beloved mendez currently has $(urlfetch https://lesi.org.uk/d2/mendez/beloved) kills on their beloved
*/

//	Variables

	$api_key = getenv("BUNGIE_KEY");

	$bungieEndpoint = 'https://www.bungie.net/Platform/';
	$endpointType = 'Destiny2/';

	$membershipType = '3/';
	$destinyMembershipId = '4611686018506946834/';
	$warlock = '2305843009688884497/';
	$hunter = '2305843010056444208/';
	$titan = '2305843010057644260/';
	
	$characterEquipment = '205';
	$itemPlugObjectives = '309';
	$components = "?components=" . $characterEquipment . "," . $itemPlugObjectives;

	$weapon = '6917529858084431385'; // Changes depending on the weapon (2 different beloved's will have different id's here)
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
