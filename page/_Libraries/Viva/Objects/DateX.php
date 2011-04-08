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

	function __construct( $cls, $id, $name, $year, $month, $day )
	{
		$this->cls   = $cls;
		$this->id    = $id;
		$this->name  = $name;
		$this->year  = $year;
		$this->month = $month;
		$this->day   = $day;
	}
	
	function render( $out )
	{
		$out->println( "<div class='$this->cls' id='$this->id'>" );
		{
			$year_id  = $this->id . "_year";
			$month_id = $this->id . "_month";
			$day_id   = $this->id . "_day";

			$year_name  = $this->name . "_year";
			$month_name = $this->name . "_month";
			$day_name   = $this->name . "_day";
		
			if ( "0000" == $this->year ) {
				$out->println( "<input class='year'  title='year'  id='$year_id'  type='text' name='$year_name'  value='yyyy' onfocus='return clear_input( \"$year_id\" );'>"  );
			} else {
				$out->println( "<input class='year'  title='year'  id='$year_id'  type='text' name='$year_name'  value='$this->year'>"  );
			}

			if ( "00" == $this->month ) {
				$out->println( "<input class='month' title='month' id='$month_id' type='text' name='$month_name' value='mm' onfocus='return clear_input( \"$month_id\" );'>" );
			} else {
				$out->println( "<input class='month' title='month' id='$month_id' type='text' name='$month_name' value='$this->month'>" );
			}

			if ( "00" == $this->day ) {
				$out->println( "<input class='day'   title='day'   id='$day_id'   type='text' name='$day_name'   value='dd' onfocus='return clear_input( \"$day_id\" );'>"   );
			} else {
				$out->println( "<input class='day'   title='day'   id='$day_id'   type='text' name='$day_name'   value='$this->day'>"   );
			}
		}
		$out->println( "</div>" );
	}
}

?>