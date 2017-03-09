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
	if($_POST['id']  && $_POST['processname'])
	{
		$processname = $_POST['processname'];
		$previousvalue = $_POST['previousvalue'];
		$agents = implode("<br>",$_POST['agents']);
		mysql_query("DELETE FROM is_hierarchy WHERE processid='".$_POST['id']."'",$connection);
		$processid = $_POST['id'];
		$agentsArr = $_POST['agents'];
		for($j=0;$j<count($agentsArr);$j++)
		{
			mysql_query("INSERT INTO is_hierarchy VALUES(NULL,'$processid','0','$agentsArr[$j]')",$connection);
		}
		$timestamp = date("Y-m-d G:i:s");
		$insertTracking = mysql_query("INSERT INTO is_tracking VALUES(NULL,'".$_SESSION['userid']."','Edit Outbound Process','$processname<br>$agents<br>','$previousvalue','$timestamp')",$connection);
		echo "<script language='javascript'>
				alert('Process Updated Successfully');
				location.href = 'home.php?".$_SERVER['QUERY_STRING']."'
			  </script>
			 ";
	}
?>
<?php

if($_POST['id'] && !$_POST['processname'])
{
	$processdetailsQuery = mysql_query("SELECT *FROM is_process WHERE id='".$_POST['id']."'",$connection);
	$row = mysql_fetch_row($processdetailsQuery);
	$agentsDetailsQuery = mysql_query("SELECT b.id,b.firstname,b.lastname FROM is_hierarchy as a JOIN is_users as b ON(a.userid=b.id) WHERE a.processid='".$_POST['id']."'",$connection);
	while($agents = mysql_fetch_row($agentsDetailsQuery))
	{
		$agentsstr .= "<option value='$agents[0]' selected>$agents[1] $agents[2]</option>";
		$agents = $agents[0]."<br>".$agents[1];
	}
?>
<script language="javascript">
function Validate()
{
	if(document.getElementById('processname').value=='')
	{
		alert("Please Enter the Inbound Process Name");
		document.getElementById('processname').focus();
		return false;
	}
	if(document.getElementById('agent').value=='-1')
	{
		alert("Please Select atleast one agent for this Process");
		document.getElementById('extension').focus();
		return false;
	}
	return true;
}
</script>
<form method="POST" name="frmaddprocess" action="home.php?<?=$_SERVER['QUERY_STRING']?>">
<input type="hidden" name="id" value="<?=$_POST['id']?>" />
<input type="hidden" name="previousvalue" value="<?=$_POST['previousvalue']?>" />
<table width="40%" cellpadding="0" cellspacing="0" style="margin:auto">
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><span class="heading">Edit Out-Bound Process</span></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td width="50%">Process Name <span class="red">*</span></td>
	<td><input type="text" name="processname" id="processname" value="<?=$row[2]?>" readonly /></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td width="50%">Assign Agents <span class="red">*</span></td>
	<td>
		<select name="agents[]" id="agent" class="input" multiple style="width:180px;height:120px;">
		<option value="-1">--select Agents--</option>
		<?php
			echo $agentsstr;
			$assignedAgents = mysql_query("SELECT *FROM is_users WHERE isagent=1",$connection);
			while($row = mysql_fetch_row($assignedAgents))
			{
				$checkUsersQuery = mysql_query("SELECT *FROM is_hierarchy WHERE userid='$row[0]' AND level='0'",$connection);
				if(mysql_num_rows($checkUsersQuery)==0)
				{
					echo "<option value='$row[0]'>$row[1] $row[2]</option>";
				}
			}
		?>
		</select>
	</td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="image" src="images/save.png" style="border:0px;" onclick="return Validate();"></td>
</tr>
</table>
</form>
<?php
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
				<td colspan="3" align="center"><span class="heading">Edit Out-Bound Process</span></td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td class="stylefour">Process Name</td>
				<td class="stylefour">Assigned Agents</td>
				<td class="stylefour">Edit</td>
			</tr>
				
<?php
		}
?>
		<tr>
			<td class="<?=$classname?>"><?=$processname?></td>
			<td class="<?=$classname?>"><?=$names?></td>
			<td class="<?=$classname?>">
				<form method="POST" action="home.php?<?=$_SERVER['QUERY_STRING']?>">
					<input type="hidden" name="id" value="<?=$row1[0]?>" />
					<input type="hidden" name="previousvalue" value="<?=$names?>" />
					<input type="image" src="images/edit.png" style="border:0px;" />
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