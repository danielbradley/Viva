<?php

include_once( VIVA_INC     . "/_Libraries/Viva/Objects/InputValidation.php" );

class ResetPasswordView extends View
{
	function __construct( $model, $request, $debug )
	{
		$this->model   = $model;
		$this->request = $request;
	}

	function render( $out )
	{
		switch( $this->request["action"] )
		{
		case "reset_password":
			$required["email_address"] = "EMAIL";

			$iv = new InputValidation( $this->request, $required );
			break;
		default:
			$iv = new InputValidation( $this->request, array() );
		}

		$email_address = $this->request["email_address"];

		if ( "true" == $this->request["completed"] )
		{
			$url = "http://" . $_SERVER["SERVER_NAME"] . PAGES . "/reset_password/?token=" . $this->request["tok"];

			if ( "true" == SENDMAIL )
			{
				$out->println( "<p>An email has been sent that will allow you to change your password.</p>" );
			} else {
				$out->println( "<p>Emails are currently disabled: please use the link below to change your password.</p>" );
				$out->println( "<br><p><a href='$url'>$url</a></p>" );
			}
		} else {
?>	
			<p>
			To reset your password, please enter the email address that you registered your account with below. 
			</p>
			<br>
			<form class='std' id='form1' method='post' action='' onsubmit='return validate_form( "form1" );'>
				<div>
					<input type='hidden' name='action' value='reset_password'>
				
					<label>Email Address</label>
					<input class='text' id='req_email_address' name='email_address' value='<?php echo $email_address ?>'>
					<span class='required' id='email_address' <?php echo $iv->value( "email_address" ) ?>>Required</span>
					<span class='required' <?php echo $iv->value( "email_address_invalid" ) ?>>Invalid</span>
				</div>
				<div>
					<input class='button' type='submit' name='submit' value='Send Email'>
				</div>
			</form>
<?php if ( "true" == $this->request["invalid_email"] ) { ?>	
			<p style='color:red'>That is not a valid email address.</p>
<?php } ?>
<?php	
		}
	}
}

?>