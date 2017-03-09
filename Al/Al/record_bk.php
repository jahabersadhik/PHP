<?php
	//$filesArr = scandir("/var/spool/asterisk/monitor/");
	
?>
<script language="javascript">
function Validate()
{
	if(document.getElementById('startDate').value == '')
	{
		alert("Please select Start Date");
		return false;
	}
	if(document.getElementById('endDate').value == '')
	{
		alert("Please select End Date");
		return false;
	}
	if(document.getElementById('camp').options[document.getElementById('camp').selectedIndex].value == '-1')
	{
		alert("Please select a Campaign");
		return false;
	}
	return true;
}
</script>
<form name="f1" method="POST" action="home.php?page=record" onsubmit="return Validate();">
<table width="100%" cellpadding="2" cellspacing="2">
<tr>
	<td>Start Date</td>
	<td><input type="text" name="startdt" id="startDate" value="<?=$_POST['startdt']?>" style="width:100px;" /></td>
	<td>End Date</td>
	<td><input type="text" name="enddt" id="endDate" value="<?=$_POST['enddt']?>" style="width:100px;" /></td>
	<td>Select Campaign</td>
	<td>
		<select name="camp" style="width:100px;">
			<option value="-1">Sel Campaign</option>
			<?php
			$sel = mysql_query("SELECT *FROM process WHERE hierarchy like '%".$_SESSION['userName']."%'");
			while($row = mysql_fetch_row($sel))
			{
				if($_POST['camp'] == $row[7])
					echo "<option value='$row[7]' selected>$row[1]</option>";
				else
					echo "<option value='$row[7]'>$row[1]</option>";
			}			
			?>
		</select>
	</td>
	<td><input type="submit" name="submit" value="Search" /></td>
</tr>
<tr>
	<td colspan="7">
	<?php
		if($_POST)
		{
			$stdt = $_POST['startdt']." 00:00:00";
			$eddt = $_POST['enddt']." 23:59:59";
			$queue = $_POST['camp'];
			//echo strtotime($stdt);
			echo "<table width='100%' cellpadding='2' cellspacing='2'>";
			echo "<tr><td height='20'>&nbsp;</td></tr>";
			echo "<tr><td height='20' bgcolor='#fcfcfc'>Recorded Files between ".$_POST['startdt']." - ".$_POST['enddt']."</td></tr>";
			for($i=strtotime($stdt),$m=0,$k=0;$i<=strtotime($eddt);$i=$i+86400)
			{
				$m++;$filesArr = array();
				$searchstr = "";
				$searchstr = $queue.'-*-'.date('Ymd',$i).'-*';
				$ret = exec("ls /var/spool/asterisk/monitor/$searchstr",$filesArr);
				if(count($filesArr)>0)
				for($j=0;$j<count($filesArr);$j++)
				{
					$fn = str_replace("/var/spool/asterisk/monitor/","",$filesArr[$j]);
					echo "<tr><td><a href='download.php?filename=$fn&mode=r'>$fn</a></td></tr>";
				}
				else 
				{
					$k++;
					break;
				}
			}
			if($k == $m)
				echo "<tr><td>No Records Found...</td></tr>";
			echo "</table>";
		}
		else 
		{
			echo "<center><br><br>Please Select all the fields and Click Search to Display Recorded Files</center>";
		}
	?>
	</td>
</tr>
</table>
</form>
