<?php
if($_POST)
{
	$queueName = $_POST['queuename'];
	$checkQueueNameResult = mysql_query("SELECT *FROM is_queue WHERE queue='$queueName'",$connection);
	$checkQueueNameResult1 = mysql_query("SELECT *FROM is_extension WHERE extensionno='$queueName'",$connection);
	$checkQueueNameResult2 = mysql_query("SELECT *FROM is_conference WHERE conferenceno='$queueName'",$connection);
	if(mysql_num_rows($checkQueueNameResult)==0 && count($_REQUEST['agents'])>0 && mysql_num_rows($checkQueueNameResult1)==0 && mysql_num_rows($checkQueueNameResult2)==0)
	{
		$agents = implode(",",$_POST['agents']).",";
		mysql_query("INSERT INTO is_queue VALUES(NULL,'$queueName','$agents')",$connection);
			
		$loginurl = "http://$ipaddress:8088/asterisk/rawman?action=login&username=$asteriskusername&secret=$asteriskpassword";
		$cookie = curlauth($loginurl);
			
			
		$queuesurl = "http://$ipaddress:8088/asterisk/rawman?action=updateconfig&srcfilename=queues.conf&dstfilename=queues.conf&Action-000000=delcat&Cat-000000=$queueNameOld&Var-000000=&Value-000000=&Action-000001=newcat&Cat-000001=$queueName&Var-000001=&Value-000001=&Action-000002=append&Cat-000002=$queueName&Var-000002=fullname&Value-000002=$queueName&Action-000003=append&Cat-000003=$queueName&Var-000003=strategy&Value-000003=rrmemory&Action-000004=append&Cat-000004=$queueName&Var-000004=timeout&Value-000004=15&Action-000005=append&Cat-000005=$queueName&Var-000005=wrapuptime&Value-000005=15&Action-000006=append&Cat-000006=$queueName&Var-000006=musicclass&Value-000006=default&Action-000007=append&Cat-000007=$queueName&Var-000007=maxlen&Value-000007=3&Action-000008=append&Cat-000008=$queueName&Var-000008=autofill&Value-000008=yes&Action-000009=append&Cat-000009=$queueName&Var-000009=updatecdr&Value-000009=yes";
		
		$agentExtensions = $_POST['agents'];
		for($j=0,$i=10;$j<count($agentExtensions);$i++,$j++)
		{
			if(strlen($i)==2)
				$zeros = '0000';
			if(strlen($i)>2)
				$zeros = '000';
			$queuesurl .=  "&Action-$zeros$i=append&Cat-$zeros$i=$queueName&Var-$zeros$i=member&Value-$zeros$i=Agent/$agentExtensions[$j]";
		}
//		echo $queuesurl;
		curlcall($queuesurl,$cookie);
		
		
		$extensionsurl = "http://$ipaddress:8088/asterisk/rawman?action=updateconfig&srcfilename=extensions.conf&dstfilename=extensions.conf&Action-000000=append&Cat-000000=queues&Var-000000=exten&Value-000000=$queueName,1,Background(record/intro2)&Action-000001=append&Cat-000001=queues&Var-000001=exten&Value-000001=$queueName,2,MixMonitor(%24{EXTEN}-%24{CALLERID(num)}-%24{STRFTIME(%24{EPOCH},,%25Y%25m%25d-%25H%25M%25S)}.gsm)&Action-000002=append&Cat-000002=queues&Var-000002=exten&Value-000002=$queueName,3,Queue(%24{EXTEN})";
		curlcall($extensionsurl,$cookie);
		
		$timestamp = date("Y-m-d G:i:s");
		$insertTracking = mysql_query("INSERT INTO is_tracking VALUES(NULL,'".$_SESSION['userid']."','Add Queue','$queueName<br>$agents<br>','','$timestamp')",$connection);
		$reloadurl = "http://$ipaddress:8088/asterisk/rawman?action=command&command=reload";
		curlcall($reloadurl,$cookie);		

		echo "
				<script language='javascript'>
					alert(\"Queue Created Successfully\");
					location.href='home.php?".$_SERVER['QUERY_STRING']."';
				</script>
		 	 ";
	}
	else
	{
		echo "
				<script language='javascript'>
					alert(\"This Queue Already Exist\");
					location.href='home.php?".$_SERVER['QUERY_STRING']."';
				</script>
			 ";
	}
}
?>
<script language="javascript">
function Validate()
{
	
	var count = document.getElementById('agents').value;
	if(document.getElementById('queuename').value == '' || isNaN(document.getElementById('queuename').value) || (document.getElementById('queuename').value).length > 4 || (document.getElementById('queuename').value)<3)
	{
		alert("Please enter the QueueNumber of Length 4");
		document.getElementById('queuename').focus();
		return false;
	}
	if(count==0 || count == '')
	{
		alert("Please select an Agent");
		document.getElementById('agents').focus();
		return false;
	}
	return true;
}
</script>	

	
<form method="post" action="home.php?<?=$_SERVER['QUERY_STRING']?>" onsubmit="return Validate();">
<table width="60%" cellpadding="0" cellspacing="0" style="margin:auto;">
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><span class="heading">Add Queue</span></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td width="50%">Type Queue Number <span class="red">*</span><br>[Four Digit Number]</td>
	<td width="50%"><input type="text" name="queuename" id="queuename" maxlength="4" /></td>
</tr>
<tr>
	<td colspan="2" height="10"></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td width="50%" valign="top">Select Agents for this Queue <span class="red">*</span></td>
	<td width="50%">
	<select name="agents[]" id="agents" multiple class="input" style="width:180px;border:1px solid #000;height:250px;">
	<option value="0">--Select Agents--</option>
	<?php
	$unassignedagents = mysql_query("SELECT id,username,firstname,lastname FROM is_users WHERE isagent=1",$connection);
	while($row = mysql_fetch_row($unassignedagents))
	{
		echo "<option value='$row[1]'>$row[1] - $row[2] $row[3]</option>"; 
	}
	?>
	</select>
	</td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="image" src="images/save.png" name="submit" onclick="return Validate();" style="border:0px;" /></td>
</tr>
</table>
</form>
	
