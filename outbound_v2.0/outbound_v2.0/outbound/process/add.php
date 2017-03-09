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
	if($_POST)
	{
		$processname = $_POST['processname'];
		$agentsQuery = mysql_query("SELECT firstname,lastname FROM is_users WHERE id IN(".implode(",",$_POST['agents']).")",$connection);
		while($age = mysql_fetch_row($agentsQuery))
		{
			$agents.=$age[0]." ".$age[1].",";
		}
		$check = mysql_query("SELECT *FROM is_process WHERE processname='$processname'",$connection);
		if(mysql_num_rows($check)==0)
		{
			mysql_query("INSERT INTO is_process VALUES(NULL,'1','$processname','')",$connection);
			$processid = mysql_insert_id();
			$agentsArr = $_POST['agents'];
			for($j=0;$j<count($agentsArr);$j++)
			{
				mysql_query("INSERT INTO is_hierarchy VALUES(NULL,'$processid','0','$agentsArr[$j]')",$connection);
			}
			$timestamp = date("Y-m-d G:i:s");
			$insertTracking = mysql_query("INSERT INTO is_tracking VALUES(NULL,'".$_SESSION['userid']."','Add Outbound Process','$processname<br>$agents<br>','','$timestamp')",$connection);
			echo "<script language='javascript'>
					alert('Process Created Successfully');
					location.href = 'home.php?".$_SERVER['QUERY_STRING']."'
				  </script>
				 ";
		}
		else 
		{
			echo "<script language='javascript'>
					alert('This Process Name already exists');
					location.href = 'home.php?".$_SERVER['QUERY_STRING']."'
				  </script>
				 ";
		}
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
<table width="40%" cellpadding="0" cellspacing="0" style="margin:auto">
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><span class="heading">Add Out-Bound Process</span></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td width="50%">Process Name <span class="red">*</span></td>
	<td><input type="text" name="processname" id="processname" value="<?=$_POST['processname']?>" /></td>
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
			$assignedAgents = mysql_query("SELECT *FROM is_users WHERE isagent=1",$connection);
			while($row = mysql_fetch_row($assignedAgents))
			{
				$checkUsersQuery = mysql_query("SELECT *FROM is_hierarchy WHERE userid='$row[0]' AND level='0'",$connection);
				if(mysql_num_rows($checkUsersQuery)==0)
				echo "<option value='$row[0]'>$row[1] $row[2]</option>";
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