<?php
include "check.php";
require("connection.php");
$teamName = $_REQUEST['q'];
if(trim($teamName)!='')
	mysql_query("delete from team where team='$teamName'");
header("location:home.php?page=teams");
?>