<?php

//	Variables

	$api_key = getenv("BUNGIE_KEY");

	$destinyMembershipId = '4611686018506946834/';
	$membershipType = '3/';
	$warlock = '2305843009688884497/';
	$hunter = '2305843010056444208/';
	$titan = '2305843010057644260/';
	
	$bungieDomain = 'https://www.bungie.net/Platform/Destiny2/';
	$characterEquipment = '205';
	$itemPlugObjectives = '309';
	$components = "?components=" . $characterEquipment . "," . $itemPlugObjectives;

	$weapon = '6917529858084431385';
	$pveTracker = '905869860';
	$crucibleTracker = '3244015567';
	$trialsMementoTracker = '3915764595';

	$urlWarlock = $bungieDomain . $membershipType . "Profile/" . $destinyMembershipId . "Character/" . $warlock . $components;
	$urlHunter = $bungieDomain . $membershipType . "Profile/" . $destinyMembershipId . "Character/" . $$hunter . $components;
	$urlTitan = $bungieDomain . $membershipType . "Profile/" . $destinyMembershipId . "Character/" . $titan . $components;

	$jsonKeyName = 'beloved_kills';
	$jsonFileName = 'kill-counts.json';

////////////////////////////////////////////////////////////////////////////////////

//	Warlock

	$chWarlock = curl_init();
	curl_setopt($chWarlock, CURLOPT_URL, $urlWarlock);
	curl_setopt($chWarlock, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($chWarlock, CURLOPT_HTTPHEADER, array(
		'x-api-key: ' . $api_key
	));
	$response = curl_exec($chWarlock);
	curl_close($chWarlock);
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

////////////////////////////////////////////////////////////////////////////////////

//	Hunter

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

////////////////////////////////////////////////////////////////////////////////////

//	Titan

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

////////////////////////////////////////////////////////////////////////////////////

//	Kill Count

	$weaponKillCounts = file_get_contents($jsonFileName);
	$weaponKillCountsDecoded = json_decode($weaponKillCounts, true);
	$weaponKills = $weaponKillCountsDecoded[$jsonKeyName];
	$weaponKillsFormatted = number_format($weaponKills);
	$finalKillCount = "" . $weaponKillsFormatted . "";
	echo $finalKillCount;
		
?>
