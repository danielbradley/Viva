<?php
//	Copyright (c) 2009, 2010 Daniel Robert Bradley. All rights reserved.
//	This software is distributed under the terms of the GNU Lesser General Public License version 2.1
?>
<?php

include_once( VIVA_INC . "/_Libraries/Viva/Objects/Email.php" );

class DefaultUserSignupController extends Controller
{
	function __construct()
	{}
	
	function perform( $session, $request, $debug )
	{
		$ret = null;
	
		$debug->println( "<!-- DefaultUserSignupController::perform() start -->" );
		$debug->indent();
		{
			if ( array_key_exists( "action", $request ) )
			{
				$msg = "<!-- performing: " . $request["action"] . " -->";
				$debug->println( $msg );
				
				switch ( $request["action"] )
				{
				case "create_user":
					$ret = $this->createUser( $session, $request, $debug );
					break;
				case "resend_activation":
					$ret = $this->resendActivation( $session, $request, $debug );
					break;
				}
			}
		}
		$debug->outdent();
		$debug->println( "<!-- DefaultUserSignupController::perform() end -->" );

		return $ret;
	}

	function createUser( $session, $request, $debug )
	{
		$success = False;
	
		$debug->println( "<!-- DefaultUserSignupController::perform() start -->" );
		$debug->indent();
		{
			$username    = $request['email'];
			$password    = $request['password'];
			$first_name  = $request['first_name'];
			$family_name = $request['family_name'];
		
			$success     = DBi_callFunction( DATABASE, "user_create( '$username', '$password', '$first_name', '$family_name', 'DEFAULT' )", $debug );
			
			$debug->println( "<!-- success: $success -->" );
			
			if ( False != $success )
			{
				$this->createAndSendActivationEmail( $username, $first_name, $debug );
				$debug->println( "<!-- User created -->" );
			} else {
				$debug->println( "<!-- User was not created -->" );
			}
		}
		$debug->outdent();
		$debug->println( "<!-- DefaultUserSignupController::perform() end -->" );
		
		return $success;
	}

	function resendActivation( $session, $request, $debug )
	{
		$username    = $request['email'];
		$first_name  = "user";

		return $this->createAndSendActivationEmail( $username, $first_name, $debug );
	}

		function createAndSendActivationEmail( $email_address, $first_name, $debug )
		{
			$success = False;
		
			$sql = "Activation_Generate( '$email_address', 'activation' )";
			$tuple = $this->first( DBi_callProcedure( DATABASE, $sql, $debug ) );
					
			$token = $tuple["token"];
					
			$url = "http://" . $_SERVER["SERVER_NAME"] . PAGE . "/account/activate/?action=activate&token=" . $token;
				
			$subject = "Account Activation";

			$message =            "Hi $first_name,\n";
			$message = $message . "\n";
			$message = $message . "Your account activation code is contained below for $email_address\n";
			$message = $message . "\n";
			$message = $message . "Please click on this link to activate your account:\n";
			$message = $message . "$url\n";
			$message = $message . "\n";
			$message = $message . "Regards,\n";
			$message = $message . $_SERVER["SERVER_NAME"] . "\n";
			$message = $message . "\n";

			if ( $message )
			{
				$email = new Email();
				$email->setFrom( "noreply@" . MAILDOMAIN );
				if ( SENDUSERMAIL )
				{
					$email->setRecipients( $email_address );
				}
				$email->setBCCs( "noreply@" . MAILDOMAIN );
				$email->setSubject( $subject );
				$email->setMessage( $message );
			
				$success = $email->send( $debug );
			}
			return $success;
		}
		
		function first( $array )
		{
			if ( array_key_exists( 0, $array ) )
			{
				return $array[0];
			}	
		}
}

?>