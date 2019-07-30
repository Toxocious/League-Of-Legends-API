<?php
	Class Summoner
	{
		/**
		 * Fetch a Summoner's data.
		 * @param string $Name
		 * @param string $Region
		 */
		public function FetchSummoner($Name, $Region)
		{
			global $Environment;

			$API_Key = $Environment['api_key'];

			$Region_Data = $this->FetchRegion($Region);

			$API_URL = "https://{$Region_Data[1]}/lol/summoner/v4/summoners/by-name/{$Name}?api_key={$API_Key}";

			// Fetch the necessary JSON from the Riot API in regards to the Summoner's personal data.
			$Fetch_Summoner_Data = curl_init();
			curl_setopt($Fetch_Summoner_Data, CURLOPT_URL, $API_URL);
			curl_setopt($Fetch_Summoner_Data, CURLOPT_RETURNTRANSFER, 1);
			$Summoner_Data_Result = curl_exec($Fetch_Summoner_Data);
			curl_close($Fetch_Summoner_Data);

			// JSON Decode into a PHP array for use.
			$Summoner_Data = json_decode($Summoner_Data_Result, true); 

			// Retrieve the Summoner's Champion Mastery data.
			$Mastery_Data = $this->FetchMasteries( $Summoner_Data['id'], $Region_Data[1] );

			// Retrieve the Summoner's Match History data.
			$Match_History = $this->FetchMatchHistory( $Summoner_Data['accountId'], $Region_Data[1] );

			// just null it; remove it later
			$Match_Data = null;

			// Return specific data about the requested Summoner.
			return [
				$Summoner_Data,
				$Mastery_Data,
				$Match_History,
				$Match_Data,
				$Region_Data[0],
			];
		}

		/**
		 * Fetch the data of a specific match.
		 * @param int $Match_ID
		 * @param string $Region
		 */
		public function FetchMatch($Match_ID, $Region)
		{
			global $Environment;

			$API_Key = $Environment['api_key'];

			$API_URL = "https://{$Region}/lol/match/v4/matches/{$Match_ID}?api_key={$API_Key}";

			// Fetch the necessary JSON from the Riot API in regards to the requested match.
			$Fetch_Match_Data = curl_init();
			curl_setopt($Fetch_Match_Data, CURLOPT_URL, $API_URL);
			curl_setopt($Fetch_Match_Data, CURLOPT_RETURNTRANSFER, 1);
			$Match_Data_Result = curl_exec($Fetch_Match_Data);
			curl_close($Fetch_Match_Data);

			$Fetch_Match = json_decode($Match_Data_Result, true);

			// General data that we need to keep track of.
			$Match = [
				'Game_ID' => $Fetch_Match['gameId'],
				'Game_Version' => $Fetch_Match['gameVersion'],
				'Region' => $Fetch_Match['platformId'],
				'Match_Creation_Date' => $Fetch_Match['gameCreation'],
				'Match_Length' => $Fetch_Match['gameDuration'],
				'Queue_ID' => $Fetch_Match['queueId'],
				'Queue_Name' => $Fetch_Match['gameType'],
				'Map_ID' => $Fetch_Match['mapId'],
				'Season_ID' => $Fetch_Match['seasonId'],
			];

			// Team data that we need to keep track of.
			$Match['Teams'] = [];
			foreach ( $Fetch_Match['teams'] as $Team_Key => $Team_Val )
			{
				$Match['Teams'][$Team_Val['teamId']] = [
					'Team_ID' => $Team_Val['teamId'],
					'WinOrLose' => $Team_Val['win'],
					'First_Blood' => $Team_Val['firstBlood'],
					'First_Tower' => $Team_Val['firstTower'],
					'First_Inhibitor' => $Team_Val['firstInhibitor'],
					'First_Baron' => $Team_Val['firstBaron'],
					'First_Dragon' => $Team_Val['firstDragon'],
					'First_RiftHerald' => $Team_Val['firstRiftHerald'],
					'Tower_Kills' => $Team_Val['towerKills'],
					'Inhibitor_Kills' => $Team_Val['inhibitorKills'],
					'Baron_Kills' => $Team_Val['baronKills'],
					'Dragon_Kills' => $Team_Val['dragonKills'],
					'Rift_Herald_Kills' => $Team_Val['riftHeraldKills'],
					'Vilemaw_Kills' => $Team_Val['vilemawKills'],
					'Dominion_Score' => $Team_Val['dominionVictoryScore'],
				];
			}

			// Data from each Summoner who participated in the match.
			$Match['Players'] = [];
			foreach ( $Fetch_Match['participants'] as $Player_Key => $Player_Val )
			{
				// Fetch general data of the player.
				$Match['Players'][$Player_Val['participantId']][] = [
					'Player_ID' => $Player_Val['participantId'],
					'Team_ID' => $Player_Val['teamId'],
					'Champion_ID' => $Player_Val['championId'],
					'Summoner_Spell_1' => $Player_Val['spell1Id'],
					'Summoner_Spell_2' => $Player_Val['spell2Id'],
					//'Highest_Rank' => $Player_Val['highestAchievedSeasonTier'],
				];
				
				// Fetch the player's stats from the match.
				$Match['Players'][$Player_Val['participantId']]['Stats'] = [];
				foreach ( $Player_Val['stats'] as $Stat_Key => $Stat_Val )
				{
					$Match['Players'][$Player_Val['participantId']]['Stats'] += [
						$Stat_Key => $Stat_Val
					];
				}
			}

			return $Match;
		}

		/**
		 * Fetch a Summoner's Match History.
		 * @param int $Account_ID
		 * @param string $Region
		 * @param int $Match_Count
		 */
		public function FetchMatchHistory($Account_ID, $Region, $Match_Count = 10)
		{
			global $Environment;

			$API_Key = $Environment['api_key'];

			// Ensure that at least one match is being requested.
			if ( $Match_Count < 1 )
			{
				$Match_Count = 1;
			}

			$API_URL = "https://{$Region}/lol/match/v4/matchlists/by-account/{$Account_ID}?endIndex={$Match_Count}&beginIndex=0&api_key={$API_Key}";

			// Fetch the necessary JSON from the Riot API in regards to the Summoner's match history data.
			$Fetch_Match_Data = curl_init();
			curl_setopt($Fetch_Match_Data, CURLOPT_URL, $API_URL);
			curl_setopt($Fetch_Match_Data, CURLOPT_RETURNTRANSFER, 1);
			$Match_Data_Result = curl_exec($Fetch_Match_Data);
			curl_close($Fetch_Match_Data);

			// JSON Decode into a PHP array for use.
			$Match_Data = json_decode($Match_Data_Result, true);

			return $Match_Data;
		}

		/**
		 * Fetch a Summoner's Champion Masteries
		 * @param int $Summoner_ID
		 * @param string $Region
		 */
		public function FetchMasteries($Summoner_ID, $Region)
		{
			global $Environment;

			$API_Key = $Environment['api_key'];

			$API_URLS = [
				"https://{$Region}/lol/champion-mastery/v4/champion-masteries/by-summoner/{$Summoner_ID}?api_key={$API_Key}",
				"https://{$Region}/lol/champion-mastery/v4/scores/by-summoner/{$Summoner_ID}?api_key={$API_Key}"
			];

			// Fetch the necessary JSON from the Riot API in regards to the Summoner's champion masteries.
			$Fetch_Mastery_Data = curl_init();
			curl_setopt($Fetch_Mastery_Data, CURLOPT_URL, $API_URLS[0]);
			curl_setopt($Fetch_Mastery_Data, CURLOPT_RETURNTRANSFER, 1);
			$Mastery_Data_Result = curl_exec($Fetch_Mastery_Data);
			curl_close($Fetch_Mastery_Data);

			// JSON Decode into a PHP array for use.
			$Mastery_Data = json_decode($Mastery_Data_Result, true);

			return [
				$Mastery_Data
			];
		}

		/**
		 * Given the selected Region, fetch the appropriate region value for the API URL.
		 * @param string $Region
		 */
		public function FetchRegion($Region)
		{
			$Region = strtoupper($Region);

			$Regions = [
				'BR' => [ 'br1', 'br1.api.riotgames.com' ],
				'EUNE' => [ 'eun1', 'eun1.api.riotgames.com' ],
				'EUW' => [ 'euw1', 'euw1.api.riotgames.com' ],
				'JP' => [ 'jp1', 'jp1.api.riotgames.com' ],
				'KR' => [ 'kr1', 'kr1.api.riotgames.com' ],
				'LAN' => [ 'la1', 'la1.api.riotgames.com' ],
				'LAS' => [ 'la1', 'la2.api.riotgames.com' ],
				'NA' => [ 'na1', 'na1.api.riotgames.com' ],
				'OCE' => [ 'oc1', 'oc1.api.riotgames.com' ],
				'TR' => [ 'tr1', 'tr1.api.riotgames.com' ],
				'RU' => [ 'ru1', 'ru1.api.riotgames.com' ],
				'PBE' => [ 'pbe1', 'pbe1.api.riotgames.com' ],
			];

			return $Regions[$Region];
		}

		/**
		 * Given the ID of a Champion, fetch a complete list of data pertaining to them.
		 * @param int $ID
		 */
		public function FetchChampion($ID)
		{
			/**
			 * In regards to the file, upload the most recent DDragon Champions.json file to the js directory.
			 * The script will automatically parse it and fetch the complete stats of every champion.
			 */
			$Champion_File = file_get_contents("../../js/champions.json");
			$Champion_JSON = json_decode($Champion_File, true);

			$Champions = [];
			foreach ( $Champion_JSON['data'] as $Champion_Key => $Champion_Val )
			{
				if ( is_array($Champion_Key) )
				{
					foreach ( $Champion_Key as $Champion_Sub_Key => $Champion_Sub_Val )
					{
						$Champions[$Champion_Val['key']] = [
							"{$Champion_Sub_Key}" => $Champion_Sub_Val
						];
					}
				}
				else
				{
					$Champions[$Champion_Val['key']] = [
						"{$Champion_Key}" => $Champion_Val
					];
				}

				$Champions[$Champion_Val['key']] += [
					'src' => "assets/images/champion/{$Champion_Val['id']}.png"
				];
			}

			return $Champions[$ID];
		}

		/**
		 * Given the specific ID of a spell, fetch a complete list of it's data.
		 * @param int $ID
		 */
		public function FetchSummonerSpell($ID)
		{
			$Spell_ID = null;

			$Summoner_Spell_File = file_get_contents('../../js/summonerspells.json');
			$Summoner_Spell_JSON = json_decode($Summoner_Spell_File, true);

			$Summoner_Spells = [];
			foreach ( $Summoner_Spell_JSON['data'] as $Sum_Spell_Key => $Sum_Spell_Val )
			{
				if ( $Sum_Spell_Val['key'] == $ID )
				{
					$Spell_ID = $Sum_Spell_Val['id'];
				}

				if ( is_array($Sum_Spell_Key) )
				{
					foreach ( $Sum_Spell_Key as $Sum_Spell_Sub_Key => $Sum_Spell_Sub_Val )
					{
						$Summoner_Spells[$Sum_Spell_Val['key']] = [
							"{$Sum_Spell_Sub_Key}" => $Sum_Spell_Sub_Val
						];
					}
				}
				else
				{
					$Summoner_Spells[$Sum_Spell_Val['key']] = [
						"{$Sum_Spell_Key}" => $Sum_Spell_Val
					];
				}
			}

			return $Summoner_Spells[$ID][$Spell_ID];
		}
	}