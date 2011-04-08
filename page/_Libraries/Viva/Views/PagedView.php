<?php
//	Copyright (c) 2009, 2010 Oysta IP Pty Ltd. All rights reserved.
//	This software is proprietary software, do not distribute.
?>
<?php

include_once( VIVA_INC . "/_Libraries/Viva/HTML/Pages.php" );
include_once( VIVA_INC . "/_Libraries/Viva/Objects/URLParameters.php" );

class PagedView extends View
{
	function __construct( $limit, $offset )
	{
		$this->pages = new Pages( new URLParameters( array(), array() ), 0 );
	
		$this->items = array();
		$this->total = "X";
	}

	function setPages( $pages )
	{
		$this->pages = $pages;
		$this->total = $pages->getTotal();
	}

	function addView( $view )
	{
		$this->items[] = $view;
	}
	
	function render( $out )
	{
		if ( ! empty( $this->items ) )
		{
			$this->renderForm( $out );
		} else {
			$this->renderNoItems( $out );
		}
	}

		function renderForm( $out )
		{
			$out->inprint( "<div class='PagedView pad'>" );
			{
				$out->inprint( "<div class='shadow box pad'>" );
				{
					$out->inprint( "<div class='heading1'>" );
					{
						$this->renderSummary( $out );
						$this->renderSortby( $out );
					}
					$out->outprint( "</div>" );
					
					$this->pages->render( $out );
					$this->renderStart( $out );
					foreach ( $this->items as $item ) $item->render( $out );
					$this->renderEnd( $out );
					$this->pages->render( $out );
				}
				$out->outprint( "</div>" );
			}
			$out->outprint( "</div>" );
		}

			function renderSummary( $out )
			{
				$first = $this->pages->getFirst();
				$last  = $this->pages->getLast();
				$total = $this->pages->getTotal();

				$out->println( "<span>Displaying $first to $last of $total matches</span>" );
			}

			function renderSortby( $out )
			{
			}

			function renderAction( $out )
			{
			}

			function renderStart( $out )
			{
			}

			function renderEnd( $out )
			{
			}

		function renderNoItems( $out )
		{
			$out->println( "<p>No items matched</p>" );
		}
	
	function size()
	{
		return count( $this->items );
	}
}

?>