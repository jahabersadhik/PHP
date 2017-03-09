<?php
include "check.php";
require("connection.php");
$extension = $_REQUEST[chk];
$teamName = strtoupper($_REQUEST['q']);
if(trim($teamName)!='' && count($extension)>0)
{
	mysql_query("delete from team where team = '$teamName'");
	for($i=0;$i<count($extension);$i++)
	{
		mysql_query("insert into team values('$extension[$i]','$teamName')");
	}
}
header("location:home.php?page=teams");
?>