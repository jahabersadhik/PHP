<?php
$crmData = mysql_query("select *from manualDialing");
$agentData = mysql_query("select *from users where typeOfUser='Call Agent'");
if($_POST['AssignAgent']=="Assign Agent")
{ 
	echo $count = $_REQUEST["hid"];
	for($i=0; $i<$count; $i++)
	{
		echo $sqlUpdate = "UPDATE manualDialing SET assignAgent='".$_POST['agent']."' WHERE dialingId='".$_REQUEST['chk'][$i]."'";
		mysql_query($sqlUpdate);
	}
}


?>
<form name="frmManual" method="post">
<table width=100% class=normalfont cellpadding=0 border=0 align=center>			
<tr align=left>
	<td width=180>Assign Agent</td>
	<td colspan=3 width=100% align=left> 
		<select name="agent" id="agent" onchange="changeURL();">
		<option value="0">Select Agent</option>
		<?php
			while($agent = mysql_fetch_array($agentData))
			{ 
				if($_REQUEST['agent'] == $agent['extensionNo'])
					echo "<option value='".$agent['extensionNo']."' selected>".$agent['employeeId']."</option>";
				else
					echo "<option value='".$agent['extensionNo']."'>".$agent['employeeId']."</option>";
			}
		?>
		</select>
	</td>
</tr>
<tr>
	<td colspan=5>
		<form method="post" action="startManualDialing.php">
		<table width="100%" cellpadding="2" cellspacing="2" align="center">
		<tr bgcolor="#ff9900" class="headings">
			<th align='left'>Select</th>
			<th align='left'>Customer Name</th>
			<th align='left'>Phone No</th>
			<th align='left'>Address</th>
		</tr>
		<?php
			$hid = 0;
			while($crm = mysql_fetch_array($crmData))
			{
				
				echo "<tr bgcolor='#F2F5F8'>
						<th align='left'>";
				if($crm['assignAgent'] == $_REQUEST['agent'])
				{
					echo "<input type='checkbox' name='chk[]' checked value='".$crm['dialingId']."' /></th>";$hid++;
				}
				elseif($crm['assignAgent'] != '' && $crm['assignAgent'] != $_REQUEST['agent'])
				{
					echo "";
				}
				else
				{
					echo "<input type='checkbox' name='chk[]' value='".$crm['dialingId']."' /></th>";
					$hid++;
				}

					echo "<td align='left'>".$crm['firstname']." ".$crm['lastname']."</td>
						<td align='left'>".$crm['phone']."</td>
						<td align='left'>".$crm['address']." ".$crm['state']."</td>
					  </tr>	
					 ";
			}
		?>
		<input type="hidden" name = "hid" value = "<?php echo $hid; ?>" />
		<tr>
			<td colspan="3" height="20"></td>
		</tr>
		<tr>
			<td colspan="4" height="20" align=center><input type="submit" name="AssignAgent" value="Assign Agent" class=NormalButton /></td>
		</tr>
		</table>
	</form>	
	</td>
</tr>
</table>
</form>

<script language = "javascript">
function changeURL()
{
	var agent  = document.getElementById('agent').options[document.getElementById('agent').selectedIndex].value;
	location.href = "home.php?page=manualmain2&agent="+agent;
}
</script>

