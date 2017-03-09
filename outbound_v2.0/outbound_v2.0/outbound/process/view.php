<?php
/***********************************************************************************
Created By : Mallikarjuna Rao
Created Date: 7th August 2010
Modified Date : 7th August 2010
Purpose : To Create an Inbound(0) or Outbound(1) Process which helps in generating reports
Linked Tables : is_process,is_hierarchy

***********************************************************************************/
?>
<?php
	if($_POST['id'])
	{
		$processname = $_POST['processname'];
		$previousvalue = $_POST['previousvalue'];
		$agents = implode("<br>",$_POST['agents']);
		mysql_query("DELETE FROM is_hierarchy WHERE processid='".$_POST['id']."'",$connection);
		mysql_query("DELETE FROM is_process WHERE id='".$_POST['id']."'",$connection);
		$timestamp = date("Y-m-d G:i:s");
		$insertTracking = mysql_query("INSERT INTO is_tracking VALUES(NULL,'".$_SESSION['userid']."','Edit Outbound Process','$previousvalue','','$timestamp')",$connection);
		echo "<script language='javascript'>
				alert('Process Updated Successfully');
				location.href = 'home.php?".$_SERVER['QUERY_STRING']."'
			  </script>
			 ";
	}
else 
{
	$processQuery = mysql_query("SELECT *FROM is_process WHERE processtype='1'",$connection);
	$i=1;
	while($row1 = mysql_fetch_row($processQuery))
	{
		$agentsQuery = mysql_query("SELECT b.firstname,b.lastname FROM is_hierarchy as a JOIN is_users as b ON(a.userid=b.id) WHERE a.processid='$row1[0]'",$connection);
		while($name = mysql_fetch_row($agentsQuery))
		{
			$names .= $name[0]." ".$name[1].",";
		}
		
		$processname = $row1[2];
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
				<td colspan="2" align="center"><span class="heading">Delete Out-Bound Process</span></td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td class="stylefour">Process Name</td>
				<td class="stylefour">Assigned Agents</td>
			</tr>
				
<?php
		}
?>
		<tr>
			<td class="<?=$classname?>"><?=$processname?></td>
			<td class="<?=$classname?>"><?=$names?></td>

		</tr>
<?php
		$i++;
	}
?>
	</table>

<?php
}
?>