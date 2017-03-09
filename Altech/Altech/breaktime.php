<?php 
	session_start();
	$statusQuery = mysql_query("SELECT status,totalbreak FROM loginLog WHERE empId='".$_SESSION['userName']."' AND extension='".$_SESSION['extn']."' AND loginDate='".$_SESSION['loginDate']."' AND loginTime='".$_SESSION['loginTime']."'");
	$statusResult = mysql_fetch_row($statusQuery);
	$currentStatus = $statusResult[0];
	$totalbreak = $statusResult[1];
	$hours = intval(intval($totalbreak) / 3600); 
	$minutes = intval(($totalbreak / 60) % 60);
	$seconds = intval($totalbreak % 60); 
	if(strlen($hours)!="2")
	{
		$hours = "0".$hours;
	}
	if(strlen($minutes)!="2")
	{
		$minutes = "0".$minutes;
	}
	if(strlen($seconds)!="2")
	{
		$seconds = "0".$seconds;
	}	
	$breaktime = $hours.':'.$minutes.':'.$seconds;

	
?>
	<tr>
		<td colspan="4">
		<?php
			echo "Total Break Time : $breaktime &nbsp;&nbsp;&nbsp;";
			if($currentStatus == 2)
				echo "<input type='button' value='Break Over' onclick=\"location.href='changeBreak.php?val=bo&extn=$extension&totbreak=$totalbreak';\" /> ";
			else 	
				echo "<input type='button' value='Taking Break' onclick=\"location.href='changeBreak.php?val=tb&extn=$extension&totbreak=$totalbreak';\" /> ";
		?> 
		</td>
	</tr>

