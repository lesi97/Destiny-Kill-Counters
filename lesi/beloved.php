<?php

/*
Twitch Nightbot Command:
!addcom !beloved lesi has $(urlfetch https://lesi.org.uk/d2/lesi/beloved) kills on his beloved atm
*/

//	Variables

	$api_key = getenv("BUNGIE_KEY");

	$bungieEndpoint = 'https://www.bungie.net/Platform/';
	$endpointType = 'Destiny2/';

	$membershipType = '3/';
	$destinyMembershipId = '4611686018475555326/';
	$warlock = '2305843009343835744/';
	$hunter = '2305843009379837540/';
	$titan = '2305843009403888668/';
	
	$characterEquipment = '205';
	$itemPlugObjectives = '309';
	$components = "?components=" . $characterEquipment . "," . $itemPlugObjectives;

	$weapon = '6917529856526470637'; // Changes depending on the weapon (2 different beloved's will have different id's here)
	$pveTracker = '905869860'; // Seems to be consistent across players and characters
	$crucibleTracker = '3244015567'; // Seems to be consistent across players and characters
	$trialsMementoTracker = '3915764595'; // Seems to be consistent across players and characters

	$url = $bungieEndpoint . $endpointType . $membershipType . "Profile/" . $destinyMembershipId . $components;

	$jsonKeyName = 'beloved_kills';
	$jsonFileName = 'kill-counts.json';

////////////////////////////////////////////////////////////////////////////////////

//	Warlock

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

////////////////////////////////////////////////////////////////////////////////////

//	Hunter
/*
	$chHunter = curl_init();
	curl_setopt($chHunter, CURLOPT_URL, $urlHunter);
	curl_setopt($chHunter, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($chHunter, CURLOPT_HTTPHEADER, array(
		'x-api-key: ' . $api_key
	));
	$response = curl_exec($chHunter);
	curl_close($chHunter);
	$current_dateTime = date("Y-m-d H:i:s");

	if ($response !== false) {
		$data = json_decode($response, true);
		$gunTracker = $data["Response"]["itemComponents"]["plugObjectives"]["data"][$weapon]["objectivesPerPlug"][$crucibleTracker]["0"]["progress"];
		if ($gunTracker !== null) {			
			$jsonData = file_get_contents($jsonFileName);
			$data1 = json_decode($jsonData, true);			
			$data1[$jsonKeyName] = $gunTracker;			
			$jsonData = json_encode($data1);
			file_put_contents($jsonFileName, $jsonData);			
		}
	}
*/
////////////////////////////////////////////////////////////////////////////////////

//	Titan
/*
	$chTitan = curl_init();
	curl_setopt($chTitan, CURLOPT_URL, $urlTitan);
	curl_setopt($chTitan, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($chTitan, CURLOPT_HTTPHEADER, array(
		'x-api-key: ' . $api_key
	));
	$response = curl_exec($chTitan);
	curl_close($chTitan);
	$current_dateTime = date("Y-m-d H:i:s");
	if ($response !== false) {
		$data = json_decode($response, true);
		$gunTracker = $data["Response"]["itemComponents"]["plugObjectives"]["data"][$weapon]["objectivesPerPlug"][$crucibleTracker]["0"]["progress"];
		if ($gunTracker !== null) {						
			$jsonData = file_get_contents($jsonFileName);
			$data1 = json_decode($jsonData, true);			
			$data1[$jsonKeyName] = $gunTracker;			
			$jsonData = json_encode($data1);
			file_put_contents($jsonFileName, $jsonData);			
		} 
	}
*/
////////////////////////////////////////////////////////////////////////////////////

//	Kill Count

	$weaponKillCounts = file_get_contents($jsonFileName);
	$weaponKillCountsDecoded = json_decode($weaponKillCounts, true);
	$weaponKills = $weaponKillCountsDecoded[$jsonKeyName];
	$weaponKillsFormatted = number_format($weaponKills);
	$finalKillCount = "" . $weaponKillsFormatted . "";
	echo $finalKillCount;
		
?>
