<?php
	include "check.php";
	include "connection.php";
	$extension = $_REQUEST[chk];
	$teamName = strtoupper($_REQUEST['teamName']);
	if(trim($teamName)!='' && count($extension)>0)
	{
		for($i=0;$i<count($extension);$i++)
		{
			mysql_query("insert into team values('$extension[$i]','$teamName')");
		}
	}
	header("location:home.php?page=teams");
?>