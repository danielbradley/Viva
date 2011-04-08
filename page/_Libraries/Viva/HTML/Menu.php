<?php
//	Copyright (c) 2010 Daniel Robert Bradley. All rights reserved.
//	This software is distributed under the terms of the GNU Lesser General Public License version 2.1
?>
<?php

class Menu extends Element
{
	function __construct( $id, $items )
	{
		$this->id    = $id;
		$this->items = $items;
	}
	
	function render( $out )
	{
		$out->println( "<div id='$this->id' class='Menu'>" );
		$out->indent();
		{
			$out->println( "<ul>" );
			$out->indent();
			{
				$first = "class='first'";
			
				foreach ( $this->items as $id => $page )
				{
					$script_filename = $_SERVER["SCRIPT_NAME"];
					$filename        = $page . "index.php";
				
					//$out->println( "<!-- F:  $filename -->" );
					//$out->println( "<!-- SF: $script_filename -->" );
				
					if ( $filename == $script_filename )
					{
						$out->println( "<li $first>$id</li>" );
					}
					else
					{
						$out->println( "<li $first><a href='$page'>$id</a></li>" );
					}
					$first = "";
				}
			}
			$out->outdent();
			$out->println( "</ul>" );
		}
		$out->outdent();
		$out->println( "</div>" );
	}



}

?>