<?php

class PasswordController extends Controller
{
	function __construct()
	{}
	
	function first( $array )
	{
		if ( count( $array ) ) return $array[0];
	}
	
	/*
	 *  Returns boolean
	 */
	function perform( $session, $request, $debug )
	{
		$ret = False;

		$debug->println( "<!-- PasswordController::perform() start -->" );
		$debug->indent();
		{
			$msg = "<!-- performing: " . $request["action"] . " -->";
			$debug->println( $msg );
			
			switch ( $request["action"] )
			{
			case "reset_password":
				$ret = $this->resetPassword( $session, $request, $debug );
				break;
			case "change_password":
				$ret = $this->changePassword( $session, $request, $debug );
				break;
			case "activate":
				$ret = $this->activate( $session, $request, $debug );
				break;
			}
			
			if ( is_array( $ret ) ) $ret = True;
		}
		$debug->outdent();
		$debug->println( "<!-- Page::perform() end : $ret -->" );

		return $ret;
	}

	function resetPassword( $session, $request, $debug )
	{
		$email_address = $request["email"];
	
		$tuple = $this->first( DBi_callProcedure( DATABASE, "Activation_Generate('$email_address', 'reset_password' )", $debug ) );
		if ( $tuple )
		{
			$token = $tuple["token"];

			$url = "http://" . $_SERVER["SERVER_NAME"] . PAGES . "/account/password/change/?token=" . $token;
		
			$subject = "Password Reset";
			
			$message  = "Hi,\n";
			$message .= "\n";
			$message .= "Userid: $email_address\n";
			$message .= "\n";
			$message .= "To reset your Oysta.com.au password, please click on this link.\n";
			$message .= "$url\n";
			$message .= "\n";
			$message .= "Regards,\n";
			$message .= "OYSTA.COM.AU\n";
			
			$email = new Email();
			$email->setFrom( "noreply@" . MAILDOMAIN );
			$email->setRecipients( $email_address );
			$email->setBCCs( "daniel.bradley@imperial-standard.com" );
			$email->setSubject( $subject );
			$email->setMessage( $message );
		
			$email->send( $debug );
			
			return $token;
		} else {
			return False;
		}
	}

	function changePassword( $session, $request, $debug )
	{
		$token        = $request["token"];
		$new_password = $request["new_password"];
	
		DBi_callProcedure( DATABASE, "Change_Password( '$token', '$new_password' )", $debug );
	}

	function activate( $session, $request, $debug )
	{
		$token = $request["token"];
	
		return is_array( DBi_callProcedure( DATABASE, "Activate_Account( '$token' )", $debug ) );
	}
}
?>
