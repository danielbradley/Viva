<?php
//	Copyright (c) 2010 Daniel Robert Bradley. All rights reserved.
//	This software is distributed under the terms of the GNU Lesser General Public License version 2.1
?>
<?php

class VivaModel extends Model
{
	private $session;
	private $credentials;

	function __construct( $session, $debug )
	{
		$this->session = $session;
		$sid = $session->sessionid;

		$this->credentials = array();
		$this->credentials = $this->first( DBi_callProcedure( DATABASE, "Authenticate( '$sid' )", $debug ) );
		
//		foreach ( $this->credentials as $key => $value )
//		{
//			$debug->println( "<!-- " . $key . " = " . $value . " -->" );
//		}
	}

	function logout()
	{
		$this->session = null;
		$this->credentials = array();
	}

	function ping( $debug )
	{
		$ret = DBi_callFunction( DATABASE, "Ping()", $debug );
		
		$debug->println( "<!-- $ret -->" );
		if ( "1" == $ret )
		{
			return true;
		} else {
			return false;
		}
	}

	function checkPassword( $uid, $password, $debug )
	{
		return DBi_callFunction( DATABASE, "Check_Password( '$uid', '$password' )" , $debug );
	}
	
	function getCredentials()
	{
		return $this->credentials;
	}

	function isAdministrator()
	{
		return ("AID" == $this->getIDType());
	}

	function isAuthenticated()
	{
		return ("" != $this->getIDType());
	}
	
	function isExistingUser( $email_address, $debug )
	{
		return DBi_callFunction( DATABASE, "User_Exists( '$email_address' )" , $debug );
	}
	
	function first( $tuples )
	{
		if ( isset( $tuples ) && is_array( $tuples ) )
		{
			return array_key_exists( 0, $tuples ) ? $tuples[0] : null;
		} else {
			return null;
		}
	}

	function getSid()
	{
		return $this->session->sessionid;
	}

	function getUid()
	{
		$key = "uid";
		return array_key_exists( $key, $this->credentials ) ? $this->credentials[$key] : null;
	}
	
	function getIDType()
	{
		$key = "idtype";
		if ( isset( $this->credentials ) && is_array( $this->credentials ) )
		{
			return array_key_exists( $key, $this->credentials ) ? $this->credentials[$key] : null;
		} else {
			return null;
		}
	}

	function getUsername()
	{
		$key = "username";
		return ( isset( $this->credentials ) && array_key_exists( $key, $this->credentials )) ? $this->credentials[$key] : null;
	}

	function retrieveAllMenus( $debug )
	{
		return DBi_callProcedure( DATABASE, "Menus_Retrieve_All()" , $debug );
	}

	function retrieveMenuID( $menu_name, $debug )
	{
		return $this->first( DBi_callProcedure( DATABASE, "Menus_Retrieve_ID( '$menu_name' )" , $debug ) );
	}

	function retrieveMenus( $menu_name, $debug )
	{
		return DBi_callProcedure( DATABASE, "Menus_Retrieve( '$menu_name' )" , $debug );
	}

	function retrievePublicMenus( $menu_name, $debug )
	{
		return DBi_callProcedure( DATABASE, "Menus_Retrieve( '$menu_name' )" , $debug );
	}

	function retrievePageContent( $page_name, $debug )
	{
		return $this->first( DBi_callProcedure( DATABASE, "Pages_Retrieve( '$page_name' )" , $debug ) );
	}

	function retrieveOnePage( $page_name, $page_id, $debug )
	{
		return $this->first( DBi_callProcedure( DATABASE, "Pages_Retrieve_One( '$page_name', '$page_id' )" , $debug ) );
	}

	function retrievePageTimestamps( $page_name, $debug )
	{
		return DBi_callProcedure( DATABASE, "Pages_Retrieve_Timestamps( '$page_name' )" , $debug );
	}
}

?>