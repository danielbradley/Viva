<?php
//	Copyright (c) 2010 Daniel Robert Bradley. All rights reserved.
//	This software is distributed under the terms of the GNU Lesser General Public License version 2.1
?>
<?php

class SummaryList extends Element
{
	function __construct( $rows, $first, $last, $max )
	{
		$this->rows  = $rows;
		$this->first = $first;
		$this->last  = $last;
		$this->max   = $max;
		
		$this->headers = array();
	}

	function addHeader( $header )
	{
		$this->headers[] = $header;
	}
	
	function render( $out )
	{
		?>
		<table>
			<thead>
				<?php $this->renderHeaders( $out ); ?>
			</thead>
			<tbody>
				<?php $this->renderRows( $out ); ?>
			</tbody>
		</table>
		<?php
	}

		function renderHeaders( $out )
		{
			foreach ( $this->headers as $header )
			{
				$header->render( $out );
			}
		}
		
		function renderRows( $out )
		{
			foreach ( $this->rows as $row )
			{
				$row->render( $out );
			}
		}
}
?>