<?php
	require('connection.php');
	$res = mysql_query("show tables");
	echo "<table border='1' width='400'>";
	echo "<tr><td><b>Table Name</b></td><td><b>Description</b></td></tr>";
	while($row = mysql_fetch_row($res))
	{
		echo "<tr><td valign='top'><i>".$row[0]."</i></td><td>";
		$tableDesc = mysql_query("desc $row[0]");
		while($desc = mysql_fetch_row($tableDesc))
		{
			echo "<div style='width:55%;float:left'><strong>".$desc[0]."</strong></div><div style='width:35%;float:left'><i>".$desc[1]."</i></div><br style='clear:both;' />";
		}
		echo "</td></tr>";
	}
	echo "</table>";
	/*session_start();
	echo $_SESSION['name'] = session_encode('mallik');*/
?>