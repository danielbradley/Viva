<?php
//	Copyright (c) 2009, 2010 Daniel Robert Bradley. All rights reserved.
//	This software is distributed under the terms of the GNU Lesser General Public License version 2.1
?>
<?php

class PageVersions extends View
{
	function __construct( $model, $request, $debug )
	{
		$this->model   = $model;
		$this->request = $request;
		
		$page = $this->request["page"];
		$this->tuples = $this->model->retrievePageTimestamps( $page, $debug );
	}

	function render( $out )
	{
		$out->println( "<ul style='list-style-type: none;'>" );
		$out->indent();
		{
			$page = $this->request["page"];
		
			foreach ( $this->tuples as $tuple )
			{
				$page_id   = $tuple["PAGE_ID"];
				$timestamp = $tuple["timestamp"];
				if ( "1" == $tuple["publish"] )
				{
					$out->println( "<li><a style='color:green' href='./?page=$page&page_id=$page_id'>$timestamp</a></li>" );
				} else {
					$out->println( "<li><a style='color:orange' href='./?page=$page&page_id=$page_id'>$timestamp</a></li>" );
				}
			}
		}
		$out->outdent();
		$out->println( "</ul>" );
	}
}


?>
