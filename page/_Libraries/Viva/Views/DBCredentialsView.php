<?php

class DBCredentialsView extends View
{
	function __construct( $model, $request, $debug )
	{
		$this->model   = $model;
		$this->request = $request;
		
		$this->openpageSQLDirs[] = "_SQL";
		
		$this->vivaSQLDirs[] = "_SQL/01Tables";
		$this->vivaSQLDirs[] = "_SQL/02Views";
		$this->vivaSQLDirs[] = "_SQL/04StoredProcedures";

		$this->webAppSQLDirs = array();
	}
	
	function setSQLDirs( $dirs )
	{
		$this->webAppSQLDirs = $dirs;
	}
	
	function render( $out )
	{
		$db = DBPREFIX . DATABASE;
	
?>
		<form method='post' action=''>
			<fieldset>
				<div>
					<input type='hidden' name='action' value='initialise_db'>

					<label>Target Database</label>
					<input class='text' name='' value='<?php echo $db ?>' disabled='disabled'><br>
					<label>DB Admin Username</label>
					<input class='text' name='dbadmin' value=''><br>
					<label>DB Admin Password</label>
					<input class='text' type='password' name='dbpassword' value=''><br>

					<label>&nbsp;</label>
					<input class='button' type='submit' name='submit' value='Initialise DB'><br>
				</div>
			</fieldset>
			
			<?php $this->writeSQLDirs( "OpenPage SQL", "o", $this->openpageSQLDirs, $out ) ?>
			<?php $this->writeSQLDirs( "Viva SQL",     "v", $this->vivaSQLDirs,     $out ) ?>
			<?php $this->writeSQLDirs( "WebApp SQL",   "w", $this->webAppSQLDirs,   $out ) ?>
		
		</form>
<?php
	}
	
	function writeSQLDirs( $label, $x, $dirs, $out )
	{
		$out->println( "<br>" );
		$out->println( "<fieldset><legend>$label</legend>" );
		$out->indent();
		{
			$out->println( "<div>" );
			$out->indent();
			{
				$i = 1;
				foreach ( $dirs as $dir )
				{
					$out->println( "<label>Dir $i</label>" );
					$out->println( "<input readonly class='text' name='$x$i' value='$dir'><br>" );
					$i++;
				}
			}
			$out->outdent();
			$out->println( "</div>" );
		}
		$out->outdent();
		$out->println( "</fieldset>" );
	}
}

?>