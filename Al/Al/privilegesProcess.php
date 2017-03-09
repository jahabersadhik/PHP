<?php
	include "check.php";
	require('connection.php');
	$hierarchyNumber = $_REQUEST['hierarchyNumber'];
	for($i=1;$i<=$hierarchyNumber;$i++)
	{
		$priviligesString = '';
		$chk = 'chk'.$i;
		$level = $_REQUEST[$chk];
		if(count($level)>0)
		$priviligesString = implode(",",$level);
		mysql_query("UPDATE hierarchy SET modules='$priviligesString' WHERE level='$i'");
	}
	header("location:home.php?page=privileges");
?>