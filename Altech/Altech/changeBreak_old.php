<?php
	session_start();
	include "connection.php";
	/*$userName = $_SESSION['userName'];
	$extension = $_REQUEST['extn'];*/
	$totalbreak = $_REQUEST['totbreak'];
	$existingBreakQuery = mysql_query("SELECT break FROM loginLog WHERE empId='".$_SESSION['userName']."' AND extension='".$_SESSION['extn']."' AND loginDate='".$_SESSION['loginDate']."' AND loginTime='".$_SESSION['loginTime']."'");
	$existingBreakResult = mysql_fetch_row($existingBreakQuery);
	$nowtime = strtotime(date('Y-m-d G:i:s'));
	$prevtime = strtotime($existingBreakResult[0]);
	$totalsec = ($nowtime-$prevtime)+$totalbreak;
	if($_REQUEST['val'] == 'bo')
	{
		mysql_query("UPDATE loginLog SET break='0000-00-00 00:00:00',status=1,totalbreak='$totalsec' WHERE empId='".$_SESSION['userName']."' AND extension='".$_SESSION['extn']."' AND loginDate='".$_SESSION['loginDate']."' AND loginTime='".$_SESSION['loginTime']."'");
	}
	if($_REQUEST['val'] == 'tb')
	{
		mysql_query("UPDATE loginLog SET status=2,break='".date('Y-m-d G:i:s')."' WHERE empId='".$_SESSION['userName']."' AND extension='".$_SESSION['extn']."' AND loginDate='".$_SESSION['loginDate']."' AND loginTime='".$_SESSION['loginTime']."'");
	}
	header("location:home.php");
?>
