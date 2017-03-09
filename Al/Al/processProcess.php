<?php
	include "check.php";
	require_once("connection.php");
	
	$hierarchyResult = mysql_query("SELECT *FROM hierarchy ORDER BY level",$connection);
	while($hierarchy = mysql_fetch_row($hierarchyResult))
		$hierarchyArr[] = $hierarchy[2];
	$processName = $_REQUEST['processName'];
	$changedName = $_REQUEST['changedName'];
	$startDate = $_REQUEST['startDate'];
	$endDate = $_REQUEST['endDate'];
		//for($i=0;$i<count($_REQUEST['team']);$i++)
		{
	//		$membersArr1 = @explode("-",$_REQUEST['team'][$i]);
			$membersArr1 = @explode("-",$_REQUEST['team']);
			$queueArr[$i] = $membersArr1[0];
			$membersArr[$i] = $membersArr1[1];
		}
		$members = implode(",",$membersArr);
		$teams = implode(',',$queueArr);
	
	$breakTime = split(":",$_REQUEST['brakeTime']);
	$break = (($breakTime[0]*3600)+($breakTime[1]*60)+$breakTime[2]);
	
	for($i=0;$i<count($hierarchyArr);$i++)
	{
		$checkedId = 'chk'.$hierarchyArr[$i];
		$ids = $_REQUEST[$checkedId];
		if(count($ids))
		$values = $values.",".implode(",",$ids);	
	}
	$newProcessName = trim($_REQUEST['newProcessName']);
	if($newProcessName!='')
	{
		mysql_query("INSERT INTO process VALUES(NULL,'$newProcessName','$values','$startDate','$endDate','$break','$members','$teams')",$connection);
		$processId = mysql_insert_id();
		$queuesArr = explode(",",$teams);
		for($i=0;$i<count($queuesArr);$i++)
		{
			$queuenameQuery = mysql_query("select processId from queues where queueName = '$queuesArr[$i]'",$connection);
			$queueProcessId = mysql_fetch_row($queuenameQuery);
			$processId = $queueProcessId[0].$processId .",";
	 		mysql_query("UPDATE queues SET processId='$processId' WHERE queueName='$queuesArr[$i]'",$connection);
		}
	}
	else
	{ 
		if(trim($changedName) == '')
		{
			$oldqueuenameQuery = mysql_query("select queueName from process where processId = '$processName'",$connection);
			$oldqueueNamesArr = mysql_fetch_row($oldqueuenameQuery);
			
			$oldQueueNames = explode(",",$oldqueueNamesArr[0]);
			for($i=0;$i<count($oldQueueNames);$i++)
			{
				$queuenameQuery = mysql_query("select processId from queues where queueName = '$oldQueueNames[$i]'",$connection);
				$queueProcessId = mysql_fetch_row($queuenameQuery);
				$updatedprocessId = str_replace("$processName,","",$queueProcessId[0]);
				mysql_query("UPDATE queues SET processId='$updatedprocessId' WHERE queueName='$oldQueueNames[$i]'",$connection);
			}
			mysql_query("UPDATE process SET hierarchy='$values',startDate='$startDate',endDate='$endDate',breaktime= '$break',team='$members',queueName='$teams' WHERE processId='$processName'",$connection);
			$queuesArr = explode(",",$teams);
			print_r($queuesArr);
			for($i=0;$i<count($queuesArr);$i++)
			{
				$queuenameQuery = mysql_query("select processId from queues where queueName = '$queuesArr[$i]'",$connection);
				$queueProcessId = mysql_fetch_row($queuenameQuery);
				$processId = $queueProcessId[0].$processName .",";
				//echo "UPDATED queues SET processId='$processId' WHERE queueName='$queuesArr[$i]'";
		 		mysql_query("UPDATE queues SET processId='$processId' WHERE queueName='$queuesArr[$i]'",$connection);
			}
		}
		else
		{
			$oldqueuenameQuery = mysql_query("select queueName from process where processId = '$processName'",$connection);
			$oldqueueNamesArr = mysql_fetch_row($oldqueuenameQuery);
			$oldQueueNames = explode(",",$oldqueueNamesArr[0]);
			for($i=0;$i<count($oldQueueNames);$i++)
			{
				$queuenameQuery = mysql_query("select processId from queues where queueName = '$oldQueueNames[$i]'",$connection);
				$queueProcessId = mysql_fetch_row($queuenameQuery);
				$updatedprocessId = str_replace("$processName,","",$queueProcessId[0]);
				mysql_query("UPDATE queues SET processId='$updatedprocessId' WHERE queueName='$oldQueueNames[$i]'",$connection);
			}
			mysql_query("UPDATE process SET processName='$changedName',hierarchy='$values',startDate='$startDate',endDate='$endDate',breaktime= '$break',team='$members',queueName='$teams' WHERE processId='$processName'",$connection);
			$queuesArr = explode(",",$teams);
			for($i=0;$i<count($queuesArr);$i++)
			{
				$queuenameQuery = mysql_query("select processId from queues where queueName = '$queuesArr[$i]'",$connection);
				$queueProcessId = mysql_fetch_row($queuenameQuery);
				$processId = $queueProcessId[0].$processName .",";
		 		mysql_query("UPDATE queues SET processId='$processId' WHERE queueName='$queuesArr[$i]'",$connection);
			}
		}
	}
	header("location:home.php?page=process");
?>


