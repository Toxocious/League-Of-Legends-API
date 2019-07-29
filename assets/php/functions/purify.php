<?php
	/**
	 * Filters user inputs.
	 */
	function Purify($input)
	{
		$text = $input;

		if ( is_array($text) )
		{
			foreach ( $text as $key => $T )
			{
				$T = htmlentities($T, ENT_NOQUOTES, "UTF-8");
				$T = nl2br($T, false);
				$text[$key] = $T;
			}
		}
		else
		{
			$text = htmlentities($text, ENT_NOQUOTES, "UTF-8");
			$text = nl2br($text, false);
		}

		return $text;
	}