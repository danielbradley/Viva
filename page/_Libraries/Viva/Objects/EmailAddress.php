<?php
//	Copyright (c) 2010 Daniel Robert Bradley. All rights reserved.
//	This software is distributed under the terms of the GNU Lesser General Public License version 2.1
?>
<?php

class EmailAddress
{
	function __construct( $email_address )
	{
		$this->email = $email_address;
	}
	
	/*
	 * Based on the following article:
	 * http://articles.sitepoint.com/article/users-email-address-php
	 */
	function isValid( $debug )
	{
		$ret = False;
	
		if ( $this->email )
		{
			$list = explode( '@', $this->email );
			
			if ( "2" == count( $list ) )
			{
				if ( "" != $list[0] )
				{
					if ( checkdnsrr( $list[1], "MX" ) )
					{
						$debug->println( "<!-- $this->email is valid -->" );
						$ret = True;
					} else {
						$debug->println( "<!-- $this->email is invalid -->" );
					}
				}
			}
		}
		
		return $ret;
	}
}

?>