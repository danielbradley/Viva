<?php
//	Copyright (c) 2010 Daniel Robert Bradley. All rights reserved.
//	This software is distributed under the terms of the GNU Lesser General Public License version 2.1
?>
<?php

//include_once( VIVA_INC . "/_Libraries/Viva/Objects/URLParameters.php" );

class Pages
{
	public $labels;

	function __construct( $url_parameters, $total )
	{
		$this->total   = $total;
		
		$this->limit   = $url_parameters->get( "limit" );
		$this->pageNr  = $url_parameters->get( "page" );

		if ( "" == $this->pageNr )
		{
			$this->pageNr = 1;
		}
		
		if ( "" == $this->limit )
		{
			$this->limit = 10;
		}

		$this->nrPages = ceil( $total / $this->limit );

		$this->urlParameters = $url_parameters;
	}
	
	function getFirst()
	{
		return (($this->pageNr - 1) * $this->limit) + 1;
	}

	function getLast()
	{
		return min( $this->total, $this->pageNr * $this->limit );
	}
	
	function getTotal()
	{
		return $this->total;
	}
	
	function render( $out )
	{
		if ( 0 < $this->nrPages )
		{
			$out->println( "<div class='Pages pages_bg pad'>" );
			$out->indent();
			{
				if ( 1 < $this->pageNr )
				{
					$prev = $this->pageNr - 1;
					$this->urlParameters->set( "page", $prev );
					$params = $this->urlParameters->encode();
					$out->println( "<div style='float:left'><span><a href='./?$params'><span>Prev</span></a></div>" );
				} else {
					$out->println( "<div style='float:left;'><span>Prev</span></div>" );
				}
				
				if ( $this->pageNr < $this->nrPages )
				{
					$next = $this->pageNr + 1;
					$this->urlParameters->set( "page", $next );
					$params = $this->urlParameters->encode();
					$out->println( "<div style='float:right'><a href='./?$params'><span>Next</span></a></div>" );
				} else {
					$out->println( "<div style='float:right;'><span>Next</span></div>" );
				}
				
				$out->inprint( "<div class='page_numbers'>" );
				{
					for ( $i=1; $i <= $this->nrPages; $i++ )
					{
						if ( $i == $this->pageNr )
						{
							$out->println( "<span>$i</span>" );
						} else {
							$this->urlParameters->set( "page", $i );
							$params = $this->urlParameters->encode();
						
							$out->println( "<a href='./?$params'><span>$i</span></a>" );
						}
					}
				}
				$out->outprint( "</div>" );
			}
			$out->outdent();
			$out->println( "</div>" );
		}
	}
}

?>