<?php
/***********************************************************************************
Created By : Mallikarjuna Rao
Created Date: 9th August 2010
Modified Date : 9th August 2010
Purpose : To Create an Inbound(0) or Outbound(1) Process Hierarchy which helps in generating reports
Linked Tables : is_process,is_hierarchy

***********************************************************************************/
?>
<?php
	
?>
<?php
if($_POST['step1'])
{
	if($_POST['process'] && $_POST['levels'])
	{
		$total = $_POST['levels'];
		for($i=0;$i<$total;$i++)
		{
			$var[$i] = $_POST['sel'.$i];
			$tot += count($var[$i]);
		}
		$var2 = array();
		for($i=0;$i<$total;$i++)
		{
			$var2 = array_merge(array($var2),array($var[$i]));
		}
		if(count($var2) != $tot)
		{
			echo "<script language='javascript'>alert('No 2 Levels can Have same User');</script>";
		}
		else 
		{
			for($i=0;$i<$total;$i++)
			{
				for($j=0;$j<count($var[$i]);$j++)
				{
					mysql_query("INSERT INTO is_hierarchy VALUES(NULL,'".$_POST['process']."','".($i+1)."','".$var[$i][$j]."')",$connection);
				}
			}
			echo "<script language='javascript'>
					alert('Hierarchy Set Successfuly');
					location.href='home.php?".$_SERVER['QUERY_STRING']."';
				  </script>";
		}
	}
	
	$usersQuery = mysql_query("SELECT id,username,firstname,lastname,designation FROM is_users WHERE id!=1 AND isagent!=1",$connection);
	while($row = mysql_fetch_row($usersQuery))
	{
		$useridArr[] = $row[0];
		$userArr[] = $row[1]."-".$row[2]."".$row[3];
	}
	echo "<form method='POST' action='home.php?".$_SERVER['QUERY_STRING']."'>";
	echo "<input type='hidden' name='levels' value='".$_POST['levels']."' />";
	echo "<input type='hidden' name='process' value='".$_POST['process']."' />";
	echo "<input type='hidden' name='step1' value='".$_POST['levels']."' />";
	echo "<table width='100%' cellspacing='10' cellpadding='0'>";
	echo "<tr><td colspan='2'>&nbsp;</td></tr>";
	echo "<tr>
			<td colspan='4' align='center'><span class='heading'>Select Employees For Each Level - Step2</span></td>
		</tr>";
	echo "<tr><td colspan='4'>&nbsp;</td></tr>";
	echo "<tr>";
	for($i=0;$i<$_POST['levels'];$i++)
	{
		if($i%4==0 && $i!=0)
			echo "</tr><tr><td colspan='4'>&nbsp;</td></tr><tr>";
		echo "<td width='25%'>";
		echo "<span class='heading' style='color:#000;'>Level-".($i+1)."</span><br><br><select name='sel$i".'[]'."' id='sel$i' multiple class='input' style='height:180px;width:140px;'>";
		for($k=0;$k<count($userArr);$k++)
		{
			if($_POST['sel'.$i][$k] == $useridArr[$k])
				echo "<option value='$useridArr[$k]' selected>$userArr[$k]</option>";
			else
				echo "<option value='$useridArr[$k]'>$userArr[$k]</option>";
		}
		echo "</select>";
		echo "</td>";
	}
	echo "</tr>";
	if($_POST['levels']>=4)
		$colspan=4;
	else 
		$colspan = $_POST['levels'];
	echo "<tr><td colspan='$colspan' align='center'>
			<div style='float:left;width:50%;text-align:right;'><input type='image' src='images/save.png' style='border:0px;' />&nbsp;&nbsp;</div>
			<div style='float:left;width:50%;padding-top:4px;'><a class='squarebutton' href='home.php?".$_SERVER['QUERY_STRING']."'><span>Back</span></a></div>
		  </td></tr>";
	echo "</table>";
}
else 
{
?>
<script language="javascript">
function Validate()
{
	if(document.getElementById('process').value == '0')
	{
		alert("Please Select Process");
		document.getElementById('process').focus();
		return false;
	}
	if(isNaN(document.getElementById('levels').value) || document.getElementById('levels').value=='')
	{
		alert("Please enter the Number of Levels for the Selected Process");
		document.getElementById('levels').focus();
		return false;
	}
	return true;
}
</script>
<form method="POST" action="home.php?<?=$_SERVER['QUERY_STRING']?>" name="frmstep1" onsubmit="return Validate();">
<table width="50%" cellpadding="0" cellspacing="0" style="margin:auto;">
<input type="hidden" name="step1" value="step1" />
<tr><td colspan='2'>&nbsp;</td></tr>
<tr>
	<td colspan="2"><span class="heading">Number of Levels - Step1</span> </td>
</tr>
<tr><td colspan='2'>&nbsp;</td></tr>
<tr>
	<td>Select Process <span class="red">*</span></td>
	<td>
		<?php 
			$processQuery = mysql_query("SELECT *FROM is_process WHERE processtype=1",$connection);
		?>
		<select name="process" id="process" class="input">
			<option value="0">--Select Process--</option>
			<?php
				while($process = mysql_fetch_row($processQuery))
				{
					$checkProcess = mysql_query("SELECT id FROM is_hierarchy WHERE processid='$process[0]' AND level!=0",$connection);
					if(mysql_num_rows($checkProcess)==0)
					{
						echo "<option value='$process[0]'>$process[2]</option>";
					}
				}
			?>
		</select>
	</td>
</tr>
<tr><td colspan='2'>&nbsp;</td></tr>
<tr>
	<td width="50%">Number of Levels (Excluding Agents) <span class="red">*</span></td>
	<td width="50%"><input type="text" name="levels" id="levels" value="<?=$_POST['levels']?>" /></td>
</tr>
<tr><td colspan='2'>&nbsp;</td></tr>
<tr>
	<td colspan="2" align="center"><input type="image" src="images/save.png" style="border:0px;" onclick="return Validate();" /></td>
</tr>
</table>
</form>
<?php
}
?>