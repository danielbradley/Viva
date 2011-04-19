<?php

class ChangePasswordView extends View
{
	function __construct( $model, $request, $debug )
	{
		$this->model   = $model;
		$this->request = $request;
	}

	function render( $out )
	{
		$iv            = $this->request["IV"];
		$token         = array_get( "token",         $this->request );
		$new_password1 = array_get( "new_password1", $this->request );
		$new_password2 = array_get( "new_password2", $this->request );
		
		$out->inprint( "<div class='pad'>" );
		{
			$out->inprint( "<div class='box pad'>" );
			{
				$out->println( "<div class='heading1'><h1>Change Password</h1></div>" );
				
				$out->inprint( "<form method='post' action=''>" );
				{
					$out->inprint( "<div>" );
					{
						$out->println( "<input type='hidden' name='action' value='change_password'>" );
						$out->println( "<input type='hidden' name='token'  value='$token'>" );
					}
					$out->outprint( "</div>" );
				
					$out->inprint( "<fieldset class='hidden'>" );
					{
						$out->inprint( "<label>" );
						{
							$out->println( "<span>Password</span>" );
							$out->println( "<input class='text' type='password' name='new_password1' value='$new_password1'>" );
							$iv->getRequired( "new_password1" );
						}
						$out->outprint( "</label>" );
						
						$out->inprint( "<label>" );
						{
							$out->println( "<span>Re-enter Password</span>" );
							$out->println( "<input class='text' type='password' name='new_password2' value='$new_password2'>" );
							$iv->getRequired( "new_password2" );
						}
						$out->outprint( "</label>" );

						$out->inprint( "<label>" );
						{
							$mismatch = $iv->value( "mismatch" );
							$out->println( "<span class='required' $mismatch>Passwords do not match!</span>" );
						}
						$out->outprint( "</label>" );
						
						$out->println( "<input class='button' type='submit' name='submit' value='Change Password'>" );
					}
					$out->outprint( "</fieldset>" );
				}
				$out->outprint( "</div>" );
			}
			$out->outprint( "</div>" );
		}
		$out->outprint( "</div>" );
	}

	function old( $out )
	{
?>
			<p>
			Please enter your new password.
			</p>
			<br>
			<form class='std' id='form1' method='post' action='' onsubmit='return validate_form( "form1" );'>
				<div>
				
					<label>Password</label>
					<input class='text' type='password' id='req_new_password1' name='new_password1' value='<?php echo $this->request["new_password1"] ?>'>
					<span class='required' id='required_new_password1' <?php echo $iv->value( "new_password1" ) ?>>Required</span>

					<label>Re-enter Password</label>
					<input class='text' type='password' id='req_new_password2' name='new_password2' value='<?php echo $this->request["new_password2"] ?>'>
					<span class='required' id='required_new_password2' <?php echo $iv->value( "new_password2" ) ?>>Required</span>
					
					<label></label>
					<span class='required' <?php echo $iv->value( "mismatch" ) ?>>Passwords do not match!</span>
				</div>
				<div>
					<input class='button' type='submit' name='submit' value='Change Password'>
				</div>
			</form>
<?php	
	}
}

?>