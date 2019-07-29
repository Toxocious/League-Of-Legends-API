<?php
	$ID = 21;
	$Spell_ID = '';

	$Summoner_Spell_File = file_get_contents('../js/summonerspells.json');
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

	echo "<div style='border: 2px solid black;'>";
	echo 'ID: ' . $ID . '<br />';
	echo 'Spell ID: ' . $Spell_ID;
	echo "</div>";

	echo "<pre>"; var_dump($Summoner_Spells[21][$Spell_ID]); echo "</pre>";
	echo "<hr /><hr /><hr />";
	echo "<pre>"; var_dump($Summoner_Spells[21][$Spell_ID]['tooltip']); echo "</pre>";
	echo "<hr /><hr /><hr />";
	echo "<pre>"; var_dump($Summoner_Spells[21]); echo "</pre>";