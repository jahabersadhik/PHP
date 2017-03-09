<?php
include "check.php";
require("connection.php");
?>
<html>
<head>
	<title>iSystem - Update Agents DB</title>
	<style type="text/css">
	 body,table
	 {font-family:Arial;font-size:12px;}
	</style>
</head>
<body>
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
	<form method="post" action="editQueuesProcess.php">
	<input type="hidden" name="queueNameOld" id="queueNameOld" maxlength="4" value="<?php echo $_REQUEST['q'];?>" />
	<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td width="25%">Type Queue Number<br>[*Four Digit Number]</td>
		<td width="75%"><input type="text" name="queueName" id="queueName" maxlength="4" value="<?php echo $_REQUEST['q'];?>" /></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<?php
		
	?>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td width="25%">Select Extensions for this Queue</td>
		<td width="75%">
		<table width="100%" cellpadding="0" cellspacing="0">
		<tr>
		<?php
			/*$queuesExtensionsResult = mysql_query("SELECT *FROM queues");
			$numberOfQueues = mysql_num_rows($queuesExtensionsResult);
			while($exten = mysql_fetch_row($queuesExtensionsResult))
			{
				$extensionsUsed = $extensionsUsed.",".$exten[1];
				if($exten[0] == $_REQUEST['q'])
					$selectedQueueArray = explode(",",$exten[1]);
			}
			$extensionsUsedArray = explode(",",$extensionsUsed);
			for($i=0,$j=1;$i<count($userArr);$i++,$j++)*/
			
			$queuesExtensionsResult = mysql_query("SELECT members,queueName FROM queues");
			$numberOfQueues = mysql_num_rows($queuesExtensionsResult);
			while($queuesArray = mysql_fetch_row($queuesExtensionsResult))
			{
				$queuesArr = explode(",",$queuesArray[0]);
				if($queuesArray[1] == $_REQUEST['q'])
					$selarr = explode(",",$queuesArray[0]);
				//else
				$arr = array_merge((array)$arr,$queuesArr);
			}
			/*print_r($arr);
			print_r($selarr);*/
			$agentsResult = mysql_query("SELECT empId,secret,firstname FROM userData WHERE designation='AGENT'");
			$j=0;
			while($agentsArr = @mysql_fetch_row($agentsResult))
			{
				if($numberOfQueues > 1)
				{
					if(in_array($agentsArr[0],$arr) && !in_array($agentsArr[0],$selarr))
					{
						echo "<td width='130'><input type='checkbox' name='chk[]' disabled value='$agentsArr[0]' />&nbsp;$agentsArr[2] { $agentsArr[0] }</td>";				
					}
					else if( in_array($agentsArr[0],$arr) && in_array($agentsArr[0],$selarr) ) 
					{
						echo "<td width='130'><input type='checkbox' name='chk[]' checked value='$agentsArr[0]' />&nbsp;$agentsArr[2] { $agentsArr[0] }</td>";				
					}
					else if(!in_array($agentsArr[0],$arr) && !in_array($agentsArr[0],$selarr))
					{
						echo "<td width='130'><input type='checkbox' name='chk[]' value='$agentsArr[0]' />&nbsp;$agentsArr[2] { $agentsArr[0] }</td>";
					}
					if($j%3==0)
					echo "</tr><tr>";
				}
				else 
				{
					if(in_array($agentsArr[0],$selarr))
					{
						echo "<td width='130'><input type='checkbox' name='chk[]' checked value='$agentsArr[0]' />&nbsp;$agentsArr[2] { $agentsArr[0] }</td>";				
					}
					else
					{
						echo "<td width='130'><input type='checkbox' name='chk[]' value='$agentsArr[0]' />&nbsp;$agentsArr[2] { $agentsArr[0] }</td>";
					}
					if($j%3==0)
					echo "</tr><tr>";
				}
			}
		?>
		</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Update Queue" />&nbsp;<input type="button" onclick="location.href='home.php?page=queues';" value="Back" /></td>
	</tr>
	</table>
	</form>
</body>
</html>
