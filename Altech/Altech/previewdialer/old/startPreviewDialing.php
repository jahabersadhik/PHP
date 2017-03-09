<?php
session_start();

include("../connection.php");
$crmData = mysql_query("select *from previewDialing where assignAgent='' order by dialingId");
$agentData = mysql_query("select *from users where typeOfUser='Call Agent' AND loggedInStatus='loggedIn' order by userId");
while($agent = mysql_fetch_array($agentData))
{
	$crm = mysql_fetch_row($crmData);
	mysql_query("update users set popWindow=1 where extensionNo='".$agent['extensionNo']."'");
	echo "update previewDialing set assignAgent='".$agent['extensionNo']."' where dialingId='$crm[0]'";
	mysql_query("update previewDialing set assignAgent='".$agent['extensionNo']."' where dialingId='$crm[0]'");
}
header("location:../home.php?page=previewmain");
?>
