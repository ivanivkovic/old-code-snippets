<?php 
/*
 * klasa za datume
 */

class libDateTime
{
	/*
	 * @desc statička metoda za ispisivanje datuma po željenom stringu
	 * @param string $DateString
	 * @param int|bool $TimeStamp
	 * @return string
	 */
	 
	public static $months = array(
	
							'en' =>
								
								array(
									1 => 'January',
									2 => 'February',
									3 => 'March',
									4 => 'April',
									5 => 'May',
									6 => 'June',
									7 => 'July',
									8 => 'August',
									9 => 'September',
									10 => 'October',
									11 => 'November',
									12 => 'December'
								),
								
							'hr' =>
								
								array(
									1 => 'Siječanj',
									2 => 'Veljača',
									3 => 'Ožujak',
									4 => 'Travanj',
									5 => 'Svibanj',
									6 => 'Lipanj',
									7 => 'Srpanj',
									8 => 'Ožujak',
									9 => 'Rujan',
									10 => 'Listopad',
									11 => 'Studeni',
									12 => 'Prosinac'
								)
	);
	
	public static $days = array(
	
							'en' => 
							array(
								'today' => 'Today',
								1 => 'Monday',
								2 => 'Tuesday',
								3 => 'Wednesday',
								4 => 'Thursday',
								5 => 'Friday',
								6 => 'Saturday',
								7 => 'Sunday'
							),
							
							'hr' =>
							array(
								'today' => 'Danas',
								1 => 'Ponedjeljak',
								2 => 'Utorak',
								3 => 'Srijeda',
								4 => 'Četvrtak',
								5 => 'Petak',
								6 => 'Subota',
								7 => 'Nedjelja'
							)
	
						);
	
	public static function Date($DateString, $Timestamp = false)
	{
		if( $Timestamp !== false )
		{
			$Date = date($DateString, $Timestamp);
		}
		else
		{
			$Date = date($DateString, self::Time());
		}
		return $Date;
	}

	/*
	 * @desc broj sekundi od epohe
	 * @return int
	 */
	public static function Time()
	{
		//$UTCTimestamp = mktime(gmdate('H'));
		$UTCString = gmdate("M d Y H:i:s", time());
  		$UTCTimestamp = strtotime($UTCString);
		$UTCOffset = self::_UTCOffset();
		return $UTCTimestamp + $UTCOffset;
	}
	
	/*
	 * @pesc broj sekundi od epohe do zadanog datuma
	 * @param int $Hour
	 * @param int $Minute
	 * @param int $Second
	 * @param int $Day
	 * @param int $Month
	 * @param int $Year
	 * @retunr int
	 */
	public static function MKTime($Hour = false, $Minute = false, $Second = false, $Day = false, $Month = false, $Year = false)
	{
		if( $Hour === false )
		{
			$Hour = self::Date('H');
		}
		
		if( $Minute === false )
		{
			$Minute = self::Date('i');
		}
		
		if( $Second === false )
		{
			$Second = self::Date('s');
		}
		
		if( $Day === false )
		{
			$Day = self::Date('d');
		}
		
		if( $Month === false )
		{
			$Month = self::Date('m');
		}
		
		if( $Year === false )
		{
			$Year = self::Date('Y');
		}

		$UTCTime = mktime($Hour, $Minute, $Second, $Month, $Day, $Year);
		
		return $UTCTime; 
	}
	
	/*
	 * @desc metoda koja vraća udaljenost trenutne vremenske zone od UTCa (u sekundama)
	 * @return int
	 */
	private static function _UTCOffset()
	{
		$UTCOffset = Conf::$GTMOffset;
		
		$UTCOffset = $UTCOffset * 3600; //sati u sekunde
		return $UTCOffset;
	}
	
	
	/*
	 * @desc metoda koja parsira stringove sa vremenom i stvara timestamp
	 * @param string $Data
	 * @param string $Time
	 */
	public static function Parse($Date = false, $Time = false)
	{
		if( $Time === false || !preg_match('/^[0-9]{2}:[0-9]{2}$/', trim($Time)) )
		{
			$Time[0] = 0;
			$Time[1] = 0;
		}
		else
		{
			$Time = explode(':', $Time);
		}

		if( $Date === false || !preg_match('/^[0-9]{2}\.[0-9]{2}\.[0-9]{4}\.?$/', trim($Date)) )
		{
			$Date = self::Date('d.m.Y.');
		}
		
		$Date = explode('.', $Date);

		return self::MKTime($Time[0], $Time[1], 0, $Date[0], $Date[1], $Date[2]);
	}
	
	/*
	 * @desc iz zapisa Ymd radu d.m.Y
	 */
	public static function YmdToString($Date)
	{
		return $Date[6].$Date[7] . '.' .$Date[4].$Date[5] . '.' . $Date[0].$Date[1].$Date[2].$Date[3] . '.';
	}
}
