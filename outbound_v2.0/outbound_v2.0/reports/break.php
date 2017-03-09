<?php 
	include "./includes/ps_pagination.php";
	include "../functions.php";
	if($_POST)
	{
		$seluser = $_POST['seluser'];
		if($_POST['seluser']!="0")
		{
			$condition = "AND agentid='".$_POST['seluser']."'";
		}
		$startdate = convertdate($_REQUEST['txtStart']);
		$startdate = $startdate." 00:00:00";
		$enddate = convertdate($_REQUEST['txtEndt']);
		$enddate = $enddate." 23:59:59";
		$sql_selct_breaktime = "SELECT * FROM is_breaktime WHERE UNIX_TIMESTAMP(startdatetime)>= '".strtotime($startdate)."' AND UNIX_TIMESTAMP(enddatetime)<='".strtotime($enddate)."' $condition ";
		$count_tot = mysql_query($sql_selct_breaktime,$connection);
		if($_REQUEST['l']=='')
		{
			$limit = 10;
		}
		else
		{
			$limit = $_REQUEST['l'];
		}
		$pager = new PS_Pagination($connection, $sql_selct_breaktime, $limit ,5, "");
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
function stateChanged1()
{
	if (xmlhttp_Update.readyState==4)
  	{
		document.getElementById('seluser').innerHTML = xmlhttp_Update.responseText;
		document.getElementById('seluser1').innerHTML = xmlhttp_Update.responseText;
	}
}
function GetXmlHttpObjectUpdate()
{
	if (window.XMLHttpRequest)
	{
		return new XMLHttpRequest();
	}
	if (window.ActiveXObject)
	{
		return new ActiveXObject("Microsoft.XMLHTTP");
	}
	return null;
}
function select_users()
{
	var process = document.getElementById('selprocess').value;
	xmlhttp_Update=GetXmlHttpObjectUpdate();	
	var url="./includes/reports/users.php"+"?process="+process;
	xmlhttp_Update.onreadystatechange=stateChanged1;
	xmlhttp_Update.open("GET",url,true);
	xmlhttp_Update.send(null);
}
function validation()
{
	if (document.getElementById("selprocess").value=="0")
	{
		alert("Please select the process.");
		return false;
	}
	if (document.getElementById("seluser").value=="0")
	{
		alert("Please select the users.");
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
function submitForm(n)
{
	document.getElementById('limit').value = n;
	document.frmprocessreport.submit();
}
</script>
<form method="post" name="frmprocessreport" action=""<?php echo 'home.php?'.$_SERVER[QUERY_STRING];?> onsubmit="return validation();">
<table width="80%" cellspacing="0" cellpadding="5" style="margin:auto;" onload="select_users();" >
<tr height="20"><td colspan="5" align="center" class="heading"> Break Time Report</td></tr>
<tr>
	<td width=20% >
		<LABEL class=sublinks>Select Process <span class="red">*</span></LABEL>
		<select name="selprocess" id="selprocess"  onchange="select_users();" >
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
	<td width=20%>
		<LABEL class=sublinks>Select Users <span class="red">*</span></LABEL>
		<select name="seluser" id="seluser">
			<option value="0">-Select Users-</option>
		</select>
	</td>
	<td width=30%>
		<LABEL class=sublinks>Start Date <span class="red">*</span></LABEL>
		<input id="calendar2_container" type="text" name="txtStart" value="<?php echo $_REQUEST['txtStart'];?>" readonly />
	</td>
	<td width=20% >
		<LABEL class=sublinks>End Date <span class="red">*</span></LABEL>
		<input id="calendar2_container2" type="text" name="txtEndt" value="<?php echo $_REQUEST['txtEndt']; ?>" readonly />
		<input type='hidden' name='l' value='10' id='limit' />
	</td>
	<td width=20% style="padding-top:25px;" align="center">
		<input type="image" src="images/search.png" style="border:0px;" />
	</td>
</tr>
</table>
<?php

if($_POST)
{
	$processid = $_POST['selprocess'];
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
	echo "<table width='80%' cellpadding='0' cellspacing='1' style='margin:auto;' align='center' >";
		echo "<tr class='pagenation_textcolor'><td align='left'>".$pager->renderPrev()."</td><td align='center' colspan='2'>";
		echo "Records per Page: <a href='javascript:void(0)' onclick=\"submitForm('10')\" style='color:$color1;'>10</a>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=\"submitForm('20')\" style='color:$color2;'>20</a>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=\"submitForm('30')\" style='color:$color3;'>30</a>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=\"submitForm('40')\" style='color:$color4;'>40</a>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=\"submitForm('50')\" style='color:$color5;'>50</a>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=\"submitForm('100')\" style='color:$color6;'>100</a>&nbsp;&nbsp;&nbsp;&nbsp<b style='color:#ff9900;'>Total Count:</b>".$count;
		echo "</td><td align='right' >".$pager->renderNav()."&nbsp;&nbsp;".$pager->renderNext()."</td></tr>";
		
	echo "<tr><td colspan='4' height='20' >&nbsp;</td></tr>";
		
		echo "<tr >
			<th class='stylefour' width='20%'>Date</td>
			<th class='stylefour' width='20%'>Agent Name</td>
			<th class='stylefour' width='20%'>Process Name</td>
			<th class='stylefour' width='20%'>Break time</td>	
		  </tr>";
	if(mysql_num_rows($rs)>0)
	{
		$count =1;
		$totbreak = "0";
		while($row = mysql_fetch_array($rs))
		{
						
			$sql_select_users = mysql_query("SELECT firstname,lastname FROM is_users WHERE id='$row[1]'",$connection);
			$row_name = mysql_fetch_row($sql_select_users);
			
			$sql_select_process = mysql_fetch_row(mysql_query("SELECT processname FROM is_process WHERE id='$processid'",$connection));
			$processname = $sql_select_process[0];
			$fullname = $row_name[0].' '.$row_name[1];
			
			$sdate = strtotime($row[2]);
			$edate = strtotime($row[3]);
			
			$breaktime = $edate - $sdate;
			$totbreak = $totbreak + $breaktime;

			if($count%2==0 && $count!=0)
			{
				
				echo "<tr>";
				echo "<td class='stylefourtext'>".$row[2]."</td>";
				echo "<td class='stylefourtext'>".$fullname."</td>";
				echo "<td class='stylefourtext'>".$processname."</td>";
				echo "<td class='stylefourtext'>".converthours($breaktime)."</td>";
				echo "</tr>";
			}	
			else
			{
				echo "<tr>";
				echo "<td class='stylefourtext1'>".$row[2]."</td>";
				echo "<td class='stylefourtext1'>".$fullname."</td>";
				echo "<td class='stylefourtext1'>".$processname."</td>";
				echo "<td class='stylefourtext1'>".converthours($breaktime)."</td>";
				echo "</tr>";
			}
			$count++;
		}
		$count = $count - 1;
		echo "<tr>
				<td align='center' class='stylefourtext'>Total No of record:</td>";
			echo 	"<td align='center' class='stylefourtext'> $count </td>";
		echo "		<td align='center' class='stylefourtext'>Total Break Time:</td>";
			echo 	"<td align='center' class='stylefourtext'> ".converthours($totbreak)." </td>
		     </tr>";
	}
	else
	{
		echo "<tr><td colspan='4' align='center' class='red'>No Records found.</td></tr>";
	}
	if(mysql_num_rows($rs)>0)
	{
		echo "<tr class='pagenation_textcolor' ><td style='padding-top:20px;'>".$pager->renderPrev()."</td><td >&nbsp;</td><td align='right' style='padding-top:20px;' colspan='2'>".$pager->renderNav()."&nbsp;&nbsp;".$pager->renderNext()."</td></tr>";
	}
	 	echo "</table> ";
}
?>
</form>
<script>
loadcal();
</script>
