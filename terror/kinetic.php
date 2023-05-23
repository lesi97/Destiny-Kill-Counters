<?php

//	Variables

	$api_key = getenv("BUNGIE_KEY");	
	
	$bungieEndpoint = 'https://www.bungie.net/Platform/';
	$endpointType = 'Destiny2/';

	$membershipType = '3/';
	$destinyMembershipId = '4611686018467358417/';
	$warlock = '2305843009301476854';
	$hunter = '2305843009321995500';
	$titan = '2305843009369808628';
	
	$characters = '200';
	$characterEquipment = '205';
	$itemPerks = '302';
	$itemSockets = '305';
	$itemPlugObjectives = '309';
	$components = "?components=" . $characterEquipment . "," . $itemPlugObjectives . "," . $characters . "," . $itemSockets;

	$crucibleTracker = '38912240';

	$url = $bungieEndpoint . $endpointType . $membershipType . "Profile/" . $destinyMembershipId . $components;

	$perksJSON = file_get_contents(__DIR__.'/../currentSockets.json');
	$perksDecoded = json_decode($perksJSON, true);
	if (json_last_error() !== JSON_ERROR_NONE) {
		echo 'JSON decode error: ' . json_last_error_msg();
	}

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
		if (isset($data["ErrorStatus"]) && $data["ErrorStatus"] !== "SystemDisabled") {
			$warlockLastPlayed = date($data["Response"]["characters"]["data"][$warlock]["dateLastPlayed"]);
			$hunterLastPlayed = date($data["Response"]["characters"]["data"][$hunter]["dateLastPlayed"]);
			$titanLastPlayed = date($data["Response"]["characters"]["data"][$titan]["dateLastPlayed"]);
			
			$mostRecentCharacter = $warlockLastPlayed;
			$currentCharacter = $warlock;
			
			if ($hunterLastPlayed > $mostRecentCharacter) {
				$mostRecentCharacter = $hunterLastPlayed;
				$currentCharacter = $hunter;
			}
			if ($titanLastPlayed > $mostRecentCharacter) {
				$mostRecentCharacter = $titanLastPlayed;
				$currentCharacter = $titan;
			}
			
			$itemInstanceId = $data["Response"]["characterEquipment"]["data"][$currentCharacter]["items"]["0"]["itemInstanceId"];
			
			$perkHashes = $data["Response"]["itemComponents"]["sockets"]["data"][$itemInstanceId]["sockets"];
			
	
			for ($i = 0; $i < count($perkHashes); $i++){
				$sockets[$i] = $perkHashes[$i]["plugHash"];
			}
			echo $sockets[1];
		
			for ($i = 0; $i < count($perkHashes); $i++){
				$perk[$i] = $perksDecoded[$sockets[$i]]["displayProperties"]["name"];
			}

			echo $perk[1];
			
		} else {
			echo "(bungie api is currently down, how about you gift a sub?)";
		}
	} else {
		echo "(script failed to fetch, how about you gift a sub?)";
	}
		
?>
