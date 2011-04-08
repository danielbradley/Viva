<?php
//	Copyright (c) 2010 Daniel Robert Bradley. All rights reserved.
//	This software is distributed under the terms of the GNU Lesser General Public License version 2.1
?>
<?php

class URLParameters
{
	function __construct( $parameters, $request )
	{
		$parameters = array_flip( $parameters );
		$this->params = array();
		$this->all    = array();
		
		foreach ( $request as $key => $value )
		{
			if ( array_key_exists( "$key", $parameters ) )
			{
				$this->params["$key"] = $value;
			}
			if ( "sid" != "$key" )
			{
				$this->all["$key"] = $value;
			}
		}
	}
	
	function get( $key )
	{
		return array_get( $key, $this->params );
	}
	
	function set( $key, $value )
	{
		$this->params[$key] = $value;
	}
	
	function remove( $key )
	{
		unset( $this->params[$key] );
	}
	
	function encode()
	{
		$str = "";
		foreach ( $this->params as $key => $value )
		{
			$str .= "&" . $key . "=" . $value;
		}
		return substr( $str, 1 );
	}

	function encodeAll()
	{
		$str = "";
		foreach ( $this->all as $key => $value )
		{
			$str .= "&" . $key . "=" . $value;
		}
		return substr( $str, 1 );
	}

	function encodeWithout( $sans )
	{
		$sans = array_flip( $sans );
	
		$str = "";
		foreach ( $this->params as $key => $value )
		{
			if ( ! array_key_exists( $key, $sans ) )
			{
				$str .= "&" . $key . "=" . $value;
			}
		}
		return substr( $str, 1 );
	}
	
	
	
}

?>