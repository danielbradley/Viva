<?php
//	Copyright (c) 2010 Daniel Robert Bradley. All rights reserved.
//	This software is distributed under the terms of the GNU Lesser General Public License version 2.1
?>
<?php

class SelectDate extends Element
{
	function __construct( $class, $name, $date )
	{
		$this->cls        = $class;
		$this->name       = $name;

		$bits = explode( "-", $date );

		$this->year       = array_get( 0, $bits );
		$this->month      = array_get( 1, $bits );
		$this->day        = array_get( 2, $bits );
		
		$this->selectDay   = $this->generateSelectDay();
		$this->selectMonth = $this->generateSelectMonth();
		$this->selectYear  = $this->generateSelectYear();
	}

	function render( $out )
	{
		$this->selectDay->render( $out );
		$this->selectMonth->render( $out );
		$this->selectYear->render( $out );
	}
	
		function generateSelectDay()
		{
			$options[''] = "day";
			for ( $i=1; $i <= 31; $i++ )
			{
				$options[$i] = "$i";
			}
		
			$attributes = "class='$this->cls' name='" . $this->name . "_day'";
		
			return new Select( $attributes, $this->day, $options );
		}

		function generateSelectMonth()
		{
			$options['']   = "month";
			$options['01'] = "Jan";
			$options['02'] = "Feb";
			$options['03'] = "Mar";
			$options['04'] = "Apr";
			$options['05'] = "May";
			$options['06'] = "Jun";
			$options['07'] = "Jul";
			$options['08'] = "Aug";
			$options['09'] = "Sep";
			$options['10'] = "Oct";
			$options['11'] = "Nov";
			$options['12'] = "Dec";
			
			$attributes = "class='$this->cls' name='" . $this->name . "_month'";
		
			return new Select( $attributes, $this->month, $options );
		}

		function generateSelectYear()
		{
			$options[''] = "year";
			for ( $i=2000; $i <= 2020; $i++ )
			{
				$options[$i] = "$i";
			}
		
			$attributes = "class='$this->cls' name='" . $this->name . "_year'";
		
			return new Select( $attributes, $this->year, $options );
		}
}

?>