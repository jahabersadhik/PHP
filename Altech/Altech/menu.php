<?php
	require_once("connection.php");
	if(!isset($_SESSION['admin']))
	{
		$loadModules = explode(",",$modules[0]);
		for($i=0;$i<count($loadModules);$i++)
		{
			$condition = $condition."moduleId = ".$loadModules[$i];
			if($i!=(count($loadModules)-1))
				$condition .= " OR ";
		}
		$moduleNameResult = mysql_query("SELECT moduleName,link FROM modules WHERE 1 AND $condition");
		while($moduleName = mysql_fetch_row($moduleNameResult))
		{
			$moduleNameArr[] = $moduleName[0];
			$moduleLinkArr[] = $moduleName[1];
		}
		for($i=0;$i<count($moduleNameArr);$i++)
		{
			echo "<a href='$moduleLinkArr[$i]'>$moduleNameArr[$i]</a><br><br>";
		}
	}
	else 
	{
?>
		<a href="home.php?page=teams">Manage Employees</a><br><br>
		<a href="home.php?page=users">Manage Extensions</a><br><br>
		<a href="home.php?page=queues">Manage Queues</a><br><br>
		<a href="home.php?page=hierarchy">Manage Hierarchy</a><br><br>
		<a href="home.php?page=privileges">Manage Privileges</a><br><br>
		<a href="home.php?page=process">Manage Campaigns</a><br><br>
		<a href="logout.php">Log Out</a><br><br>
<?
	}
?>
