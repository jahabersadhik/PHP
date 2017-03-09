<?php 
	include "./includes/ps_pagination.php";
	include "../functions.php";
	$startdate = convertdate($_REQUEST['txtStart']);
	$startdate = $startdate." 00:00:00";
	$enddate = convertdate($_REQUEST['txtEndt']);
	$enddate = $enddate." 23:59:59";
	if($_POST)
	{
		$sql_select_cdr = "SELECT * FROM cdr WHERE userfield='".$_POST['selprocess']."' AND (calldate BETWEEN '$startdate' AND '$enddate') AND src!='' AND dst!=''";
		$query = urlencode($sql_select_cdr);
		$count_tot = mysql_query($sql_select_cdr,$connection);
		if($_REQUEST['l']=='')
		{
			$limit = 10;
		}
		else
		{
			$limit = $_REQUEST['l'];
		}
		$pager = new PS_Pagination($connection, $sql_select_cdr, $limit ,5, "");
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
	if(document.getElementById('selprocess').value =="0")
	{
		alert("Please select the process.");
		return false;
	}
	if(document.getElementById('calendar2_container').value =="")
	{
		alert("Please select start date.");
		return false;
	}
	if(document.getElementById('calendar2_container2').value =="")
	{
		alert("Please select end date.");
		return false;
	}
	return true;
}
function exportXLS()
{
	URL = "./includes/reports/export.php?q=<?php echo $query; ?>";
	location.href= URL;
}
function submitForm(n)
{
	document.getElementById('limit').value = n;
	document.frmprocessreport.submit();
}
</script>
<form method="post" name="frmprocessreport" action=""<?php echo 'home.php?'.$_SERVER[QUERY_STRING]; ?>" onsubmit="return validate();">
<table width="70%" cellspacing="0" cellpadding="5" style="margin:auto;">
<tr height="20"><td colspan="4" align="center" class="heading"> Process Report</td></tr>
<tr height="60">
	<td width=30% >
		<LABEL class=sublinks>Select Process <span class="red">*</span></LABEL>
		<select name="selprocess" id="selprocess" >
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
		<input type="image" src="images/search.png" style="border:0px;" />
	</td>
</tr>
</table>
<?php
if($_REQUEST['selprocess']!="")
{
	if(mysql_num_rows($rs)>0)
	{
		$count_tot = mysql_num_rows($rs);
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
	echo "<table width='90%' cellpadding='0' cellspacing='1' style='margin:auto;'>";
		echo "<tr><td colspan='4' height='40'>&nbsp;</td></tr>";
		echo "<tr align='right' height='40px;'>
			<td colspan='5'>
				<a class='squarebutton' href='#' id='Excel' onclick=\"exportXLS();\" ><span>Export to EXL </span></a>
			</td>
		    </tr>";	
		echo "<tr class='pagenation_textcolor'><td align='left'>".$pager->renderPrev()."</td><td colspan='4' align='center'>";
		echo "Records per Page: <a href='javascript:void(0)' onclick=\"submitForm('10')\" style='color:$color1;'>10</a>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=\"submitForm('20')\" style='color:$color2;'>20</a>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=\"submitForm('30')\" style='color:$color3;'>30</a>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=\"submitForm('40')\" style='color:$color4;'>40</a>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=\"submitForm('50')\" style='color:$color5;'>50</a>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=\"submitForm('100')\" style='color:$color6;'>100</a>&nbsp;&nbsp;&nbsp;&nbsp<b style='color:#ff9900;'>Total Count:</b> $count_tot";
		echo "</td><td align='right' >".$pager->renderNav()."&nbsp;&nbsp;".$pager->renderNext()."</td></tr>";
		echo "<tr >
			<th class='stylefour' width='20%'>Date</td>
			<th class='stylefour' width='20%'>Process Name</td>	
			<th class='stylefour' width='20%'>Source</td>	
			<th class='stylefour' width='20%'>Destination</td>
	  		<th class='stylefour' width='20%'>Call Duration</td>
			<th class='stylefour' width='20%'>Call Disposition</td>
		  </tr>";
	if(mysql_num_rows($rs)>0)
	{
		$count =1;
		while($row = mysql_fetch_array($rs))
		{

			$sql_select_process = mysql_query("SELECT processname FROM is_process WHERE id ='".$row['userfield']."'",$connection);
			$pocess = mysql_fetch_row($sql_select_process);
			

			if($count%2==0 && $count!=0)
			{
				if(trim($row['src'])!='' && trim($row['dst'])!='')
				{
					echo "<tr>";
					echo "<td class='stylefourtext'>".$row['calldate']."</td>";
					echo "<td class='stylefourtext'>".$pocess[0]."</td>";
					echo "<td class='stylefourtext'>".$row['src']."</td>";
					echo "<td class='stylefourtext'>".$row['dst']."</td>";
					echo "<td class='stylefourtext'>".$row['duration']."</td>";
					echo "<td class='stylefourtext'>".$row['disposition']."</td>";
					echo "</tr>";
				}
			}	
			else
			{
				if(trim($row['src'])!='' && trim($row['dst'])!='')
				{
					echo "<tr>";
					echo "<td class='stylefourtext1'>".$row['calldate']."</td>";
					echo "<td class='stylefourtext1'>".$pocess[0]."</td>";
					echo "<td class='stylefourtext1'>".$row['src']."</td>";
					echo "<td class='stylefourtext1'>".$row['dst']."</td>";
					echo "<td class='stylefourtext1'>".$row['duration']."</td>";
					echo "<td class='stylefourtext1'>".$row['disposition']."</td>";
					echo "</tr>";
				}
			}
			$count++;
		}
	}
	else
	{
		echo "<tr><td colspan='5' align='center' class='red'>No Records found.</td></tr>";
	}
	if(mysql_num_rows($rs)>0)
	{
		echo "<tr class='pagenation_textcolor'><td>".$pager->renderPrev()."</td><td colspan='4'>&nbsp;</td><td align='right'>".$pager->renderNav()."&nbsp;&nbsp;".$pager->renderNext()."</td></tr>";
	}
	 	echo "</table> ";
}
?>

</form>
<script>
loadcal();
</script>
