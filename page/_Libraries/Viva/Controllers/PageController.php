<?php

class PageController extends Controller
{
	function __construct()
	{}
	
	/*
	 *  Returns boolean
	 */
	function perform( $session, $request, $debug )
	{
		$ret = False;

		$debug->println( "<!-- PageController::perform() start -->" );
		$debug->indent();
		{
			$msg = "<!-- performing: " . $request["action"] . " -->";
			$debug->println( $msg );
			
			switch ( $request["action"] )
			{
			case "save_page":
				$ret = $this->savePage( $session, $request, $debug );
				break;
			}
			
			if ( is_array( $ret ) ) $ret = True;
		}
		$debug->outdent();
		$debug->println( "<!-- Page::perform() end : $ret -->" );

		return $ret;
	}

	function first( $tuples )
	{
		return $tuples[0];
	}

	function savePage( $session, $request, $debug )
	{
		$sid                  = $request["sid"];

		$page_id              = $request["page_id"];
		$publish              = ( "on" == $request["publish"] ) ? "1" : "0";
		$page_template        = $request["page_template"];
		$page_name            = $request["page_name"];
		$page_heading         = $request["page_heading"];
		$page_title           = $request["page_title"];
		$page_url             = $request["page_url"];
		$page_keywords        = $request["page_keywords"];
		$page_description     = $request["page_description"];
		$page_content         = $request["page_content"];
		$menu_id              = $request["menu_id"];
		$position             = $request["position"];
		
		$sql = "Page_Save( '$page_id', '$publish', '$page_template', '$page_name', '$page_heading', '$page_title', '$page_url', '$page_keywords', '$page_description', '$page_content', '$menu_id', '$position' )";

		return DBi_callProcedure( DATABASE, $sql, $debug );
	}
}
?>