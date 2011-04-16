<?php
//	Copyright (c) 2010 Daniel Robert Bradley. All rights reserved.
//	This software is distributed under the terms of the GNU Lesser General Public License version 2.1
?>
<?php

class Date
{
	protected $year;
	protected $month;
	protected $day;

	function __construct( $date )
	{
		$bits = explode( " ", $date );

		$this->date = $bits[0];

		$date        = explode( "-", $this->date );

		$this->year  = $date[0];
		$this->month = $date[1];
		$this->day   = $date[2];
		
		if ( array_key_exists( 1, $bits ) )
		{
			$this->time = $bits[1];
			$time = explode( ":", $this->time );
			$this->hour = $time[0];
			$this->min  = $time[1];
		}
	}

	function getHour()
	{
		return $this->hour;
	}

	function getMinute()
	{
		return $this->min;
	}

	function get24Time()
	{
		return $this->time;
	}

	function getTime()
	{
		$tod = ( $this->hour < 12 ) ? "am" : "pm";
		
		switch ( $this->hour )
		{
		case "0":
			$hour = "12";
			break;
		case "12":
			$hour = "12";
			break;
		default:
			$hour = $this->hour % 12;
		}
		return "$hour:$this->min $tod";
	}

	function getDMY()
	{
		return $this->day . "/" . $this->month . "/" . $this->year;
	}

	function getDMonthY()
	{
		$day = ltrim( $this->day, '0' );
			
		return $day . " " . Date::getMonth( $this->month ) . " " . $this->year;
	}

	function getMonthYear()
	{
		return Date::getMonth( $this->month ) . " " . $this->year;
	}
	
	function getD() { return $this->day;   }
	function getM() { return $this->month; }
	function getY() { return $this->year;  }
	
	function getDayOfWeek()
	{
		$date = $this->year . "-" . $this->month . "-" . $this->day;
		$time = strtotime( $date );
		
		return Date::getDayOfWeekLabel( date('w', $time ) );
	}
	
	static function extract( $prefix, $array )
	{
		$y = array_get( $prefix . "_year",  $array );
		$m = array_get( $prefix . "_month", $array );
		$d = array_get( $prefix . "_day",   $array );
		
		return $y . "-" . $m . "-" . $d;
	}

	static function today()
	{
		return gmdate( "Y-m-d", time() );
	}
	
	static function getDayOfWeekLabel( $d )
	{
		$day = "";
		switch( $day )
		{
		case 0:
			$day = "Sunday";
			break;
		case 1:
			$day = "Monday";
			break;
		case 2:
			$day = "Tuesday";
			break;
		case 3:
			$day = "Wednesday";
			break;
		case 4:
			$day = "Thursday";
			break;
		case 5:
			$day = "Friday";
			break;
		case 6:
			$day = "Saturday";
			break;
		}
		
		return $day;
	}
	
	static function getMonth( $month )
	{
		$mth = "";
	
		switch ( $month )
		{
		case "1":
			$mth = "January";
			break;
		case "2":
			$mth = "February";
			break;
		case "3":
			$mth = "March";
			break;
		case "4":
			$mth = "April";
			break;
		case "5":
			$mth = "May";
			break;
		case "6":
			$mth = "June";
			break;
		case "7":
			$mth = "July";
			break;
		case "8":
			$mth = "August";
			break;
		case "9":
			$mth = "September";
			break;
		case "10":
			$mth = "October";
			break;
		case "11":
			$mth = "November";
			break;
		case "12":
			$mth = "December";
			break;
		}
	
		return $mth;
	}
}

?>