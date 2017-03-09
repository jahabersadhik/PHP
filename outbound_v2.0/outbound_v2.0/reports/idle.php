
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
		alert("Please select the User.");
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
<table width="80%" cellspacing="0" cellpadding="5" style="margin:auto;" >
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
		<LABEL class=sublinks>Select Users </LABEL>
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
	if($_POST['seluser']!="0")
	{
		$condition = "AND uniqueid='".$_POST['seluser']."'";
	}
	if($_POST['selprocess']!="0")
	{
		$condition1 = "AND userfield='".$_POST['selprocess']."'";
	}
	$startdate = convertdate($_REQUEST['txtStart']);
	$startdate = $startdate." 00:00:00";
	$enddate = convertdate($_REQUEST['txtEndt']);
	$enddate = $enddate." 23:59:59";
	
	$cdrQuery = mysql_query("SELECT sum(duration) FROM cdr WHERE UNIX_TIMESTAMP(startdatetime)>= '".strtotime($startdate)."' AND UNIX_TIMESTAMP(enddatetime)<='".strtotime($enddate)."' $condition1 $condition",$connection);
	$totalcdr = mysql_fetch_row($cdrQuery);
	
	$breakQuery = mysql_query("SELECT sum(UNIX_TIMESTAMP(enddatetime)-UNIX_TIMESTAMP(startdatetime)) FROM is_breaktime WHERE UNIX_TIMESTAMP(startdatetime)>= '".strtotime($startdate)."' AND UNIX_TIMESTAMP(enddatetime)<='".strtotime($enddate)."' AND agentid='".$_POST['seluser']."'",$connection);
	$totalbreak = mysql_fetch_row($breakQuery);
	
	$loginQuery = mysql_query("SELECT sum(UNIX_TIMESTAMP(logoutdatetime)-UNIX_TIMESTAMP(logindatetime)) FROM is_breaktime WHERE UNIX_TIMESTAMP(logindatetime)>= '".strtotime($startdate)."' AND UNIX_TIMESTAMP(logoutdatetime)<='".strtotime($enddate)."' AND userid='".$_POST['seluser']."'",$connection);
	$totallogin = mysql_fetch_row($loginQuery);
	
	$agentQuery = mysql_query("SELECT firstname,lastname FROM is_users WHERE id='".$_POST['seluser']."'",$connection);
	$agentname = mysql_fetch_row($agentQuery);
	
	$processQuery = mysql_query("SELECT processname FROM is_process WHERE id='".$_POST['selprocess']."'",$connection);
	$processname = mysql_fetch_row($processQuery);
	
	$idletime = $totallogin[0] - $totalbreak[0] - $totalcdr[0];
		
	echo "<table width='60%' cellpadding='0' cellspacing='1' style='margin:auto;' align='center' >";
	echo "<tr><td colspan='3' height='20' >&nbsp;</td></tr>";
	if($idletime > 0)
	{	
	echo "<tr >
		<th class='stylefour' width='20%'>Agent Name</td>
		<th class='stylefour' width='20%'>Process Name</td>
		<th class='stylefour' width='20%'>Idle time</td>	
	  </tr>";
	echo "<tr >
		<th class='stylefourtext1' width='20%'>$agentname[0] $agentname[1]</td>
		<th class='stylefourtext1' width='20%'>$processname[0]</td>
		<th class='stylefourtext1' width='20%'>".converthours($idletime)."</td>	
	  </tr>";
	}
	else
	{
		echo "<tr><td colspan='3' align='center' class='red'>No Records found.</td></tr>";
	}
 	echo "</table> ";
}
?>
</form>
<script>
loadcal();
</script>
