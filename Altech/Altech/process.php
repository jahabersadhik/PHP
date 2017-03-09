<?php
	include "check.php";
	require_once("connection.php");
	$hierarchyResult = mysql_query("SELECT *FROM hierarchy ORDER BY level",$connection) or die(mysql_error());
	while($hierarchy = mysql_fetch_row($hierarchyResult))
	{
		$hierarchyNameArr[] = $hierarchy[1];
		$hierarchyArr[] = $hierarchy[2];
	}
//echo "asdf";
function strTime($s) {
  $d = intval($s/60);
  $sec = ($s%60); 
  $h = intval($d/60);
  if(strlen($h)==1){
  	$h = "0".$h;
  } 
  $min = ($d%60);  
  if(strlen($min)==1){
  	$min = "0".$min;
  }
  if(strlen($sec)==1){
  	$sec = "0".$sec;
  }
  $value = $h.":".$min.":".$sec;
  return $value;
}
	
?>
<html>
<body onload="show();">
<script language="javascript">
function changeProcess()
{
	var val = document.getElementById('processName').options[document.getElementById('processName').selectedIndex].value;
	if(val != '-1')
		location.href = "home.php?page=process&pid="+val;
	else
		location.href = "home.php?page=process";
}

function show(){
	var value = document.getElementById('brakeTime').value;
	if(value==""){
		document.getElementById('brakeTime').value = 'HH:MM:SS';
	} else {
		document.getElementById('brakeTime').value = value;
	}
}
function hide(){
	document.getElementById('brakeTime').value = '';
}


function IsValidTime(timeStr) { 
	// Checks if time is in HH:MM:SS AM/PM format.
	// The seconds and AM/PM are optional.
	var timePat = /^(\d{1,2}):(\d{2})(:(\d{2}))?(\s?(AM|am|PM|pm))?$/;
	var matchArray = timeStr.match(timePat);
	
	if (matchArray == null) {
		alert("Time is not in a valid format.");
	return false;
	}
	hour = matchArray[1];
	minute = matchArray[2];
	second = matchArray[4];
	ampm = matchArray[6];

	if (second=="") { second = null; }
	if (ampm=="") { ampm = null }
		if (hour < 0  || hour > 23) {
			alert("Hour must be between 1 and 12. (or 0 and 23 for military time)");
		return false;
	}
	if (minute<0 || minute > 59) {
		alert ("Minute must be between 0 and 59.");
		return false;
	}
	if (second != null && (second < 0 || second > 59)) {
		alert ("Second must be between 0 and 59.");
		return false;
	}
	return true;
}
//  End -->

function validate(){
	var dtValue = document.getElementById('brakeTime').value;
	if(IsValidTime(dtValue)==true){
		return true;
	}else {
		return false;
	}
}


</script>

<form method="POST" name="frmProcess" action="processProcess.php" onsubmit="return validate();">
<table width="98%" cellpadding="0" cellspacing="0">
<tr>
	<td width="22%" align="left">Select Process Name&nbsp;&nbsp;<br><i>[Existing Process]&nbsp;&nbsp;</i></td>
	<td width="28%" align="left">
		<select name="processName" id="processName" onchange="changeProcess()">
		<option value="-1">--Select Process--</option>
		<?php 
			$processResult = mysql_query("SELECT *FROM process ORDER BY processId",$connection);
			
			while($process = mysql_fetch_row($processResult))
			{  
				if($_REQUEST['pid'] == $process[0])
					echo "<option value='$process[0]' selected>$process[1]</option>";
				else 
					echo "<option value='$process[0]'>$process[1]</option>";
				
				if($_REQUEST['pid']==$process[0])
				{ 
					$hierarchyGroup = explode(",",$process[2]); 
					$disabledStatus = '';
					$startDate = $process[3];
					$endDate = $process[4];
					$brakeTime =  strTime($process[5]);
					$teamArr = explode(",",$process[6]);
					$queueAgents = mysql_query("SELECT members FROM queues WHERE queueName!='$process[7]'");
					while($ags = mysql_fetch_row($queueAgents))
					{
						$agents .= $ags[0].",";
					}
					//print_r($teamArr);
					

				}
				else 
				{
					$hierarchyGroup[] = '';
					$disabledStatus = 'disabled';
					
					/*$startDate = '';
					$endDate = '';*/
				}
				if($_REQUEST['pid']!=""){
					$disabledStatus = '';
					
				}else {
					$disabledStatus = 'disabled';
				}
			}
		?>
		</select>
	</td>
	<td width="22%" align="left" height="40">Type Process Name&nbsp;&nbsp;<br><i>[New Process]</i>&nbsp;&nbsp;</td>
	<td width="28%" align="left"><input type="text" name="newProcessName" id="newProcessName" <?php echo $disabledStatus;?>  style="width:120px;" /></td>
</tr>
<tr>
	<td width="22%" align="left" height="40">Update Process Name&nbsp;&nbsp;<br><i>[New Name for Process]&nbsp;&nbsp;</i></td>
	<td width="28%" align="left"><input type="text" name="changedName" style="width:120px;" id="changedName" <?php echo $disabledStatus;?> /></td>
	<td width="22%" align="left">Break Time <i>[New brake time for Process]&nbsp;&nbsp;</i></td>
	<td width="28%" align="left"><input type="text" name="brakeTime" style="width:120px;" id="brakeTime" onblur="show();" onclick="hide();" value="<?php echo $brakeTime;?>" /></td>
</tr>
<!--<tr>
	<td width="22%" align="left" height="40">Process Start Date&nbsp;&nbsp;</td>
	<td width="28%" align="left"><input type="text" name="startDate" id="startDate" value="<?php echo $startDate; ?>" /></td>
	<td width="22%" align="left">Process End Date&nbsp;&nbsp;</td>
	<td width="28%" align="left"><input type="text" name="endDate" id="endDate" value="<?php echo $endDate; ?>" /></td>
</tr> -->
<tr>
	<td colspan="4">
	<?php
		for($i=0;$i<(count($hierarchyArr)-1);$i++)
		{
			$k=1;
			echo "<div style='width:97%;background:#fff;margin-top:15px;font-weight:bold;padding-left:15px;'>".str_replace("~"," ",$hierarchyNameArr[$i])."</div>";
			$employeesResult = mysql_query("SELECT firstname,lastname,empId FROM userData WHERE designation='$hierarchyNameArr[$i]'",$connection);
			echo "<table width='100%' cellpadding='2' cellspacing='2'>";
			echo "<tr><td width='33%'>&nbsp;</td><td width='33%'>&nbsp;</td><td width='33%'>&nbsp;</td></tr><tr>";
			while($employees = mysql_fetch_row($employeesResult))
			{
				if($_REQUEST['pid']!='')
				{
					if(in_array($employees[2],$hierarchyGroup) )
					{
						echo "<td><input type='checkbox' name='chk$hierarchyArr[$i][]' value='$employees[2]' checked />&nbsp;$employees[0] $employees[1][$employees[2]]</td>";
					}
					else 
					{
						/*if(stristr($agents,$employees[2]))
							echo "<td><input type='checkbox' name='chk$hierarchyArr[$i][]' value='$employees[2]' disabled />&nbsp;$employees[0] $employees[1][$employees[2]]</td>";	
						else*/
							echo "<td><input type='checkbox' name='chk$hierarchyArr[$i][]' value='$employees[2]' />&nbsp;$employees[0] $employees[1][$employees[2]]</td>";
					}
					if($k%3 == 0)
						echo "</tr><tr>";
					$k++;
				}
				else 
				{
					echo "<td><input type='checkbox' name='chk$hierarchyArr[$i][]' value='$employees[2]' />&nbsp;$employees[0] $employees[1][$employees[2]]</td>";
					if($k%3 == 0)
						echo "</tr><tr>";
					$k++;
				}
			}
			echo "</tr>";
			echo "</table>";
		}
	?>
	</td>
</tr>
<tr>
	<td colspan="4">
	<?php 
		echo "<div style='width:97%;background:#fff;margin-top:15px;font-weight:bold;padding-left:15px;'>QUEUES FOR THIS PROCESS</div>";
		$queuesResult = mysql_query("SELECT queueName,members from queues",$connection);
		$k=1;
		$selectedQueuesResult = mysql_query("SELECT queueName FROM process WHERE processId = '".$_REQUEST['pid']."'",$connection);
		/*SELECTING ALREADY ALLOTTED QUEUES SO THAT IT CANNOT BE ALLOTTED TO SOME OTHER PROCESS*/		
		$filledQueuesResult = mysql_query("SELECT queueName FROM process WHERE processId != '".$_REQUEST['pid']."'",$connection);
		while($filledQueues = mysql_fetch_row($filledQueuesResult))
		{
			$filledQueuesArr[] = $filledQueues[0];
		}
		/*SELECTING ALREADY ALLOTTED QUEUES SO THAT IT CANNOT BE ALLOTTED TO SOME OTHER PROCESS END*/
		$selectedQueuesResultSet = @mysql_fetch_row($selectedQueuesResult);
		$teamArr = @explode(",",$selectedQueuesResultSet[0]);

		echo "<table width='100%' cellpadding='2' cellspacing='2'>";
		echo "<tr><td width='33%'>&nbsp;</td><td width='33%'>&nbsp;</td><td width='33%'>&nbsp;</td></tr>";
		//echo "<tr>";
		while(@$team = mysql_fetch_row($queuesResult))
		{
			if(is_array($teamArr))
			{
				
				if(in_array($team[0],$teamArr))
				{
					echo "<tr><td colspan='3'><input type='radio' checked name='team' value='$team[0]-$team[1]' />&nbsp;$team[0] { $team[1] }</td></tr>";
				}
				else if(in_array($team[0],$filledQueuesArr))
					echo "<tr><td colspan='3'><input type='radio' name='team' disabled value='$team[0]-$team[1]' />&nbsp;$team[0]{ $team[1] }</td></tr>";
				else
					echo "<tr><td colspan='3'><input type='radio' name='team' value='$team[0]-$team[1]' />&nbsp;$team[0]{ $team[1] }</td></tr>";
			}
			else 
			{
				if($team[0] == $teamArr)
					echo "<tr><td colspan='3'><input type='radio' checked name='team' value='$team[0]-$team[1]' />&nbsp;$team[0] { $team[1] }</td></tr>";
				else if(in_array($team[0],$filledQueuesArr))
					echo "<tr><td colspan='3'><input type='radio' name='team' disabled value='$team[0]-$team[1]' />&nbsp;$team[0] { $team[1] }</td></tr>";
				else
					echo "<tr><td colspan='3'><input type='radio' name='team' value='$team[0]-$team[1]' />&nbsp;$team[0] { $team[1] }</td></tr>";
			}
			//if($k%3 == 0)
				//echo "</tr><tr>";
			//$k++;
		}
		//echo "</tr>";
		echo "</table>";
	?>
	</td>
</tr>
<tr><td colspan="4">&nbsp;</td></tr>
<tr>	
	<td colspan="4"><input type="submit"  name="submit" value="Save" /></td>
</tr>
</table>
</form>
</body>
</html>
<script language="javascript">
	var val = document.getElementById('processName').options[document.getElementById('processName').selectedIndex].value;
	if(val != '-1')
	{
		document.getElementById('newProcessName').value = '';
		document.getElementById('newProcessName').disabled = true;
	}
	else
	{
		document.getElementById('newProcessName').disabled = false;
	}
</script>

