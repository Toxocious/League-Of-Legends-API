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
				
					echo "
						<div class='match'>
							<div class='match-head'>
								Game Mode - Game Duration
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