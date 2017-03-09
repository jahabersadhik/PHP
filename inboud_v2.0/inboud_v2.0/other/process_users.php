<?php
	include "../connection.php";
if($_GET['process']!="")
{
	$sql_select_hierarchy = mysql_query("SELECT a.processid,b.extensionno FROM is_hierarchy as a JOIN is_users as b ON (a.userid=b.id) WHERE b.isagent='1' AND processid='".$_GET['process']."'  ",$connection);
	$val = "<select name='seluser' id='users'>";
	while ($row = mysql_fetch_array($sql_select_hierarchy))
	{
		$val .= "<option value='$row[1]'>$row[1]</option>";
	}
	$val .= "</select>";
	echo $val;
}
?>
