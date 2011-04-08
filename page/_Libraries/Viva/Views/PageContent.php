<?php
//	Copyright (c) 2009, 2010 Daniel Robert Bradley. All rights reserved.
//	This software is distributed under the terms of the GNU Lesser General Public License version 2.1
?>
<?php

class PageContent extends View
{
	function __construct( $model, $request, $debug )
	{
		$this->model   = $model;
		$this->request = $request;
		
		$this->tuple = $this->getPage( $debug );
	}

	function getPage( $debug )
	{
		return $this->model->retrievePageContent( $this->request["page"], $debug );
	}

	function getPageTemplate()
	{
		return $this->tuple["page_template"];
	}

	function getPageName()
	{
		return $this->tuple["page_name"];
	}

	function getPageHeading()
	{
		return $this->tuple["page_heading"];
	}

	function getPageTitle()
	{
		return $this->tuple["page_title"];
	}

	function getPageKeywords()
	{
		return $this->tuple["page_keywords"];
	}

	function getPageDescription()
	{
		return $this->tuple["page_description"];
	}
	
	function render( $out )
	{
		$page_name    = $this->tuple["page_name"];
		$page_content = $this->tuple["page_content"];

		if ( "AID" == $this->model->getIDType() )
		{
			$out->println( "<div class='page_edit' style='position: absolute; top: -28px; left: -40px;' ><a style='font-size: 14px; color: blue;'href='" . PAGES . "/content/edit/?page=$page_name'>Edit</a></div>" );
		}
		$out->println( "<div class='content'>$page_content</div>" );
	}
}


?>
