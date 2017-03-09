<?php
//mysql_close();
$connection = mysql_connect("localhost","root","PeRi") or die("Error".mysql_error());
mysql_select_db("cdr",$connection) or die("Error".mysql_error());

if($_GET['act']=="incoming"){
	
	$sql_select_cdr = mysql_query("SELECT * FROM cdr WHERE dst = ".$_GET['id']." AND UNIX_TIMESTAMP(calldate) >= '".strtotime($_GET['sdate'])."' AND UNIX_TIMESTAMP(calldate) <= '".strtotime($_GET['edate'])."'");
	
}
if($_GET['act']=="Outgoing"){
	$sql_select_cdr = mysql_query("SELECT * FROM cdr WHERE src = ".$_GET['id']." AND UNIX_TIMESTAMP(calldate) >= '".strtotime($_GET['sdate'])."' AND UNIX_TIMESTAMP(calldate) <= '".strtotime($_GET['edate'])."'");
}


?>
<table style="" align="center" border="1" width="100%">
	<tr style="color:#fsd;">
		<td >S.No</td>
		<td>Call Date</td>
		<td>Call From</td>
		<td>Call To</td>
		<td>Call Duration</td>
		<td>Call Disposition</td>
	</tr>
	<?php 	$i = 1; 
		while($row_incoming = mysql_fetch_array($sql_select_cdr)){  ?>
	<tr>
		<td><?=$i?></td>
		<td><?=$row_incoming['calldate']?></td>
		<td><?=$row_incoming['src']?></td>
		<td><?=$row_incoming['dst']?></td>
		<td><?=$row_incoming['duration']?></td>
		<td><?=$row_incoming['disposition']?></td>
	</tr>
	<?php $i++; }  ?>
		


</table>

