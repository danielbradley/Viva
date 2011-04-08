<?php
//	Copyright (c) 2009, 2010 Daniel Robert Bradley. All rights reserved.
//	This software is distributed under the terms of the GNU Lesser General Public License version 2.1
?>
<?php

class InstallController extends Controller
{
	function __construct()
	{}
	
	function perform( $session, $request, $debug )
	{
		$ret = null;
	
		$debug->println( "<!-- InstallController::perform() start -->" );
		$debug->indent();
		{
			if ( array_key_exists( "action", $request ) )
			{
				$msg = "<!-- performing: " . $request["action"] . " -->";
				$debug->println( $msg );
				
				switch ( $request["action"] )
				{
				case "initialise_db":
					$ret = $this->initialiseDB( $request, $debug );
					break;
				}
			}
		}
		$debug->outdent();
		$debug->println( "<!-- InstallController::perform() end -->" );

		return $ret;
	}

	function initialiseDB( $request, $debug )
	{
		$status = False;

		$debug->println( "<!-- InstallController::initialiseDB() start -->" );
		$debug->indent();

		switch ( $request["submit"] )
		{
		case "Initialise DB":
			$debug->println( "<!-- doing -->" );
			$status = $this->installTables( $request, $debug );
			break;
		}

		$debug->outdent();
		$debug->println( "<!-- InstallController::initialiseDB() end -->" );

		return $status;
	}

	function endsWith( $str, $sub )
	{
		return ( substr( $str, strlen( $str ) - strlen( $sub ) ) == $sub );
	}

	function installTables( $request, $debug )
	{
		$status   = array();
		$status[] = $this->perform_install_action( "CREATE DATABASE", $this->createDatabase( $request, $debug ) );
		$status[] = $this->perform_install_action( "GRANT EXECUTE TO PUBLIC", $this->grantExecuteToPublic( $request, $debug ) );

		$status = $this->installTablesFor( $status, "o", OPENPAGE_INC, $request, $debug );
		$status = $this->installTablesFor( $status, "v", VIVA_INC,     $request, $debug );
		$status = $this->installTablesFor( $status, "w", WEBAPP_INC,   $request, $debug );

		return $status;
	}

	function installTablesFor( $status, $namespace, $base_dir, $request, $debug )
	{
		$i = 1;
		while ( true )
		{
			$key = $namespace . $i;
			$debug->println( "<!-- $key -->" );
			if ( array_key_exists( $key, $request ) )
			{
				$dir = $base_dir . "/" . $request[$key];
				$status[] = $this->perform_install_action( $dir, $this->installTablesIn( $request, $debug, $dir ) );
			} else {
				break;
			}
			$i++;
		}
		
		return $status;
	}
	
//	function installTables( $request, $debug )
//	{
//		$open_dir = OPENPAGE_INC . "/_SQL";
//		$viva1    = VIVA_INC     . "/_SQL/01Tables";
//		$viva2    = VIVA_INC     . "/_SQL/02Views";
//		$viva4    = VIVA_INC     . "/_SQL/04StoredProcedures";
//		$dir1     = WEBAPP_INC   . "/_SQL/01Tables";
//		$dir2     = WEBAPP_INC   . "/_SQL/02Views";
//		$dir3     = WEBAPP_INC   . "/_SQL/03Data";
//		$dir4     = WEBAPP_INC   . "/_SQL/04StoredProcedures";
//		$dir5     = WEBAPP_INC   . "/_SQL/05Grants";
//
//		$status = array();
//
//		$status[] = $this->perform_install_action( "CREATE DATABASE", $this->createDatabase( $request, $debug ) );
//		$status[] = $this->perform_install_action( "GRANT EXECUTE TO PUBLIC", $this->grantExecuteToPublic( $request, $debug ) );
//
//		$status[] = $this->perform_install_action( $open_dir, $this->installTablesIn( $request, $debug, $open_dir ) );
//
//		$status[] = $this->perform_install_action( $viva1, $this->installTablesIn( $request, $debug, $viva1 ) );
//		$status[] = $this->perform_install_action( $viva2, $this->installTablesIn( $request, $debug, $viva2 ) );
//		$status[] = $this->perform_install_action( $viva4, $this->installTablesIn( $request, $debug, $viva4 ) );
//
//		$status[] = $this->perform_install_action( $dir1, $this->installTablesIn( $request, $debug, $dir1 ) );
//		$status[] = $this->perform_install_action( $dir2, $this->installTablesIn( $request, $debug, $dir2 ) );
//		$status[] = $this->perform_install_action( $dir3, $this->installTablesIn( $request, $debug, $dir3 ) );
//		$status[] = $this->perform_install_action( $dir4, $this->installTablesIn( $request, $debug, $dir4 ) );
//		$status[] = $this->perform_install_action( $dir5, $this->installTablesIn( $request, $debug, $dir5 ) );
//		
//		return $status;
//	}

		function perform_install_action( $label, $result )
		{
			$checked = ("1" == $result) ? "checked='checked'" : "";

			$check_box = "<input type='checkbox' $checked disabled>";
			$row = "<tr><td style='font-size:10px;'>$check_box $label</td></tr>";
			return $row;
		}

	function createDatabase( $request, $debug )
	{
		$status = False;

		$debug->println( "<!-- InstallController::createDatabase start -->" );
		$debug->indent();
		{
			$dbadmin    = $request["dbadmin"];
			$dbpassword = $request["dbpassword"];

			$opusername = OPENPAGE_USERNAME;
			$oppassword = OPENPAGE_PASSWORD;
			$ophostname = OPENPAGE_HOSTNAME;

			$database_name = DBPREFIX . DATABASE;
			
			$db = new DBi( $dbadmin, HOSTNAME, $dbpassword, False );
			if ( $db->connect( $debug ) )
			{
				$sql = "CREATE DATABASE $database_name";
				if ( $db->change( null, $sql, $debug ) )
				{
					$status = True;
					$debug->println( "<!-- Created Database: $database_name -->" );
				} else {
					$debug->println( "<!-- Could not create database! -->" );
				}
			}
			else
			{
				$debug->println( "<!-- Invalid credentials for connection to db -->" );
			}
		}
		$debug->outdent();
		$debug->println( "<!-- InstallController::createDatabase end : $status -->" );
		
		return $status;
	}

	function grantExecuteToPublic( $request, $debug )
	{
		$status = True;

		$debug->println( "<!-- InstallController::grantExecuteToPublic start -->" );
		$debug->indent();
		{
			$dbadmin    = $request["dbadmin"];
			$dbpassword = $request["dbpassword"];

			$opusername = OPENPAGE_USERNAME;
			$oppassword = OPENPAGE_PASSWORD;
			$ophostname = OPENPAGE_HOSTNAME;

			$database_name = DBPREFIX . DATABASE;
			
			$db = new DBi( $dbadmin, HOSTNAME, $dbpassword, False );
			if ( $db->connect( $debug ) )
			{
				$sql = "GRANT EXECUTE ON $database_name.* TO '$opusername'@'$ophostname' IDENTIFIED BY '$oppassword'";
				$status = $db->change( DATABASE, $sql, $debug );
				if ( $status )
				{
					$debug->println( "<!-- Added auth -->" );
				}
			}
			else
			{
				$debug->println( "<!-- Invalid credentials for connection to db -->" );
			}
		}
		$debug->outdent();
		$debug->println( "<!-- InstallController::grantExecuteToPublic end : $status -->" );
		
		return $status;
	}
	
	function installTablesIn( $request, $debug, $sql_dir )
	{
		$status = True;

		$debug->println( "<!-- InstallController::installTables start ($sql_dir) -->" );
		$debug->indent();
		{
			$dbadmin    = $request["dbadmin"];
			$dbpassword = $request["dbpassword"];

			$opusername = OPENPAGE_USERNAME;
			$oppassword = OPENPAGE_PASSWORD;
			$ophostname = OPENPAGE_HOSTNAME;

			$database_name = DBPREFIX . DATABASE;
			
			$files = scandir( $sql_dir );
			if ( ! empty( $files ) )
			{
				$db = new DBi( $dbadmin, HOSTNAME, $dbpassword, False );
				if ( $db->connect( $debug ) )
				{
					/*
					   Below are two almost identical loops, the first processes .sql files starting with '_'.
					   The second processes .sql files that don't.
					 */

					foreach ( $files as $pos => $file )
					{
						if ( ("." != $file[0]) && ("_blank.sql" != "$file") && $this->endsWith( $file, ".sql" ) )
						{
							if ( "_" == $file[0] )
							{
								$debug->println( "<!-- Trying to load: $file -->" );
								$sql = SQL_loadfile( $sql_dir . "/" . $file );
								if ( False !== $sql )
								{
									if ( $db->multichange( DATABASE, $sql, $debug ) )
									{
										$debug->println( "<!-- Added $file -->");
									}
									else
									{
										$status = False;
									}
								}
							}
						}
					}

					foreach ( $files as $pos => $file )
					{
						if ( ("." != $file[0]) && ("_blank.sql" != "$file") && $this->endsWith( $file, ".sql" ) )
						{
							if ( "_" != $file[0] )
							{
								$debug->println( "<!-- Trying to load: $file -->" );
								$sql = SQL_loadfile( $sql_dir . "/" . $file );
								if ( False !== $sql )
								{
									if ( $db->multichange( DATABASE, $sql, $debug ) )
									{
										$debug->println( "<!-- Added $file -->");
									}
									else
									{
										$status = False;
									}
								}
							}
						}
					}
				}
				else
				{
					$debug->println( "<!-- Invalid credentials for connection to db -->" );
				}
			}
		}
		$debug->outdent();
		$debug->println( "<!-- InstallController::installTables end : $status -->" );
		
		return $status;
	}
}
?>