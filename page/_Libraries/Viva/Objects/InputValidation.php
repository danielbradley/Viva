<?php

include_once( VIVA_INC . "/_Libraries/Viva/Objects/EmailAddress.php" );

class InputValidation
{
	function __construct( $request, $required )
	{
		$this->request  = $request;
		$this->required = $required;

		if ( is_array( $this->request ) && array_key_exists( "action", $this->request ) )
		{
			$text = "style='visibility:visible;display:inline-block;'";
		
			foreach ( $this->required as $name => $required )
			{
				switch ( $required )
				{
				case "AXN":
					if ( "" == array_get( $name, $this->request ) )
					{
						$this->required["$name"] = $text;
					}
					else if ( ! $this->isAXN( array_get( $name, $this->request ) ) )
					{
						$name_invalid = $name . "_invalid";
						$this->required["$name_invalid"] = $text;
					} else {
						$this->required["$name"] = "";
					}
					break;
				case "CHECKED":
					if ( "on" != array_get( $name, $this->request ) )
					{
						$this->required["$name"] = $text;
					} else {
						$this->required["$name"] = "";
					}
					break;
				case "DATE":
					$day   = $name . "_day";
					$month = $name . "_month";
					$year  = $name . "_year";

					$valid = True;
					$valid = $this->isDate( array_get( "$day", $this->request ), array_get( "$month", $this->request ), array_get( "$year", $this->request ) );
					
					if ( ! $valid )
					{
						$this->required["$name"] = $text;
					} else {
						$this->required["$name"] = "";
					}
					break;
				case "EMAIL":
					if ( "" == array_get( $name, $this->request ) )
					{
						$this->required["$name"] = $text;
					}
					else
					{
						$email_address = new EmailAddress( array_get( $name, $this->request ) );
						if ( $email_address->isValid( new NullPrinter() ) )
						{
							$this->required["$name"] = "";
						} else {
						$name_invalid = $name . "_invalid";
						$this->required["$name_invalid"] = $text;
						}
					}
					break;
				case "PHONE":
					if ( "" == array_get( $name, $this->request ) )
					{
						$this->required["$name"] = $text;
					}
					else if ( ! $this->isPhoneNumber( array_get( $name, $this->request ) ) )
					{
						$name_invalid = $name . "_invalid";
						$this->required["$name_invalid"] = $text;
					} else {
						$this->required["$name"] = "";
					}
					break;
				case "YEAR":
					if ( "" == array_get( $name, $this->request ) )
					{
						$this->required["$name"] = $text;
					}
					else if ( $this->isYear( array_get( $name, $this->request ) ) )
					{
						$this->required["$name"] = "";
					}
					else
					{
						$name_invalid = $name . "_invalid";
						$this->required["$name_invalid"] = $text;
					}
					break;
				case "FILE":
					if ( array_key_exists( $name, $_FILES ) )
					{
						$this->required["$name"] = "";
					}
					else
					{
						$this->required["$name"] = $text;
					}
					break;
				default:
					if ( "" == array_get( $name, $this->request ) )
					{
						$this->required["$name"] = $text;
					} else {
						$this->required["$name"] = "";
					}
				}
			}
		}
	}

		function isDate( $day, $month, $year )
		{
			$ret = False;

			if ( $this->isYear( $year ) )
			{
				if ( (1 <= $month) && ($month <= 12) )
				{
					if ( 1 <= $day )
					{
						switch ( $month )
						{
						case "1":
						case "3":
						case "5":
						case "7":
						case "8":
						case "10":
						case "12":
							$ret = ( $day <= 31 );
							break;
						case "2":
							$ret = ( $day <= 28 );
							break;
						case "4":
						case "6":
						case "9":
						case "11":
							$ret = ( $day <= 28 );
							break;
						}
					}
				}
			}
			return $ret;
		}

		function isYear( $value )
		{
			return ( is_numeric( $value ) && ( 1900 < $value ) && ( $value < 2020 ) );
		}
		
		function isPhoneNumber( $value )
		{
			$value = str_replace( " ", "", $value );

			if ( "+" == substr( $value, 0, 1 ) )
			{
				// International +61 etc. expect 10
				$value = str_replace( "+", "", $value );
				
				if ( "61" == substr( $value, 0, 2 ) )
				{
					return (9 == strlen( substr( $value, 2 ) ));
				} else {
					return True; // ???
				}
			} else {
				$bits = explode( ".", $value );
			
				switch( count( $bits ) )
				{
				case "1";
					if ( is_numeric( $value ) )
					{
						switch ( strlen( $bits[0] ) )
						{
						case 8:  // Local 32902441 
							return True;
						case 10: // Mobile 0412 669 210
							if ( "0" == substr( $value, 0, 1 ) ) return True;
							else return False;
							break;
						default:
							return False;
						}
					} else {
						return False;
					}
					break;
				case "2":
					if ( is_numeric( $bits[0] ) && is_numeric( $bits[1] ) )
					{
						if ( (2 == strlen( $bits[0] )) && ("0" == substr( $bits[0], 0, 1 )) )
						{
							if ( 8 == strlen( $bits[1] ) ) return True;
						}
					}
					return False;
					break;
				default:
					return False;
				}
				return False;
			}
		}

		function isAXN( $value )
		{
			$value = str_replace( " ", "", $value );

			return ( is_numeric( $value ) && ("11" == strlen( $value )) );
		}

	function validate()
	{
		$cont = True;
		foreach ( $this->required as $field => $value )
		{
			if ( "" != $value ) $cont = False;
		}
		return $cont;
	}

	function value( $field )
	{
		return array_key_exists( $field, $this->required ) ? $this->required["$field"] : null;
	}

	function ignore( $field )
	{
		$this->required["$field"] = "";
	}

	function flag( $field )
	{
		$this->required["$field"] = "style='visibility:visible;display:inline-block;'";
	}

	function depValue( $field1, $field2 )
	{
		if ( ! $this->required["$field2"] && $this->required["$field1"] )
		{
			return $this->required["$field1"];
		}
	}

	function all( $fields )
	{
		foreach ( $fields as $field )
		{
			if ( "" != array_get( $field, $this->required ) ) return $this->required["$field"];
		}
	}

	function encode()
	{
		$m = "";
		foreach ( $this->required as $name => $value )
		{
			$m .= "&" . $name . "=" . array_get( $name, $this->request );
		}
		return substr( $m, 1 );
	}
	
	function getMissing()
	{
		$missing = "<ul>";
		foreach ( $this->required as $field => $value )
		{
			$f1 = ucwords( $field );
			$f2 = str_replace( "_", " ", $f1 );
		
			if ( "" != $value ) $missing .= "<li>$f2</li>";
		}
		$missing .= "</ul>";
		return $missing;
	}

	function getRequired( $name )
	{
		$value = $this->value( $name );
		return "<span class='required' id='required_$name' $value>Required</span>";
	}
}

?>