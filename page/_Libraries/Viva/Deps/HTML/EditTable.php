<?php
//	Copyright (c) 2009, 2010 Daniel Robert Bradley. All rights reserved.
//	This software is distributed under the terms of the GNU Lesser General Public License version 2.1
?>
<?php

class EditTableItem extends Element
{
	function __construct( $element, $edit_url, $edit_parameters, $edit_link )
	{
		$this->element         = $element;
		$this->edit_url        = $edit_url;
		$this->edit_parameters = $edit_parameters;
		$this->hasEditLink     = $edit_link;

		$this->editLabel       = "Edit";
	}

	function setEditLabel( $text )
	{
		$this->editLabel = $text;
	}

	function render( $out )
	{
//		$out->println( "<tr>" );
//		$out->indent();
//		{
//			$out->println( "<td class='edit_table_item' colspan='3'>" );
//			$out->indent();
//			{
				$this->element->render( $out );
//			}
//			$out->outdent();
//			$out->println( "</td>" );
//		}
//		$out->outdent();
//		$out->println( "</tr>" );
	}

	function renderEdit( $out )
	{
		if ( ! $this->hasEditLink )
		{
			$this->renderEditA( $out );
		} else {
			$this->renderEditB( $out );
		}
	}

	function renderEditA( $out )
	{
//		$out->println( "<tr>" );
//		$out->indent();
//		{
//			$out->println( "<td class='edit_table_item' colspan='3'>" );
//			$out->indent();
//			{
				$this->element->renderEdit( $out );
//			}
//			$out->outdent();
//			$out->println( "</td>" );
//		}
//		$out->outdent();
//		$out->println( "</tr>" );
	}

	function renderEditB( $out )
	{
		$out->println( "<div>" );
		$out->indent();
		{
			$out->println( "<div class='edit_table_body_edit'>" );
			$out->indent();
			{
				$url    = $this->edit_url;
				$params = $this->edit_parameters;
				$out->println( "<div xclass='centered'><a href='$url?$params'>$this->editLabel</a></div>" );
			}
			$out->outdent();
			$out->println( "</div>" );

			$out->println( "<div class='edit_table_body_item'>" );
			$out->indent();
			{
				$this->element->render( $out );
			}
			$out->outdent();
			$out->println( "</div>" );
		}
		$out->outdent();
		$out->println( "</div>" );
	}

	function renderEditBX( $out )
	{
		$out->println( "<tr>" );
		$out->indent();
		{
			if ( $this->hasEditLink )
			{
				$out->println( "<td class='edit_table_item' colspan='2'>" );
				$out->indent();
				{
					$this->element->render( $out );
				}
				$out->outdent();
				$out->println( "</td>" );
				$out->println( "<td class='edit_table_edit'>" );
				$out->indent();
				{
					$url    = $this->edit_url;
					$params = $this->edit_parameters;
					$out->println( "<a href='$url?$params'>Edit</a>" );
				}
				$out->outdent();
				$out->println( "</td>" );
			} else {
				$out->println( "<td class='edit_table_item' colspan='3'>" );
				$out->indent();
				{
					$this->element->renderEdit( $out );
				}
				$out->outdent();
				$out->println( "</td>" );
			}
		}
		$out->outdent();
		$out->println( "</tr>" );
	}
}

class EditTable extends Element
{
	function __construct( $id, $title, $items, $editable, $plus_url )
	{
		$this->id         = $id;
		$this->title      = $title;
		$this->items      = $items;
		$this->isEditable = $editable;
		$this->plusURL    = $plus_url;

		$this->plusLabel       = "Add";
		
		if ( ! $items ) $this->items = array();

		$this->enableAspect( "" );
	}

	function enableAspect( $view )
	{
		if ( $this->id == $view )
		{
			$this->view = "style='display:none'";
			$this->edit = "";
		} else {
			$this->view = "";
			$this->edit = "style='display:none'";
		}
	}

	function setPlusLabel( $text )
	{
		$this->plusLabel = $text;
	}
	
	function setEditURL( $url )
	{
		$this->editURL = $url;
	}
	
	function render( $out )
	{
		$out->println( "<!-- $this->plusURL -->" );
		$out->println( "<div class='edit_table box' id='$this->id'>" );
		$out->indent();
		{
			$out->println( "<div class='heading1'><span>$this->title</span></div>" );
		
		
			$this->renderAsDivs( $out );
		}
		$out->outdent();
		$out->println( "</div>" );
	}
	
	function renderAsDivs( $out )
	{
		$out->println( "<div class='view_edit_table' id='view_$this->id' $this->view>" );
		$out->indent();
		{
			$out->println( "<div class='edit_table_header'>" );
			$out->indent();
			{
				if ( $this->isEditable )
				{
					$out->println( "<div class='edit_table_edit'>" );
					$out->indent();
					{
						if ( isset( $this->editURL ) )
						{
							$out->println( "<a href='$this->editURL'>Edit</a>" );
						} else {
							if ( False === strpos( $_SERVER["REQUEST_URI"], "?" ) )
							{
								$url = $_SERVER["REQUEST_URI"] . "?view=" . $this->id;
							} else {
								$url = $_SERVER["REQUEST_URI"] . "&view=" . $this->id;
							}
						
							$out->println( "<a href='$url' onclick='return viewedit( \"$this->id\" );'>Edit</a>" );
						}
					}
					$out->outdent();
					$out->println( "</div>" );
				}
				$out->println( "<div class='edit_table_plus'>" );
				$out->indent();
				{
					if ( $this->plusURL )
					{
						$out->println( "<a href='$this->plusURL'>+</a>" );
					}
				}
				$out->outdent();
				$out->println( "</div>" );
//				$out->println( "<div class='edit_table_title'>" );
//				$out->indent();
//				{
//					$out->println( "<span class='form_title'>$this->title</span>" );
//				}
//				$out->outdent();
//				$out->println( "</div>" );
			}
			$out->outdent();
			$out->println( "</div>" );
			$out->println( "<div class='edit_table_body'>" );
			$out->indent();
			{
				foreach ( $this->items as $edit_table_item )
				{
					$edit_table_item->render( $out );
				}
			}
			$out->outdent();
			$out->println( "</div>" );
		}
		$out->outdent();
		$out->println( "</div>" );

		$out->println( "<div class='edit_edit_table' id='edit_$this->id' $this->edit>" );
		$out->indent();
		{
			$out->println( "<div class='edit_table_header'>" );
			$out->indent();
			{
				if ( $this->isEditable )
				{
					$out->println( "<div class='edit_table_edit'>" );
					$out->indent();
					{
						if ( isset( $this->editURL ) )
						{
							$out->println( "<a href='$this->editURL'>Edit</a>" );
						} else {
							$out->println( "<a href='./' onclick='return viewedit( \"$this->id\" );'>Cancel</a>" );
						}
					}
					$out->outdent();
					$out->println( "</div>" );
				}
				$out->println( "<div class='edit_table_plus'>" );
				$out->indent();
				{
					if ( $this->plusURL )
					{
						$out->println( "<a href='$this->plusURL'>+</a>" );
					}
				}
				$out->outdent();
				$out->println( "</div>" );
//				$out->println( "<div class='edit_table_title'>" );
//				$out->indent();
//				{
//					$out->println( "<span class='form_title'>$this->title</span>" );
//				}
//				$out->outdent();
//				$out->println( "</div>" );
			}
			$out->outdent();
			$out->println( "</div>" );
			$out->println( "<div class='edit_table_body'>" );
			$out->indent();
			{
				foreach ( $this->items as $edit_table_item )
				{
					$edit_table_item->renderEdit( $out );
				}
			}
			$out->outdent();
			$out->println( "</div>" );
		}
		$out->outdent();
		$out->println( "</div>" );
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	function renderAsTable( $out )
	{
		$out->println( "<div id='view_$this->id'>" );
		$out->indent();
		{
			$out->println( "<table class='edit_table'>" );
			$out->indent();
			{
				$out->println( "<thead class='edit_table'>" );
				$out->indent();
				{
					$out->println( "<tr>" );
					$out->indent();
					{
						if ( "" != $this->plusURL )
						{
							$out->println( "<th class='edit_table_plus' style='text-align: center;'>" );
							$out->indent();
							{
								$out->println( "<!-- a href='$this->plusURL'>+</a -->" );
							}
							$out->outdent();
							$out->println( "</th>" );
						} else {
							$out->println( "<th class='edit_table_plus' style='text-align: center;'></th>" );
						}
						if ( $this->isEditable )
						{
							$out->println( "<th class='edit_table_title'><span class='form_title'>$this->title</span></th>" );
							$out->println( "<th class='edit_table_edit' style='text-align: center;'>" );
							$out->indent();
							{
								if ( $this->editURL )
								{
									$out->println( "<a href='$this->editURL'>Edit</a>" );
								} else {
									$out->println( "<a href='#' onclick='return viewedit( \"$this->id\" );'>Edit</a>" );
								}
							}
							$out->outdent();
							$out->println( "</th>" );
						} else {
							$out->println( "<th class='edit_table_title' colspan='2'>$this->title</th>" );
						}
					}
					$out->outdent();
					$out->println( "</tr>" );
				}
				$out->outdent();
				$out->println( "</thead>" );
				
				if ( count( $this->items ) )
				{
					$out->println( "<tbody class='edit_table'>" );
					$out->indent();
					{
						foreach ( $this->items as $edit_table_item )
						{
							$edit_table_item->render( $out );
						}
					}
					$out->outdent();
					$out->println( "</tbody>" );
				}
			}
			$out->outdent();
			$out->println( "</table>" );
		}
		$out->outdent();
		$out->println( "</div>" );
		$out->println( "<div id='edit_$this->id' style='display:none;'>" );
		$out->indent();
		{
			$out->println( "<table class='edit_table'>" );
			$out->indent();
			{
				$out->println( "<thead class='edit_table'>" );
				$out->indent();
				{
					$out->println( "<tr>" );
					$out->indent();
					{
						if ( "" != $this->plusURL )
						{
							$out->println( "<th class='edit_table_plus' style='text-align: center;'>" );
							$out->indent();
							{
								$out->println( "<a href='$this->plusURL'>+</a>" );
							}
							$out->outdent();
							$out->println( "</th>" );
						} else {
							$out->println( "<th class='edit_table_plus' style='text-align: center;'></th>" );
						}
						$out->println( "<th class='edit_table_title'><span class='form_title'>$this->title</span></th>" );
						if ( $this->isEditable )
						{
							$out->println( "<th class='edit_table_edit' style='text-align:center;'>" );
							$out->indent();
							{
								$out->println( "<a href='#' onclick='return viewedit( \"$this->id\" );'>Done</a>" );
							}
							$out->outdent();
							$out->println( "</th>" );
						}
					}
					$out->outdent();
					$out->println( "</tr>" );
				}
				$out->outdent();
				$out->println( "</thead>" );
				$out->println( "<tbody class='edit_table'>" );
				$out->indent();
				{
					if ( $this->newItem ) $this->newItem->renderEdit( $out );
					foreach ( $this->items as $edit_table_item )
					{
						$edit_table_item->renderEdit( $out );
					}
				}
				$out->outdent();
				$out->println( "</tbody>" );
			}
			$out->outdent();
			$out->println( "</table>" );
		}
		$out->outdent();
		$out->println( "</div>" );
	}
	
	function renderAsDiv( $out )
	{
	
	}
}


?>
