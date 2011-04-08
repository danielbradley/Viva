<?php
//	Copyright (c) 2010 Daniel Robert Bradley. All rights reserved.
//	This software is distributed under the terms of the GNU Lesser General Public License version 2.1
?>
<?php

class LoginBox extends Element
{
	function __construct( $id )
	{
		$this->id = $id;
	}

	function render( $out )
	{
		$out->println( "<div class='Viva-HTML-LoginBox' id='$this->id'>" );
		$out->indent();
		{
			$url = PAGE . "/account/login/";
		
			$this->styles();
			$out->println( "<form method='post' action='$url'>" );
			$out->indent();
			{
				$out->println( "<table>" );
				$out->println( "<tr>" );
				$out->println( "<td><small>Email</small></td>" );
				$out->println( "<td><small>Password</small></td>" );
				$out->println( "<td></td>" );
				$out->println( "</tr>" );
				$out->println( "<tr>" );
				$out->println( "<td><input class='text'  name='username' value=''></td>" );
				$out->println( "<td><input class='text'  name='password' value='' type='password'></td>" );
				$out->println( "<td><input type='submit' name='submit'   value='Login' style='margin:0px; padding:0px; height:100%;'></td>" );
				$out->println( "</tr>" );
				$out->println( "<tr>" );
				$out->println( "<td></td>" );
				$out->println( "<td><small><a href='/account/reset_password'>Forgot your password</a></small></td>" );
				$out->println( "<td></td>" );
				$out->println( "</tr>" );
				
				
				$out->println( "</table>" );
			}
			$out->outdent();
			$out->println( "</form>" );
		}
		$out->outdent();
		$out->println( "</div>" );
	}

		function styles()
		{
?>
				<style>
				DIV.Viva-HTML-LoginBox TABLE TD   { margin: 0px; padding: 0px; }
				DIV.Viva-HTML-LoginBox INPUT      { margin: 0px; padding: 3px; height: 13px; font-size: 11px; }
				DIV.Viva-HTML-LoginBox INPUT.text { width: 120px; }
				</style>

<?php		
		
		}


}

?>