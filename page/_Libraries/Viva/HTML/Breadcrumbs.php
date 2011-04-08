<?php
//	Copyright (c) 2009, 2010 Daniel Robert Bradley. All rights reserved.
//	This software is distributed under the terms of the GNU Lesser General Public License version 2.1
?>
<?php

class Breadcrumbs
{
	public $labels;

	function __construct( $labels )
	{
		$page = PAGE;
	
		$this->labels = $labels;
		$this->pages  = array();
		$this->urls   = array();
		
		foreach( $this->labels as $label )
		{
			$this->pages[] = str_replace( " ", "_", strtolower( $label ) );
		}

		$max = count( $this->pages );
		for ( $i=0; $i < $max; $i++ )
		{
			if ( $i < ($max - 1) )
			{
				$url = $page . "/";
				for ( $j=0; $j <= $i; $j++ )
				{
					if ( 0 < $j )
					{
						$url .= $this->pages[$j] . "/";
					} else if ( "dashboard" == $this->pages[$j] ) {
						$url .= $this->pages[$j] . "/";
					} else {
						//$url .= $this->pages[$j] . "/";
					}
				}
				$this->urls[$i] = $url;
			}
		}
	}
	
	function setLabel( $index, $label )
	{
		$i   = $index - 1;
		$max = count( $this->labels );

		$this->labels[$max+$i] = $label;
	}

	function setURL( $index, $url )
	{
		$i   = $index - 1;
		$max = count( $this->labels );

		$this->urls[$max+$i] = $url;
	}

	function getURL( $index )
	{
		$i   = $index - 1;
		$max = count( $this->labels );

		return $this->urls[$max+$i];
	}
	
	function getLastLabel()
	{
		$max = count( $this->labels );
		return $this->labels[$max-1];
	}

	function getLastURL()
	{
		$max = count( $this->labels );
		return $this->urls[$max-1];
	}

	function getPreviousURL()
	{
		$max = count( $this->labels );
		return $this->urls[$max-2];
	}
	
	function render( $out )
	{
//		$out->println( "<div class='breadcrumbs'>" );
//		$out->indent();
//		{
			$out->println( "<ol>" );
			$out->indent();
			{
				$max = count( $this->pages );
				
				for ( $i=0; $i < $max; $i++ )
				{
					$label = $this->labels[$i];

					if ( $i < ($max - 1) )
					{
						$url = $this->urls[$i];
						$out->println( "<li><a href='$url'>" . $label . "</a></li>" );
						$out->println( "<li class='arrow'>&gt;</li>" );
					} else {
						$out->println( "<li>" . $label . "</li>" );
					}
				}
			}
			$out->outdent();
			$out->println( "</ol>" );
//		}
//		$out->outdent();
//		$out->println( "</div>" );
	}
}

?>