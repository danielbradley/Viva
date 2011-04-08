<?php

class SelectMenu extends View
{
	function __construct( $model, $request, $selected, $debug )
	{
		$this->model    = $model;
		$this->request  = $request;
		$this->selected = $selected;

		$rid = $model->getUid();

		$this->menus = $model->retrieveAllMenus( $debug );
		if ( ! $this->menus ) $this->menus = array();
	}

	function render( $out )
	{
		$options = array();
		$options[""] = "-- select menu --";

		foreach ( $this->menus as $tuple )
		{
			$MUID           = $tuple["MENU_ID"];
			$options[$MUID] = $tuple["menu_name"];
		}

		//$options["X"] = "Other (please specify)";
	
		$attributes = "class='narrow' id='menu_id' name='menu_id'";
		$select = new Select( $attributes, $this->selected, $options );
		$select->render( $out );
	}
}

?>