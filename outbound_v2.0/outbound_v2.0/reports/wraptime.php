<?php 
	include "./includes/ps_pagination.php";
	include "../functions.php";
	if($_POST)
	{
		$seluser = $_POST['seluser'];
		if($_POST['seluser']!="0")
		{
			$usernameQuery = mysql_query("SELECT username FROM is_users WHERE id='".$_POST['seluser']."'",$connection);
			$username = mysql_fetch_row($usernameQuery);
			$condition = "AND dstchannel='$username[0]'";
		}
		$startdate = convertdate($_REQUEST['txtStart']);
		$startdate = $startdate." 00:00:00";
		$enddate = convertdate($_REQUEST['txtEndt']);
		$enddate = $enddate." 23:59:59";

		$sql_select_cdr_incoming = mysql_query("SELECT *from cdr WHERE lastapp='Queue' AND UNIX_TIMESTAMP(calldate)>=UNIX_TIMESTAMP('$startdate') AND UNIX_TIMESTAMP(calldate)<=UNIX_TIMESTAMP('$enddate') AND dstchannel!='' AND userfield='".$_POST['selprocess']."' $condition",$connection);
		
		$count_tot = mysql_query($sql_select_cdr_incoming,$connection);
		if($_REQUEST['l']=='')
		{
			$limit = 10;
		}
		else
		{
			$limit = $_REQUEST['l'];
		}
		$pager = new PS_Pagination($connection, $sql_select_cdr_incoming, $limit ,5, "");
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
			<th class='stylefour' width='20%'>Call Date</td>
			<th class='stylefour' width='20%'>Agent Name</td>
			<th class='stylefour' width='20%'>Source</td>
			<th class='stylefour' width='20%'>Duration</td>	
			<th class='stylefour' width='20%'>Wrap time</td>	
		  </tr>";
	if(mysql_num_rows($rs)>0)
	{
		$count =1;
		$totbreak = "0";
		$agentQuery = mysql_query("SELECT firstname,lastname FROM is_users WHERE id='".$_POST['seluser']."'",$connection);
		$agentname = mysql_fetch_row($agentQuery);
		while($row = mysql_fetch_array($rs))
		{
			$dur = strtotime($row['calldate'])+$row['duration'];
$res = mysql_query("SELECT a.*,b.nextcallDate,b.agentId,b.callDate FROM cdr as a JOIN callsList as b ON((UNIX_TIMESTAMP(b.callDate)>=UNIX_TIMESTAMP('".$row['calldate']."') AND UNIX_TIMESTAMP(b.callDate)<='$dur') AND a.dst = b.dst ) WHERE a.src='".$row['src']."' AND a.lastapp='Dial' ORDER BY a.calldate",$connection);
				if(mysql_num_rows($res)>0)
				{
					$row1 = mysql_fetch_array($res);
					$wraptime = (strtotime($row1['nextcallDate'])-strtotime($row1['callDate']))-($row['duration']-(strtotime($row1['callDate'])-strtotime($row['calldate'])));
					if($wraptime<0)
						$wraptime = '-';
					$callduration = ($row['duration']-(strtotime($row1['callDate'])-strtotime($row['calldate'])));
					$totalcallduration += $callduration;
					$totalwraptime += $wraptime;
					
				}		
			
			if($count%2==0 && $count!=0)
			{
				
				echo "<tr>";
				echo "<td class='stylefourtext'>".$row['calldate']."</td>";
				echo "<td class='stylefourtext'>".$agentname[0].$agentname[1]."</td>";
				echo "<td class='stylefourtext'>".$processname."</td>";
				echo "<td class='stylefourtext'>".$callduration."</td>";
				echo "<td class='stylefourtext'>".$wraptime."</td>";
				echo "</tr>";
			}	
			else
			{
				echo "<tr>";
				echo "<td class='stylefourtext1'>".$row['calldate']."</td>";
				echo "<td class='stylefourtext1'>".$agentname[0].$agentname[1]."</td>";
				echo "<td class='stylefourtext1'>".$processname."</td>";
				echo "<td class='stylefourtext1'>".$callduration."</td>";
				echo "<td class='stylefourtext1'>".$wraptime."</td>";
				echo "</tr>";
			}
			$count++;
		}
		$count = $count - 1;
		echo "<tr>
					<td align='center' class='stylefourtext'>Total No of record:$count</td>
					<td align='center' class='stylefourtext'>&nbsp; </td>
					<td align='center' class='stylefourtext'>Total Call Duration:".converthours($totalcallduration)."</td>
					<td align='center' class='stylefourtext'>Total Wraptime:".converthours($totalwraptime)."</td>
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
