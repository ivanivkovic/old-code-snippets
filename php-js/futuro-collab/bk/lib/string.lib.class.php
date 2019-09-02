<?php

class libString
{

	/*
	 * Metoda za prevaranje string au oznaku
	 * @param string $String
	 * @return string
	 */
	public function ToLabel($String)
	{

		$String = trim($String);
		do
		{
			$String = str_replace('  ', ' ', $String, $Count); //$Count govori koliko je znamjena je napravljeno
		}
		while( $Count );

		/*
		 * @todo naći pravu kombinaciju što se tiče utf-8
		 */
		foreach(Conf::$HrCharConvSearch as $HrKey => $HrChar)
		{
			$String = mb_ereg_replace($HrChar, Conf::$HrCharConvReplace[$HrKey], $String);
		}

		$String = strtolower($String);
		$Label = '';
		for( $Index = 0; $Index < strlen($String); $Index++)
		{
			if( in_array($String[$Index], Conf::$EnglishAlphabet) )
			{
				$Label .= $String[$Index];
			}
		}

		$Label = strtolower($Label);
		$Label = str_replace(' ', '-', $Label);
		return $Label;
	}
	
	/*
	 * @desc Metoda za čišćenje hr znakova
	 * @param string $String
	 * @return string
	 */
	public function RemoveHr($String)
	{
		foreach (Conf::$HrCharConvSearch as $HrKey => $HrChar)
		{
			$String = mb_ereg_replace($HrChar, Conf::$HrCharConvReplace[$HrKey], $String);
		}
		return $String;
	}
	
	/*
	 * Metoda za smanjivanje svih slova u mala
	 * @param string $String
	 * @return string
	 */
	public static function ToLower($String)
	{
		$String = strtolower($String);
		$String = str_replace(Conf::$LettersUpper, Conf::$LettersLower, $String);
		return $String;
	}
	
	
	/*
	 * Metoda za povećavanje svih slova
	 * @param string $String
	 * @return string
	 */
	public static function ToUpper($String)
	{
		$String = strtoupper($String);
		$String = str_replace(Conf::$LettersLower, Conf::$LettersUpper, $String);
		return $String;
	}
	
	
	/*
	 * Metoda za povećanje početnih slova riječi
	 * @param string $String
	 * @returnstring
	 */
	public static function Capitalise($String)
	{
		$String = ucwords($String);
		$Words = str_word_count($String, 1);

		foreach( $Words as $Word )
		{
			if( in_array($Word[0], Conf::$LettersLower) )
			{
				$WordOld = $Word;
				$Word[0] = Conf::StringToUpper($Word[0]);
				str_replace($WordOld, $Word, $String);
			}
		}
		return $String;
	}
	
	/*
	 * metoda za detekciju ne markiran linkova unutar teksta
	 * @param string $Text
	 * @return string
	 */
	public static function UrlToA($Text, $Blank = false)
	{
		$Blank = $Blank === true ? ' target="_blank" ' : '';
		preg_match_all('/<a.*<\/a>/U', $Text, $UrlMatches);
		$UrlStorage = array();
		foreach( $UrlMatches[0] as $UrlMatchKey => $MatchedUrl)
		{
			$Text = str_replace($MatchedUrl, '@link_placment_' . $UrlMatchKey, $Text);
			$UrlStorage ['link_placment_' . $UrlMatchKey] = $MatchedUrl;
		}
		
		//preg_match_all('/(?<!href=")\b(?:https?|ftp|file)(?::\/\/|www\.|ftp\.)(?:\([-A-Z0-9+&@#\/%=~_|$?!:,.]*\)|[-A-Z0-9+&@#\/%=~_|$?!:,.])*(?:\([-A-Z0-9+&@#\/%=~_|$?!:,.]*\)|[A-Z0-9+&@#\/%=~_|$])/i', $Text, $Matches);
		$Text = preg_replace('/\b(?:https?|ftp|file)(?::\/\/|www\.|ftp\.)(?:\([-A-Z0-9+&@#\/%=~_|$?!:,.]*\)|[-A-Z0-9+&@#\/%=~_|$?!:,.])*(?:\([-A-Z0-9+&@#\/%=~_|$?!:,.]*\)|[A-Z0-9+&@#\/%=~_|$])/i', '<a href="$0"' . $Blank . '>$0</a>', $Text);
		$Text = preg_replace('/\b(?<!:\/\/)(?:www\.|ftp\.)(?:\([-A-Z0-9+&@#\/%=~_|$?!:,.]*\)|[-A-Z0-9+&@#\/%=~_|$?!:,.])*(?:\([-A-Z0-9+&@#\/%=~_|$?!:,.]*\)|[A-Z0-9+&@#\/%=~_|$])/i', '<a href="http://$0"' . $Blank . '>$0</a>', $Text);
		$Text = preg_replace(Conf::$FindEmailRegEx, '<a href="mailto:$0">$0</a>', $Text);
		$UrlStorage = array_reverse($UrlStorage, true);
		foreach( $UrlStorage as $UrlMatchKey => $UrlData )
		{
			$Text = str_replace('@'.$UrlMatchKey, $UrlData, $Text);
		}
		return $Text;
	}
	
	
	/*
	 * metoda za pretvaranje novg reda u html tag
	 * @param string $Text
	 * @return string
	 */
	
	public static function Nl2Br($Text)
	{
		return nl2br($Text);
	}
	
	/**
	* Ako je string veći od ograničenja, metoda ograniči string i doda mu "..." na kraju. Ne lomi riječi.
	* @param string $string
	* @param int $limit
	* @return string
	*/
	
	public static function limitString( $string, $limit = 100 )
	{
		if( strlen( $string ) <= $limit ){ return $string; }

		$string = substr($string, 0, $limit);
		$string = substr($string, 0, strrpos($string, ' ')) . '...';
		
		return $string;
	}

}
