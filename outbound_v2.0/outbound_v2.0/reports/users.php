<?php 
	include "../connection.php";
if($_GET['process']!="")
{
	$sql_select_hierarchy = mysql_query("SELECT * FROM is_hierarchy WHERE processid='".$_GET['process']."'",$connection);
	while ($row_hierarchy = mysql_fetch_array($sql_select_hierarchy))
	{
		$sql_select_users = mysql_query("SELECT id,firstname,Lastname FROM is_users WHERE id='$row_hierarchy[3]' AND isagent='1'",$connection);
		$row_users = mysql_fetch_row($sql_select_users);
		if($row_users[0]!="")
			$str .="<option value='$row_users[0]'>$row_users[1] $row_users[2]</option>";
	}
	if($str=="")
	{
		$str .="<option value='0'>-Select Users-</option>";
	}
	else
	{
		$str;
	}
	echo $str;
}
?>
