<?php
if($_POST['queuename'] && $_POST['id'])
{
	$queueName = $_POST['queuename'];
	$previousvalue = $_POST['previousvalue'];
	$agents = implode(",",$_POST['agents']).",";
	mysql_query("DELETE FROM is_queue WHERE id='".$_POST['id']."'",$connection);
		
	$loginurl = "http://$ipaddress:8088/asterisk/rawman?action=login&username=$asteriskusername&secret=$asteriskpassword";
	$cookie = curlauth($loginurl);
		
	////////////////////////////////////////////////////////////////////////////////
	$queuesurl = "http://$ipaddress:8088/asterisk/rawman?action=updateconfig&srcfilename=queues.conf&dstfilename=queues.conf&Action-000000=delcat&Cat-000000=$queueName&Var-000000=&Value-000000=";
	curlcall($queuesurl,$cookie);
	
	$match0 = "$queueName,1,Background(record/intro2)";
	$match1 = "$queueName,2,MixMonitor(%24{EXTEN}-%24{CALLERID(num)}-%24{STRFTIME(%24{EPOCH},,%25Y%25m%25d-%25H%25M%25S)}.gsm)";
	$match2 = "$queueName,3,Queue(%24{EXTEN})";
	$extensionsdeleteurl = "http://$ipaddress:8088/asterisk/rawman?action=updateconfig&srcfilename=extensions.conf&dstfilename=extensions.conf&Action-000000=delete&Cat-000000=queues&Var-000000=exten&Match-000000=$match0&Action-000001=delete&Cat-000001=queues&Var-000001=exten&Match-000001=$match1&Action-000002=delete&Cat-000002=queues&Var-000002=exten&Match-000002=$match2";
	curlcall($extensionsdeleteurl,$cookie);
	////////////////////////////////////////////////////////////////////////////////
	$reloadurl = "http://$ipaddress:8088/asterisk/rawman?action=command&command=reload";
	curlcall($reloadurl,$cookie);	
	$timestamp = date("Y-m-d G:i:s");	
	$insertTracking = mysql_query("INSERT INTO is_tracking VALUES(NULL,'".$_SESSION['userid']."','Delete Queue','','$previousvalue','$timestamp')",$connection);
	echo "
			<script language='javascript'>
				alert(\"Queue Deleted Successfully\");
				location.href='home.php?".$_SERVER['QUERY_STRING']."';
			</script>
	 	 ";
}
else 
{
?>
<?php
	$queueQuery = mysql_query("SELECT * FROM is_queue",$connection);
	$i=1;
	while($row1 = mysql_fetch_row($queueQuery))
	{
		$queuename = $row1[1];
		$agents = $row1[2];
		if($i%2==0 && $i!=0)
			$classname = "stylefourtext";
		else 	
			$classname = "stylefourtext1";
?>
<?php
		if($i==1)
		{
?>
			<table width="100%" cellpadding="0" cellspacing="1">
			<tr>
				<td colspan="3" align="center"><span class="heading">Delete Queues</span></td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td class="stylefour">Queue No.</td>
				<td class="stylefour">Agents</td>
				<td class="stylefour">Edit</td>
			</tr>
				
<?php
		}
?>
		<tr>
			<td class="<?=$classname?>"><?=$queuename?></td>
			<td class="<?=$classname?>"><?=$agents?></td>
			<td class="<?=$classname?>">
				<form method="POST" action="home.php?<?=$_SERVER['QUERY_STRING']?>">
					<input type="hidden" name="id" value="<?=$row1[0]?>" />
					<input type="hidden" name="queuename" value="<?=$row1[1]?>" />
					<input type="hidden" name="previousvalue" value="<?php echo $row1[1]."<br>".$row1[2]."<br>";?>" />
					<input type="image" src="images/delete.png" style="border:0px;" />
				</form>
			</td>
		</tr>
<?php
		$i++;
	}
?>
	</table>

<?php
}
?>
