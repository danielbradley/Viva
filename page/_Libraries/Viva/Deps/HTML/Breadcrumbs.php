<?php
//	Copyright (c) 2009, 2010 Daniel Robert Bradley. All rights reserved.
//	This software is distributed under the terms of the GNU Lesser General Public License version 2.1
?>
<?php

class Breadcrumbs extends Element
{
	function __construct( $page_id )
	{
		$this->pageId = $page_id;
	}
	
	function render( $out )
	{
		$out->println( "<div class='breadcrumbs'>" );
		$out->indent();
		{
			$out->println( "<ol>" );
			$out->indent();
			{
				$bits = explode( "-", $this->pageId );
				$max = count( $bits );
				
				for ( $i=0; $i < $max; $i++ )
				{
					//$bit = toUppercase( $bit );
					$label = str_replace( "_", " ", $bits["$i"] );

					if ( $i < ($max - 1) )
					{
						$url = PAGE . "/";
						for ( $j=0; $j <= $i; $j++ )
						{
							if ( 0 < $j ) $url .= strtolower( $bits[$j] ) . "/";
						}
					
						$out->println( "<li><a href='$url'>" . $label . "</a></li>" );
						$out->println( "<li>&gt;</li>" );
					} else {
						$out->println( "<li>" . $label . "</li>" );
					}
				}
			}
			$out->outdent();
			$out->println( "</ol>" );
		}
		$out->outdent();
		$out->println( "</div>" );
	}
}

?>