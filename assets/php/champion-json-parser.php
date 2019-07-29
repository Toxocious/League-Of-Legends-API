<?php
	$Champion_File = file_get_contents("../js/champions.json");
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

	echo "<pre>"; var_dump($Champions[67]); echo "</pre>";