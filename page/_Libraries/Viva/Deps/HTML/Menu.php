<?php
//	Copyright (c) 2009, 2010 Daniel Robert Bradley. All rights reserved.
//	This software is distributed under the terms of the GNU Lesser General Public License version 2.1
?>
<?php

class Menu extends View
{
	function __construct( $model, $request, $class, $menu_name, $debug )
	{
		$this->model   = $model;
		$this->request = $request;
		$this->class   = $class;
		$this->menu_name = $menu_name;

		$tuple = $this->model->retrieveMenuID( $menu_name, $debug );
		$this->menu_id = $tuple["MENU_ID"];
		$this->tuples = $this->model->retrieveMenus( $menu_name, $debug );
		//if ( ! $this->tuples ) $this->tuples = array();
	}
	
	function render( $out )
	{
		$out->println( "<div class='$this->class' id='$this->menu_name'>" );
		$out->indent();
		{
			$out->println( "<ul class='$this->class' id='$this->menu_name'>" );
		
			foreach ( $this->tuples as $tuple )
			{
				$name        = $tuple["page_name"];
				$heading     = $tuple["page_heading"];
				$url         = $this->generateURL( $tuple["page_url"], $name );
				$request_uri = $_SERVER["REQUEST_URI"];

				if ( ! isset( $tag ) )
				{
					$tag = "li class='first'";
				} else {
					$tag = "li";
				}

				$show = true;
				if ( "" != $this->model->getIDType() )
				{
					switch ( $heading )
					{
					case "Join Today":
					case "Subscribe Today":
					case "Enquire Today":
						$show = false;
					}
				}

				if ( $show )
				{
					if ( "$url" == $request_uri )
					{
						$out->println( "<$tag>$heading</li>" );
					} else {
						$out->println( "<$tag><a class='enabled' href='$url'>$heading</a></li>" );
						//$out->println( "<$tag><a class='enabled' id='$this->menu_name-$name' href='$url'>$heading</a></li>" );
					}
				}
			}
			if ( $this->model->isAdministrator() )
			{
				$url = PAGES . "/content/edit/?page=&menu_id=$this->menu_id";
				$out->printf( "<li><a class='enabled'  id='' href='$url'>+</a></li>" );
			}

			$out->println( "</ul>" );
		}
		$out->outdent();
		$out->println( "</div>" );
	}

	function generateURL( $page_url, $name )
	{
		if ( $page_url )
		{
				$url = PAGES . $page_url;
		}
		else
		{
			if ( NICE_URLS )
			{
				$name = str_replace( "-", "/", $name );
				$url = PAGES . "/$name/";
			} else {
				$url = PAGES . "/content/?page=$name";
			}
		}
		return $url;
	}
}


?>
