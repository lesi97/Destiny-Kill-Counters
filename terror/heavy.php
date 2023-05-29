<?php
//set_time_limit(120);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

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

	$manifestUrl = $bungieEndpoint . $endpointType . "Manifest/DestinyInventoryItemDefinition/";

	$weaponName = "undefined";
	$weaponPerks = "";
	$pvpTracker = false;
	$pvpTrackerHash = "";
	$pvpKillCount = "";
	
	
////////////////////////////////////////////////////////////////////////////////////

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'x-api-key: ' . $api_key
	));
	$response1 = curl_exec($ch);
	curl_close($ch);
	$current_dateTime = date("Y-m-d H:i:s");

	if ($response1 !== false) {
		$data = json_decode($response1, true);
		// Get Most Recent Character
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
			
			// Get Kinetic Gun Name Hash And Perk Hashes
			$itemInstanceId = $data["Response"]["characterEquipment"]["data"][$currentCharacter]["items"]["2"]["itemInstanceId"];	
			$itemHash = $data["Response"]["characterEquipment"]["data"][$currentCharacter]["items"]["2"]["itemHash"];			
			$perkHashes = $data["Response"]["itemComponents"]["sockets"]["data"][$itemInstanceId]["sockets"];

			// Get Perk Hashes From $perkHashes Array
			for ($i = 0; $i < count($perkHashes); $i++){
				$perkHash[$i] = $perkHashes[$i]["plugHash"];
			}

			// Get Kinetic Gun name
			$chWepUrl = $manifestUrl . $itemHash . "/";
			$chWep = curl_init();
			curl_setopt($chWep, CURLOPT_URL, $chWepUrl);
			curl_setopt($chWep, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($chWep, CURLOPT_HTTPHEADER, array(
				'x-api-key: ' . $api_key
			));
			$chWepResponse = curl_exec($chWep);
			curl_close($chWep);
			if ($chWepResponse !== false) {
				$chWepData = json_decode($chWepResponse, true);
				//echo $chWepData;
				$weaponName = $chWepData["Response"]["displayProperties"]["name"];
			}			
			
			$newData = array();
			$perks = array();
			
			// Get Perk Names And Put Into $perks Array
			for ($i = 0; $i < count($perkHashes); $i++) {
				$manifestNewUrl[$i] = $manifestUrl . $perkHash[$i] . '/';
				$ch_i = curl_init();
				curl_setopt($ch_i, CURLOPT_URL, $manifestNewUrl[$i]);
				curl_setopt($ch_i, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch_i, CURLOPT_HTTPHEADER, array(
					'x-api-key: ' . $api_key
				));
				$execResult = curl_exec($ch_i);	
				if ($execResult !== false) {		
					$newData[$i] = json_decode($execResult, true);
					$perks[$i] = $newData[$i]["Response"]["displayProperties"]["name"];
					if ($perks[$i] == "Crucible Tracker") {
						$pvpTrackerHash = $perkHash[$i];
						$pvpTracker = true;
					}
				} else {
					echo "Curl error: " . curl_error($ch_i);
				}	
				curl_close($ch_i);

			}
			
			// Only Get The Perks People Care About
			$selectedPerks = array_slice($perks, 0, 5);
			$weaponPerks = implode(", ", $selectedPerks);					

			if ($pvpTracker === true) {
				echo $weaponName . " | Perks: " .  $weaponPerks . " | PVP Kill Count: " . $data["Response"]["itemComponents"]["plugObjectives"]["data"][$itemInstanceId]["objectivesPerPlug"][$pvpTrackerHash]["0"]["progress"];
			} else {
				echo $weaponName . " | Perks: " .  $weaponPerks;
			}

		} else {
			echo "(bungie api is currently down, gift a sub instead)";
		}
	} else {
		echo "(script failed to fetch, gift a sub instead)";
	}
	
?>