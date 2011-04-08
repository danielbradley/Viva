<?php
//	Copyright (c) 2010 Daniel Robert Bradley. All rights reserved.
//	This software is distributed under the terms of the GNU Lesser General Public License version 2.1
?>
<?php

class Email
{
	protected $from;
	protected $recipients;
	protected $CCs;
	protected $BCCs;
	protected $subject;
	protected $message;

	function __construct()
	{}
	
	function setFrom( $from )
	{
		$this->from = $from;
	}
	
	function setRecipients( $addresses )
	{
		$this->recipients = $addresses;
	}

	function setCC( $addresses )
	{
		$this->CCs = $addresses;
	}

	function setBCCs( $addresses )
	{
		$this->BCCs = $addresses;
	}

	function setSubject( $subject )
	{
		$this->subject = $subject;
	}

	function setMessage( $message )
	{
		$this->message = $message;
	}

	function setHTML( $html )
	{
		$this->html = $html;
	}
	
	function send( $debug )
	{
		$accepted_for_delivery = false;
		$newline = "\r\n";

		$headers = "";
		$content = $this->message;

		if ( isset( $this->html ) )
		{
			$headers .= "MIME-Version: 1.0" . $newline;
			$headers .= "Content-type: text/html; charset=iso-8859-1" . $newline;
			$content = $this->html;
		}

		$headers .= "From: $this->from" . $newline;
		
		if ( $this->CCs  ) $headers .= "Cc:  $this->CCs"  . $newline;
		if ( $this->BCCs ) $headers .= "Bcc: $this->BCCs" . $newline;
	
		if ( SENDUSERMAIL )
		{
			$date = date( DATE_ISO8601 );
			$debug->println( "<!-- User Email Sent: $date; To: " . $this->recipients . "; Subject: " . $this->subject . " -->" );
			$accepted_for_delivery = mail( $this->recipients, $this->subject, $content, $headers );
		}
		else if ( SENDDEVMAIL )
		{
			$date = date( DATE_ISO8601 );
			$debug->println( "<!-- Dev Email Sent: $date; To: " . $this->recipients . "; Subject: " . $this->subject . " -->" );
			$accepted_for_delivery = mail( "", $this->subject, $content, $headers );
		}

		$debug->println( "<!--" );
		$debug->println( "" );
		$debug->println( "Subject: " . $this->subject );
		$debug->println( "To: " . $this->recipients );
		$debug->println( $headers );
		$debug->println( $content );
		$debug->println( "" );
		$debug->println( "-->" );
		
		return $accepted_for_delivery;
	}
}

?>