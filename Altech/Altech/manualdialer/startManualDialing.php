<?php
session_start();
include("../connection.php");
echo "update manualDialing set assignAgent='".$agent['extensionNo']."' where dialingId='$crm[0]'";
mysql_query("update previewDialing set assignAgent='".$agent['extensionNo']."' where dialingId='$crm[0]'");

header("location:manualDialingMain.php");
	
?>