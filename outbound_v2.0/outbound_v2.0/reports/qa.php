<?php 
	include "./includes/ps_pagination.php";
	include "../functions.php";
	$startdate = convertdate($_REQUEST['txtStart']);
	$startdate = $startdate." 00:00:00";
	$enddate = convertdate($_REQUEST['txtEndt']);
	$enddate = $enddate." 23:59:59";
	if($_POST)
	{
		$sql_select_calllog = "SELECT calldate,src,dst,duration,disposition,userfield FROM `cdr` WHERE 1 AND (`calldate` BETWEEN '$startdate' AND '$enddate') AND `userfield` = '".$_POST['selprocess']."'";
		$query = urlencode($sql_select_calllog);
		$count_tot = mysql_query($sql_select_calllog,$connection);
		if($_REQUEST['l']=='')
		{
			$limit = 10;
		}
		else
		{
			$limit = $_REQUEST['l'];
		}
		$pager = new PS_Pagination($connection, $sql_select_calllog, $limit ,5, "");
		$rs = $pager->paginate();
	}

?>
<script>
var calendar1, calendar2, calendar3; 
function loadcal() 
{ 
	 calendar1 = new Epoch('cal1','popup',document.getElementById('calendar2_container'),false); 
	 calendar2 = new Epoch('cal2','popup',document.getElementById('calendar2_container2'),false);
}
function validate()
{
	if (document.getElementById("selprocess").value=="0")
	{
		alert("Please select the process.");
		frmqareport.txtLoginName.focus();
		return false;
	}
	if(document.getElementById("calendar2_container").value=="")
	{
		alert("Please select start date.");
		frmqareport.calendar2_container.focus();
		return false;
	}
	if(document.getElementById("calendar2_container2").value=="")
	{
		alert("Please select end date.");
		frmqareport.calendar2_container2.focus();
		return false;
	}
	return true;
}
function submitForm(n)
{
	document.getElementById('limit').value = n;
	document.frmqareport.submit();
}
</script>
<form method="post" name="frmqareport" onsubmit="return validate();" action=""<?php echo 'home.php?'.$_SERVER[QUERY_STRING]; ?>">
<table width="70%" cellspacing="0" cellpadding="5" style="margin:auto;">
<tr height="20"><td colspan="4" align="center" class="heading"> QA Report</td></tr>
<tr height="60">
	<td width=30% >
		<LABEL class=sublinks>Select Process <span class="red">*</span></LABEL>
		<select name="selprocess" id="selprocess"  >
			<option value="0" style="color:Grey;background:none;"> -Select Process- </option>
			<?php 
				$sql_select_hierarchy = mysql_query("SELECT a.processid,b.* FROM is_hierarchy as a JOIN is_process as b ON(a.processid=b.id) WHERE userid='".$_SESSION['userid']."'",$connection);
				while($row_process = mysql_fetch_array($sql_select_hierarchy))
				{
					if($_POST['selprocess']==$row_process[1])
					{
						 $select = 'selected';
						echo "<option value='$row_process[1]' $select >$row_process[3]</option>";
					}
					else
					{
						echo "<option value='$row_process[1]' >$row_process[3]</option>";
					}
				}
				
			?>
		</select>
	</td>
	
	<td width=30%>
		<LABEL class=sublinks>Start Date <span class="red">*</span></LABEL>
		<input id="calendar2_container" type="text" name="txtStart" value="<?php echo $_REQUEST['txtStart'];?>" readonly />
	</td>
	<td width=30%>
		<LABEL class=sublinks>End Date <span class="red">*</span></LABEL>
		<input id="calendar2_container2" type="text" name="txtEndt" value="<?php echo $_REQUEST['txtEndt']; ?>" readonly />
		<input type='hidden' name='l' value='10' id='limit' />
	</td>
	<td width=30% style="padding-top:25px;">
		<input type="image" src="images/search.png" onclick="return validate();" style="border:0px;" />
	</td>
</tr>
</table>
<?php

if($_POST)
{
	if(mysql_num_rows($rs)>0)
	{
		$count = mysql_num_rows($rs);
		if($_REQUEST['l']==10)
			$color1 = '#ff9900';
		else
			$color1 = '#666666';
		if($_REQUEST['l']==20)
			$color2 = '#ff9900';
		else
			$color2 = '#666666';
		if($_REQUEST['l']==30)
			$color3 = '#ff9900';
		else
			$color3 = '#666666';
		if($_REQUEST['l']==40)
			$color4 = '#ff9900';
		else
			$color4= '#666666';
		if($_REQUEST['l']==50)
			$color5 = '#ff9900';
		else
			$color5 = '#666666';
		if($_REQUEST['l']==100)
			$color6 = '#ff9900';
		else
			$color6 = '#666666';
	
		
	}
	echo "<table width='60%' cellpadding='0' cellspacing='1' style='margin:auto;' align='center' >";
		echo "<tr class='pagenation_textcolor'><td align='left'>".$pager->renderPrev()."</td><td align='center'>";
		echo "Records per Page: <a href='javascript:void(0)' onclick=\"submitForm('10')\" style='color:$color1;'>10</a>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=\"submitForm('20')\" style='color:$color2;'>20</a>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=\"submitForm('30')\" style='color:$color3;'>30</a>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=\"submitForm('40')\" style='color:$color4;'>40</a>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=\"submitForm('50')\" style='color:$color5;'>50</a>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=\"submitForm('100')\" style='color:$color6;'>100</a>&nbsp;&nbsp;&nbsp;&nbsp<b style='color:#ff9900;'>Total Count:</b>".$count;
		echo "</td><td align='right' >".$pager->renderNav()."&nbsp;&nbsp;".$pager->renderNext()."</td></tr>";
		
	echo "<tr><td colspan='3' height='20' >&nbsp;</td></tr>";
		
		echo "<tr >
			<th class='stylefour' width='20%'>Date</td>
			<th class='stylefour' width='20%'>Record File</td>	
			<th class='stylefour' width='20%'>Play file</td>
		  </tr>";
	if(mysql_num_rows($rs)>0)
	{
		$count =1;
		while($row = mysql_fetch_array($rs))
		{
			$filename = $row['src']."-".$row['dst']."-".str_replace(" ","-",str_replace(":","",str_replace("-","",$row['calldate']))).".gsm";
			$userid = $row['userfield'];
			if($count%2==0 && $count!=0)
			{
				
				if(trim($row['src'])!='' && trim($row['dst'])!='')
				{
					echo "<tr>";
					echo "<td class='stylefourtext'>".$row['calldate']."</td>";
					echo "<td class='stylefourtext'>".$filename."</td>";
					echo "<td class='stylefourtext'><a href='recordplayFile.php?filename=$filename&mode=v&userid=$userid&height=350&width=450' class='thickbox'>PlayFile</a></td>";
					echo "</tr>";
				}
			}	
			else
			{
				if(trim($row['src'])!='' && trim($row['dst'])!='')
				{
					echo "<tr>";
					echo "<td class='stylefourtext1'>".$row['calldate']."</td>";
					echo "<td class='stylefourtext1'>".$filename."</td>";
					echo "<td class='stylefourtext1'><a href='recordplayFile.php?filename=$filename&mode=v&userid=$userid&height=350&width=450' class='thickbox'>PlayFile</a></td>";
					echo "</tr>";
				}
			}
			$count++;
		}
	}
	else
	{
		echo "<tr><td colspan='3' align='center' class='red'>No Records found.</td></tr>";
	}
	if(mysql_num_rows($rs)>0)
	{
		echo "<tr class='pagenation_textcolor' ><td style='padding-top:20px;'>".$pager->renderPrev()."</td><td >&nbsp;</td><td align='right' style='padding-top:20px;'>".$pager->renderNav()."&nbsp;&nbsp;".$pager->renderNext()."</td></tr>";
	}
	 	echo "</table> ";
}
?>

</form>
<script>
loadcal();
</script>
