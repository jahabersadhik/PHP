<?php
include "check.php";
require("connection.php");
?>
<?php
	/*$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://$IPAddress/asterisk/rawman?action=login&username=$asteriskUsername&secret=$asteriskPassword");
	curl_setopt($ch, CURLOPT_HEADER, 1);
	@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	$curlOutput = curl_exec($ch);
	$expOutput = explode(";",$curlOutput);
	$expCookeInfo = explode("mansession_id=",$expOutput[1]);
	$cookieValue = str_replace('"','',$expCookeInfo[1]);
	$expPhpSelf = explode("/",$_SERVER['PHP_SELF']);
	$strToAdd = "";
	if(!strstr($expPhpSelf[1],"."))
	{
		$strToAdd = $expPhpSelf[1]."/";
	}
	setcookie("mansession_id",$cookieValue,time()+3600,"/".$strToAdd,$_SERVER['SERVER_ADDR']);
	curl_close($ch);
	
	$ch = curl_init();
	$testurl = "https://$IPAddress/asterisk/rawman?action=getconfig&filename=users.conf";
	curl_setopt($ch, CURLOPT_URL, $testurl);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	$mansession_id = $_COOKIE['mansession_id'];
	@curl_setopt($ch, CURLOPT_COOKIE, "mansession_id = ".$cookieValue);
	//echo "<pre>";
	$curlOutput = curl_exec($ch);
	curl_close($ch);
	$userExplode = explode("Category-000",$curlOutput);
	for($i=0,$k=0;$i<count($userExplode);$i++)
	{
		$user = explode("\n",$userExplode[$i]);
		if(is_numeric(trim(substr($user[0],4,strlen($user[0])))))
		{
			$userArr[$k] = trim(substr($user[0],4,strlen($user[0])));
			$k++;
		}
	}*/
?>


<script language="javascript">
function Validate()
{
	var len = document.getElementById('len').value;
	//alert(len);
	var count = 0;
	for(var i=1;i<len;i++)
	{
		var elem = 'chk'+i;
		if(document.getElementById(elem).checked)
			count++;
	}
	if(document.getElementById('queueName').value == '' || isNaN(document.getElementById('queueName').value) || (document.getElementById('queueName').value).length!=4)
	{
		alert("Please enter the Queue Number of Length 4");
		document.getElementById('queueName').focus();
		return false;
	}
	/*if(document.getElementById('process').options[document.getElementById('process').selectedIndex].value == '-1')
	{
		alert("Please select the process for which you are creating this Queue");
		return false;
	}*/
	if(count==0)
	{
		alert("Please select atleast one extension to create Queue");
		return false;
	}
	return true;
}
</script>	

	
	<form method="post" action="createQueuesProcess.php" onsubmit="return Validate();">
	<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td width="25%">Type Queue Number<br>[*Four Digit Number]</td>
		<td width="75%"><input type="text" name="queueName" id="queueName" maxlength="4" /></td>
	</tr>
	<tr>
		<td colspan="2" height="10"></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td width="30%">Select Extensions for this Queue</td>
		<td width="70%">
		<table width="100%" cellpadding="0" cellspacing="0">
		<tr>
		<?php
			$queuesExtensionsResult = mysql_query("SELECT members FROM queues");
			
			while($queuesArray = mysql_fetch_row($queuesExtensionsResult))
			{
				$queuesArr = explode(",",$queuesArray[0]);
				$arr = array_merge((array)$arr,$queuesArr);
			}
			//print_r($arr);
			$agentsResult = mysql_query("SELECT empId,secret,firstname FROM userData WHERE designation='AGENT'");
			$j=0;
			while($agentsArr = @mysql_fetch_row($agentsResult))
			{
				if(is_array($queuesArr))
				{
					if(in_array($agentsArr[0],$arr))
					{
						echo "<td width='70'><input type='checkbox' name='chk[]' disabled value='$agentsArr[0]' />&nbsp;$agentsArr[2] { $agentsArr[0] }</td>";				
					}
					else 
					{
						echo "<td width='70'><input type='checkbox' name='chk[]' id='chk$k' value='$agentsArr[0]' />&nbsp;$agentsArr[2] { $agentsArr[0] }</td>";
						$k++;
					}
				}
				else 
				{
					echo "<td width='70'><input type='checkbox' name='chk[]' id='chk$k' value='$agentsArr[0]' />&nbsp;$agentsArr[2] { $agentsArr[0] }</td>";
					$k++;
				}
				$j++;
				if($j%3==0)
				echo "</tr><tr>";
			}
		?>
		<input type="hidden" name="len" id="len" value="<?php echo $k;?>" />
		</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Create Queue" /></td>
	</tr>
	</table>
	</form>
	<?php
		//$existingQueuesResult = mysql_query("SELECT queues.*,process.processName FROM queues JOIN process ON(queues.processId=process.processId)");
		$existingQueuesResult = mysql_query("SELECT * FROM queues");
	?>
	<div style="width:99%;height:1px;background:#ff9900"><!--space--></div>
	<table width="100%" cellpadding="3" cellspacing="0">
	<tr>
		<td colspan="4"><strong>Existing Queues</strong></td>
	</tr>
	<tr>
		<th align="left">Queue Number</th>
		<th align="left">Queue Members</th>
		<!--<th align="left">Process Name</th>-->
		<th align="left">Delete Queue</th>
	</tr>
	<?php
		while($existingQueues=mysql_fetch_row($existingQueuesResult))
		{
	?>
	<tr>
		<td width="25%" valign="top"><?php echo "<a href='home.php?page=queues&q=$existingQueues[0]'>$existingQueues[0]</a>";?></td>
		<td width="30%">
		<?php 
			echo $existingQueues[1];
		?>
		</td>
		
		<td>
			<a href="deleteQueues.php?q=<?php echo $existingQueues[0];?>">Delete</a>
		</td>
	</tr>
	<?php
		}
	?>
	</table>
