<?php
//	Copyright (c) 2009, 2010 Daniel Robert Bradley. All rights reserved.
//	This software is distributed under the terms of the GNU Lesser General Public License version 2.1
?>
<?php

class EditPageContent extends PageContent
{
	function __construct( $model, $request, $debug )
	{
		parent::__construct( $model, $request, $debug );
		
		$selected = $this->tuple["MENU_ID"];
		if ( ! $selected ) $selected = $request["menu_id"];
		$this->select_menu = new SelectMenu( $model, $request, $selected, $debug );
	}

	function getPage( $debug )
	{
		return $this->model->retrieveOnePage( $this->request["page"], $this->request["page_id"], $debug );
	}
	
	function render( $out )
	{
		$page_id          = $this->tuple["PAGE_ID"];
		$timestamp        = $this->tuple["timestamp"];
		$publish          = $this->tuple["publish"];
		$page_name        = $this->tuple["page_name"];
		$page_heading     = $this->tuple["page_heading"];
		$page_title       = $this->tuple["page_title"];
		$page_content     = $this->tuple["page_content"];
		$page_keywords    = $this->tuple["page_keywords"];
		$page_description = $this->tuple["page_description"];
		$position         = $this->tuple["position"];

		if ( "1" == $publish ) $checked = "checked";

		if ( $this->model->isAdministrator() )
		{
			if ( ! $page )    $page    = $this->request["page"];
			if ( ! $page_id ) $page_id = "0";
		
			$out->println( "<form class='std' method='post' action='./?page=$page'>" );
			$out->indent();
			{
				$out->println( "<div class='content' id='edit_page'>" );
				$out->indent();
				{
					$out->println( "<input type=hidden name='action'        value='save_page'>" );

					$out->println( "<label>Date Saved:</label>" );
					$out->println( "<input class='text' value='$timestamp' disabled='disabled'><br>" );

					$out->println( "<label>Published:</label>" );
					$out->println( "<input class='checkbox' type='checkbox' $checked disabled='disabled'><br>" );

					$out->println( "<label>Page Name:</label>" );
					$out->println( "<input class='text' name='page_name' value='$page'><br>" );

					$out->println( "<label>Page Heading:</label>" );
					$out->println( "<input class='text' name='page_heading' value='$page_heading'><br>" );

					$out->println( "<textarea id='page_content' style='width: 500px; height: 300px;' name='page_content'>$page_content</textarea>" );
					$out->println( "<fieldset><legend>SEO Fields</legend>" );
					$out->indent();
					{
						$out->println( "<label>Page Title:</label>" );
						$out->println( "<input class='text' name='page_title' value='$page_title'><br>" );

						$out->println( "<label>Keywords:</label>" );
						$out->println( "<input class='text' name='page_keywords' value='$page_keywords'><br>" );

						$out->println( "<label>Description:</label>" );
						$out->println( "<textarea style='width: 474px; height: 100px;' name='page_description'>$page_description</textarea>" );
					}
					$out->outdent();
					$out->println( "</fieldset>" );

					$out->println( "<fieldset><legend>Navigation</legend>" );
					$out->indent();
					{
						$out->println( "<label>Menu:</label>" );
						$this->select_menu->render( $out );
						$out->println( "<br>" );

						$out->println( "<label>Position in menu:</label>" );
						$out->println( "<input class='text' name='position' value='$position'>" );
					}
					$out->outdent();
					$out->println( "</fieldset>" );

					$out->println( "<input class='checkbox' type='checkbox' name='publish'>" );
					$out->println( "<label>Publish this page now!</label>" );
					$out->println( "<br>" );
					$out->println( "<br>" );
					$out->println( "<input type='submit' id='save_button' name='submit' value='Save'>" );
				}
				$out->outdent();
				$out->println( "</div>" );
			}
			$out->outdent();
			$out->println( "</form>" );
		} else {
			$out->println( "<div class='content'>$page_content</div>" );
		}
	}
}


?>
