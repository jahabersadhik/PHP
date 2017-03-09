<?php
	session_start();
	include "../connection.php";
	$userName = $_SESSION['userName'];
	$extension = $_REQUEST['extn'];
	$totalbreak = $_REQUEST['totbreak'];
	$existingBreakQuery = mysql_query("SELECT break FROM loginLog WHERE empId='$userName' AND logoutDate='0000-00-00' AND extension='$extension'");
	$existingBreakResult = mysql_fetch_row($existingBreakQuery);
	$nowtime = strtotime(date('Y-m-d G:i:s'));
	$prevtime = strtotime($existingBreakResult[0]);
	$totalsec = ($nowtime-$prevtime)+$totalbreak;
	if($_REQUEST['val'] == 'bo')
	{
		mysql_query("UPDATE loginLog SET break='0000-00-00 00:00:00',status=1,totalbreak='$totalsec' WHERE empId='$userName' AND logoutDate='0000-00-00' AND extension='$extension'");
	}
	if($_REQUEST['val'] == 'tb')
	{
		mysql_query("UPDATE loginLog SET status=2,break='".date('Y-m-d G:i:s')."' WHERE empId='$userName' AND logoutDate='0000-00-00' AND extension='$extension'");
	}
	header("location:../home.php?page=manualagent");
?>