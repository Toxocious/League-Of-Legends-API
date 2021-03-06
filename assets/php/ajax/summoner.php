<?php
	// Fetch the Purify function to assist in sanitizing user sent inputs.
	require '../functions/purify.php';

	// Call the Summoner Class.
	require '../classes/summoner.php';

	// Fetch any environmental data that we may need, such as our API Key.
	$Environment = parse_ini_file('../../../config/environment.ini');

	// Sanitize both of the sent inputs.
	$Summoner_Name = Purify($_GET['Summoner']);
	$Summoner_Region = Purify($_GET['Region']);

	// Instantiate a new Summoner and fetch their Summoner data.
	$Summoner = new Summoner();
	$Summoner_Data = $Summoner->FetchSummoner($Summoner_Name, $Summoner_Region);

	/**
	 * Display the appropriate summoner data on the page.
	 */
?>

<div class='profile-banner'>
	<div class='profile-icon'>
		<div class='profile-name'>
			<div>
				<?= $Summoner_Data[0]['name']; ?>
			</div>
		</div>

		<img src='assets/images/profile/<?= $Summoner_Data[0]['profileIconId']; ?>.png' />

		<div class='profile-level'>
			<div>
				<?= number_format($Summoner_Data[0]['summonerLevel']); ?>
			</div>
		</div>
	</div>
</div>

<div class='profile-nav'>
	<div class='profile-nav-item active' onclick='DisplayTab("match-history");'>
		Match History
	</div>
	<div class='profile-nav-item' onclick='DisplayTab("champion-mastery");'>
		Champion Mastery
	</div>
</div>

<div class='profile-container'>
	<div class='profile-content'>
		<div id='match-history' style='display: block;'>
			<?php
				/**
				 * For each retrieved match data in the Summoner's match history, fetch specific data regarding each match.
				 */
				foreach ( $Summoner_Data[2]['matches'] as $Match_Key => $Match_Val )
				{
					$Match_ID = $Match_Val['gameId'];
				
					$Match_Region = $Summoner->FetchRegion($Summoner_Region);
					$Match_Data = $Summoner->FetchMatch($Match_ID, $Match_Region[1]);

					$Summoner_Player_ID = $Match_Data['Players'][1][0]['Player_ID'];
					$Summoner_Team_ID = $Match_Data['Players'][1][0]['Team_ID'];
					$Summoner_Champion_ID = $Match_Data['Players'][1][0]['Champion_ID'];
					$Summoner_Spell_1 = $Match_Data['Players'][$Summoner_Player_ID][0]['Summoner_Spell_1'];
					$Summoner_Spell_2 = $Match_Data['Players'][$Summoner_Player_ID][0]['Summoner_Spell_2'];
					$Summoner_WinOrLose = $Match_Data['Teams'][$Match_Data['Players'][$Summoner_Player_ID][0]['Team_ID']]['WinOrLose'];
					
					$Champion_Data = $Summoner->FetchChampion( $Match_Val['champion'] );
					$Summoner_Spell_Data_1 = $Summoner->FetchSummonerSpell($Summoner_Spell_1);
					$Summoner_Spell_Data_2 = $Summoner->FetchSummonerSpell($Summoner_Spell_2);

					/**
					 * An array of the items that the user bought during the match.
					 */
					$Summoner_Item_Data = [
						$Match_Data['Players'][$Summoner_Player_ID]['Stats']['item0'],
						$Match_Data['Players'][$Summoner_Player_ID]['Stats']['item1'],
						$Match_Data['Players'][$Summoner_Player_ID]['Stats']['item2'],
						$Match_Data['Players'][$Summoner_Player_ID]['Stats']['item3'],
						$Match_Data['Players'][$Summoner_Player_ID]['Stats']['item4'],
						$Match_Data['Players'][$Summoner_Player_ID]['Stats']['item5'],
						$Match_Data['Players'][$Summoner_Player_ID]['Stats']['item6'],
					];

					/**
					 * Loop through the Summoner's items.
					 * If the Item == 0, they didn't buy an item, and we're going to force a blank image ID on it.
					 */
					$Item_Inc = 0;
					foreach ( $Summoner_Item_Data as $Item )
					{
						if ( $Item == 0 )
						{
							$Summoner_Item_Data[$Item_Inc] = '3637';
						}

						$Item_Inc++;
					}

					/**
					 * Determine if the user won or lost.
					 * We're going to use this to style the element.
					 */
					switch ( $Summoner_WinOrLose )
					{
						case 'Win':
							$BG_Color = '#001900';
							break;

						case 'Fail':
							$BG_Color = '#190000';
							break;
					}
				
					/**
					 * Display the requested matches.
					 */
					echo "
						<div class='match' style='background-color: {$BG_Color};'>
							<div class='match-icons'>
								<!-- Champion Icon -->
								<div class='match-icon'>
									<img src='{$Champion_Data['src']}' />
								</div>

								<!-- Summoner Spell Icons -->
								<div class='match-icon' style='height: 40px; margin: -100px 0px 0px 80px; position: absolute; width: 40px;'>
									<img src='assets/images/spell/{$Summoner_Spell_Data_1['image']['full']}' style='height: 40px; width: 40px;' />
								</div>
									
									<div class='match-icon' style='height: 40px; margin: -49px 0px 0px 80px; position: absolute; width: 40px;'>
										<img src='assets/images/spell/{$Summoner_Spell_Data_2['image']['full']}' style='height: 40px; width: 40px;' />
								</div>

								<!-- Item Icons -->
								<div class='match-icon' style='height: 40px; margin: -100px 0px 0px 395px; position: absolute; width: 40px;'>
									<img src='assets/images/item/{$Summoner_Item_Data[0]}.png' style='height: 40px; width: 40px;' />
								</div>
									
								<div class='match-icon' style='height: 40px; margin: -49px 0px 0px 395px; position: absolute; width: 40px;'>
									<img src='assets/images/item/{$Summoner_Item_Data[3]}.png' style='height: 40px; width: 40px;' />
								</div>

								<div class='match-icon' style='height: 40px; margin: -100px 0px 0px 445px; position: absolute; width: 40px;'>
									<img src='assets/images/item/{$Summoner_Item_Data[1]}.png' style='height: 40px; width: 40px;' />
								</div>
									
								<div class='match-icon' style='height: 40px; margin: -49px 0px 0px 445px; position: absolute; width: 40px;'>
									<img src='assets/images/item/{$Summoner_Item_Data[4]}.png' style='height: 40px; width: 40px;' />
								</div>

								<div class='match-icon' style='height: 40px; margin: -100px 0px 0px 495px; position: absolute; width: 40px;'>
									<img src='assets/images/item/{$Summoner_Item_Data[2]}.png' style='height: 40px; width: 40px;' />
								</div>
									
								<div class='match-icon' style='height: 40px; margin: -49px 0px 0px 495px; position: absolute; width: 40px;'>
									<img src='assets/images/item/{$Summoner_Item_Data[5]}.png' style='height: 40px; width: 40px;' />
								</div>

								<div class='match-icon' style='height: 40px; margin: -72px 0px 0px 545px; position: absolute; width: 40px;'>
									<img src='assets/images/item/{$Summoner_Item_Data[6]}.png' style='height: 40px; width: 40px;' />
								</div>		
							</div>

							<div style='margin: -70px 0px 0px 150px; position: absolute; text-align: center;'>
								<b>KDA</b><br />
								{$Match_Data['Players'][$Summoner_Player_ID]['Stats']['kills']}/{$Match_Data['Players'][$Summoner_Player_ID]['Stats']['deaths']}/{$Match_Data['Players'][$Summoner_Player_ID]['Stats']['assists']}
							</div>

							<div style='margin: -70px 0px 0px 250px; position: absolute; text-align: center;'>
								<b>Gold Earned</b><br />
								" . number_format($Match_Data['Players'][$Summoner_Player_ID]['Stats']['goldEarned']) . "
							</div>
						</div>
					";
				}
			?>
		</div>

		<div id='champion-mastery' style='display: none;'>
			<?php
				/**
				 * For each champion that the Summoner has mastery on, fetch the champion's data and display the appropriate data.
				 */
				foreach ( $Summoner_Data[1][0] as $Champ_Key => $Champ_Val )
				{
					$Champion_Data = $Summoner->FetchChampion($Champ_Val['championId']);

					echo "
						<div class='champion-content'>
							<div class='champion-icon'>
								<div class='champion-level'>
									<div>
										{$Champ_Val['championLevel']}
									</div>
								</div>

								<img src='{$Champion_Data['src']}' />

								<div class='champion-name'>
									<div>
										" . number_format($Champ_Val['championPoints']) . "
									</div>
								</div>
							</div>
						</div>
					";
				}
			?>
		</div>
	</div>
</div>

<script type='text/javascript'>
	/**
	 * Swap the .active class for the nav items.
	 */
	$('.profile-nav-item').click(function()
	{
		$('.profile-nav-item').removeClass('active');
		$(this).addClass('active');
	});

	/**
	 * Display the desired tab and it's contents.
	 */
	function DisplayTab(Tab)
	{
		$('.profile-content > div').each(function()
		{
			if ( $(this).css('display', 'block') )
			{
				$(this).css('display', 'none');
			}
		});

		$('#' + Tab).css('display', 'block');
	}
</script>