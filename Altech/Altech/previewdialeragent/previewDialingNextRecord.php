<?php
session_start();
include("../connection.php");	

$extensionResult = mysql_query("SELECT extension FROM loginLog WHERE empId='".$_SESSION['userName']."' AND logoutDate='0000-00-00'");
$extensionArr = mysql_fetch_row($extensionResult);
$extension = $extensionArr[0];
$today = date("Y-m-d");
$res = mysql_query("select * from current where dialertype='1' AND calltype='0' LIMIT 0,1");

if(@mysql_num_rows($res)>0)
{ 
	$row = mysql_fetch_row($res);
	//print_r($row);
	mysql_query("update current set dialedextension='$extension' where id='$row[0]'");
	//mysql_query("update users set popWindow='1' where extensionNo='$extension'");
}


header("location:../home.php?page=previewagent");
?>
