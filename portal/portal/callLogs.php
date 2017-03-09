<?php
include("mysql_connection.php");
$extstr = implode(",",$_POST['ext']).",";
require_once("ps_pagination.php");
function convertToDateTime($secs)
{
    $days = floor($secs / 86400);
    $secs = $secs % 86400;
    $hours = floor($secs / 3600);
    $secs = $secs % 3600;
    $mins = floor($secs / 60);
    $secs = $secs % 60;
    return "$days day(s), $hours hour(s), $mins minute(s), $secs second(s)<br />";
}

$ch = curl_init();
$connectUrl = "https://$IPAddress/asterisk/rawman?action=login&username=$asteriskUsername&secret=$asteriskPassword";
curl_setopt($ch, CURLOPT_URL, "$connectUrl");
curl_setopt($ch, CURLOPT_HEADER, 1);
@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
$curlOutput = curl_exec($ch);
$expOutput = explode(";",$curlOutput);
/*if($_SESSION['userIp'] == '61.246.55.96')
	$expOutput[1] = $expOutput[0];*/
$expCookeInfo = explode("mansession_id=",$expOutput[1]);
$cookieValue = str_replace('"','',$expCookeInfo[1]);
setcookie("mansession_id",$cookieValue,time()+3600,"/periisystem/",$_SERVER['SERVER_ADDR']);
curl_close($ch);

$ch = curl_init();
$testurl = "https://$IPAddress/asterisk/rawman?action=getconfig&filename=users.conf";
curl_setopt($ch, CURLOPT_URL, $testurl);
curl_setopt($ch, CURLOPT_HEADER, 1);
@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
$mansession_id = $_COOKIE['mansession_id'];
@curl_setopt($ch, CURLOPT_COOKIE, "mansession_id = ".$cookieValue);
$curlOutput = curl_exec($ch);
$expOutput = explode(";",$curlOutput);
$expResponse = explode("chunked",$expOutput[3]);
$finalResult['response']=$expResponse[1];
$file = explode("\n",$finalResult['response']);
curl_close($ch);

	if($_POST)
	{
		$extstr = implode(",",$_REQUEST['ext']).",";
	}
	else
	{
		$extstr = $_REQUEST['ext'];	
	}
	
	$startdate = $_REQUEST['txtStart']." 00:00:00";
	$enddate = $_REQUEST['txtEndt']." 23:59:59";
	if($_REQUEST['calltype']=='any')
	{
		$condition = " AND (INSTR('$extstr',src) OR INSTR('$extstr',dst)) AND (calldate BETWEEN '$startdate' AND '$enddate') AND src!='' AND dst!=''";
	} 
	else if($_REQUEST['calltype']=='in')
	{
		$condition = " AND INSTR('$extstr',dst) AND (calldate BETWEEN '$startdate' AND '$enddate') AND dst!=''";
	} 
	else
	{
		$condition = " AND INSTR('$extstr',src) AND (calldate BETWEEN '$startdate' AND '$enddate') AND src!=''";
	}
	
	if($_REQUEST['l']=='')
	{
		$limit = 10;
	}
	else
	{
		$limit = $_REQUEST['l'];
	}

$sql_select_calllog = "SELECT calldate,src,dst,duration,disposition FROM cdr  WHERE 1 $condition";
$count_tot = mysql_num_rows(mysql_query($sql_select_calllog,$connection_cdr));

$pager = new PS_Pagination($connection_cdr, $sql_select_calllog, $limit ,5, "param1=valu1&param2=value2&calltype=".$_REQUEST['calltype']);
$rs = $pager->paginate();
$query = urlencode($sql_select_calllog);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
<title>PERI iSystem Web Portal</title>
<link href="./css/style_new.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="./epoch_styles.css" /> <!--Epoch's styles--> 
<script type="text/javascript" src="./epoch_classes_callLogs.js"></script>
<script type="text/javascript">
var calendar1, calendar2, calendar3; 
function loadcal() 
{ 
	 calendar1 = new Epoch('cal1','popup',document.getElementById('calendar2_container'),false); 
	 calendar2 = new Epoch('cal2','popup',document.getElementById('calendar2_container2'),false);
}
function submitForm(n)
{
	//validate();
	document.getElementById('limit').value = n;
	document.callLogs.submit();
}
function validate()
{
	if(document.getElementById('ext').value == '0' || document.getElementById('ext').value == '')
	{
		alert("Please Select atleast one Extension");
		document.getElementById('ext').focus();
		return false;
	}
	else if(document.getElementById('calendar2_container').value == '')
	{
		alert("Please Select the Start Date");
		document.getElementById('calendar2_container').focus();
		return false;
	}
	else if(document.getElementById('calendar2_container2').value == '')
	{
		alert("Please Select the End Date");
		document.getElementById('calendar2_container2').focus();
		return false;
	}
	return true;
		
}
function exportXLS()
{
	URL = "export1.php?q=<?php echo $query; ?>";
	location.href= URL;
}
</script>
</head>
<body>
<div id="container">
		<div id="templatemo_container">
    <div id="templatemo_banner">
    	<div id="logo"></div>
        <div id="project_name"></div>
    </div>
    <div id="templatemo_menu_search">
        <div id="templatemo_menu">
		<?php
			include("menu.php");
		?>
<div id="maincontainer_fullpannel" class="clearfix">
    <div id="fullpannel">
    	<div class="f_top"><!--&nbsp;--></div>
        <div class="f_mid">   
<form method="post" name="callLogs" action="callLogs.php" onsubmit="return validate();">
<table width="70%" cellspacing="0" cellpadding="0" style="margin:auto;">
<tr>
	<td width=40% rowspan="3">
		<?php
			$j=0;
			for($i=0;$i<count($file);$i++)
			{
				if(strstr($file[$i],"Category-"))
				{
					$extension=substr(trim($file[$i]),-5);
					if(!is_numeric(trim($extension)))
					$extension=substr(trim($file[$i]),-4);
					$userArr[$j]['exten']=$extension;
					$userExtensionArr[$j] = trim($extension);
				}
				if(strstr($file[$i],": fullname"))
				{
					$fullname=substr(trim($file[$i]),29);
					$userArr[$j]['fullname']=$fullname;
					$userFullnameArr[$j] = $fullname;
					$j++;
				}
			}
			asort($userExtensionArr);
		?>
		<select size="25" style="width:180px; height:120px;background:none;border:1px solid #999;"  name="ext[]" id="ext"  multiple>
			<option value="0" style="color:Grey;background:none;"> -Select User- </option>
			<?php
			if($_POST)
				$extstr = implode(",",$_POST['ext']);
			else
				$extstr = $_REQUEST['ext'];
			foreach($userExtensionArr as $extenKey => $extenValue)
			{	
				if(trim($userExtensionArr[$extenKey]) != "" && trim($userFullnameArr[$extenKey]) != "" && is_numeric(trim($userExtensionArr[$extenKey])))
				{
			?>   		
					<option style="color:Green" value="<?php echo trim($userExtensionArr[$extenKey]);?>" <?php if(stristr($extstr,trim($userExtensionArr[$extenKey]))) echo 'selected';?>><?php echo trim($userExtensionArr[$extenKey])."--".$userFullnameArr[$extenKey];?></option> 
			<?php	
				}
			}
			?>
		</select>
	</td>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td width=30%>
		<LABEL class=sublinks>Start Date</LABEL>
		<input id="calendar2_container" type="text" name="txtStart" value="<?php echo $_REQUEST['txtStart'];?>" readonly />
	</td>
	<td width=30%>
		<LABEL class=sublinks>End Date</LABEL>
		<input id="calendar2_container2" type="text" name="txtEndt" value="<?php echo $_REQUEST['txtEndt']; ?>" readonly />
		<input type='hidden' name='l' value='10' id='limit' />
	</td>
</tr>
<tr>
	<td width=30%>
		<LABEL class=sublinks>Call Type</LABEL>
		<select name='calltype'  class=sublinks>
			<?php 	if($_REQUEST['calltype']=='any' && $_REQUEST['calltype']!='') { $val = 'selected'; } 
				if($_REQUEST['calltype']=='in') { $val1 = 'selected'; } 
				if($_REQUEST['calltype']=='out') { $val2 = 'selected'; }
			?>
			<option value='any' <?=$val?>>Any</option>
			<option value='in' <?=$val1?>>InComing</option>
			<option value='out' <?=$val2?>>OutGoing</option>
		</select>
	</td>
	<td align="center">
		<input type="image" src="images/search.png" style="border:0px;" />
	</td>
</tr>
</table>
<?php

	

if($_REQUEST['ext']!="")
{
	
	echo "<table width='90%' cellpadding='0' cellspacing='1' style='margin:auto;'>";
	echo "<tr><td colspan='4' height='40'>&nbsp;</td></tr>";
	echo "<tr align='right' height='40px;'>
		<td colspan='5'>
			<input type='button' name='export' value='Export XLS' onclick=\"exportXLS();\" />
		</td>
	    </tr>";	
	if(mysql_num_rows($rs)>0)
	{
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
	
		echo "<tr><td align='left'>".$pager->renderPrev()."</td><td colspan='3' align='center'>";
		echo "Records per Page: <a href='javascript:void(0)' onclick=\"submitForm('10')\" style='color:$color1;'>10</a>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=\"submitForm('20')\" style='color:$color2;'>20</a>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=\"submitForm('30')\" style='color:$color3;'>30</a>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=\"submitForm('40')\" style='color:$color4;'>40</a>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=\"submitForm('50')\" style='color:$color5;'>50</a>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=\"submitForm('100')\" style='color:$color6;'>100</a>&nbsp;&nbsp;&nbsp;&nbsp<b style='color:#ff9900;'>Total Count:</b> $count_tot";
		echo "</td><td align='right' >".$pager->renderNext()."</td></tr>";
	}
	
	echo "<tr >
			<th class='stylefour' width='20%'>Date</td>
			<th class='stylefour' width='20%'>Source</td>	
			<th class='stylefour' width='20%'>Destination</td>
	  		<th class='stylefour' width='20%'>Call Duration</td>
			<th class='stylefour' width='20%'>Call Disposition</td>
		  </tr>
	 	 ";
	if(mysql_num_rows($rs)>0)
	{
		
		$count =1;
		while($row = mysql_fetch_array($rs))
		{
			if($count%2==0 && $count!=0)
			{
				if(trim($row['src'])!='' && trim($row['dst'])!='')
				{
				echo "<tr>";
				echo "<td class='stylefourtext'>".$row['calldate']."</td>";
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
		echo "<tr><td colspan='4' align='center'>No Records found.</td></tr>";
	}
	if(mysql_num_rows($rs)>0)
		echo "<tr><td>".$pager->renderPrev()."</td><td colspan='3'>&nbsp;</td><td align='right'>".$pager->renderNext()."</td></tr>";
	echo "</table>";
}
?>
</form>	
 </div>
	<div class="f_bottom"></div>
	</div>
</div>
<?php include('footer.php');?>
</body>
</html>
<script>
loadcal();
</script>
