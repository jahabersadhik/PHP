<?php
//include "connection.php";

$expPhpSelf = explode("/",$_SERVER['PHP_SELF']);
$strToAdd = "";
if(!strstr($expPhpSelf[1],".")){
	$strToAdd = $expPhpSelf[1]."/";
}
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://$IPAddress/asterisk/rawman?action=login&username=$asteriskUsername&secret=$asteriskPassword");
curl_setopt($ch, CURLOPT_HEADER, 1);
@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
$curlOutput = curl_exec($ch);
$expOutput = explode(";",$curlOutput);
$expCookeInfo = explode("mansession_id=",$expOutput[1]);
$cookieValue = str_replace('"','',$expCookeInfo[1]);
setcookie("mansession_id",$cookieValue,time()+3600,"/".$strToAdd,$_SERVER['SERVER_ADDR']);
curl_close($ch); 

?>
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
	<td width="25%">Select ProcessName</td>
	<td width="75%">
		<select name="processId" onchange='changeURL();' id="processId">
		<option value="-1">--Select Process--</option>
		<?php 
		$pidQuery = mysql_query("SELECT processId,processName FROM process WHERE hierarchy like '%".$_SESSION['userName']."%'");
		while($pidArr = mysql_fetch_row($pidQuery))
		{
			if($_REQUEST['pid']==$pidArr[0])
				echo  "<option value='$pidArr[0]' selected>$pidArr[1]</option>";
			else
				echo  "<option value='$pidArr[0]'>$pidArr[1]</option>";
		}
		?>
		</select>
	</td>
</tr>
<tr>
	<td colspan="2">
		&nbsp;
		<?php
			//mysql_query("insert into incoming values(name,email,address,phone)");
					
		?>
	</td>
</tr>
</table>
<div style='width:100%;height:auto 400px;margin:auto;' id='txtHint' class=NormalFont></div>
<script language="javascript">
var xmlhttp_curl;
var xmlhttp_Update;
function stateChanged()
{
	if (xmlhttp_curl.readyState==4)
  	{
  		//alert(xmlhttp_curl.responseText);
		document.getElementById("txtHint").innerHTML=xmlhttp_curl.responseText;
	}
}

function GetXmlHttpObject()
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
function ajaxLoad()
{
	xmlhttp_curl=GetXmlHttpObject();	
	if (xmlhttp_curl==null)
	{
		alert("Your browser does not support XMLHTTP!");
		return;
	}
	var url="ajaxActiveChannels.php";
	url = url+"?extn=0";
	url=url+"&pid=<?php echo $_REQUEST['pid']; ?>";
	url=url+"&sid="+Math.random();
	xmlhttp_curl.onreadystatechange=stateChanged;
	xmlhttp_curl.open("GET",url,true);
	xmlhttp_curl.send(null);
	setTimeout("ajaxLoad()",5000);
}
function ajax_Function(channel,mode)
{
	var channel = document.getElementById(channel).value;
	xmlhttp_Update=GetXmlHttpObjectUpdate();	
	if (xmlhttp_Update==null)
	{
		alert("Your browser does not support XMLHTTP!");
		return;
	}
	var url="ajaxActiveChannelsOperations.php?";
	/*if(mode=='transfer')
	{
		var extn = document.getElementById(channel).value;
		url = url+"&extn="+extn+"&";
	}*/
	url=url+"channel="+channel+"&pid=<?php echo $_REQUEST['pid']; ?>"+"&mode="+mode+"&sid="+Math.random();
	alert(url);
	xmlhttp_Update.onreadystatechange=stateChanged1;
	xmlhttp_Update.open("GET",url,true);
	xmlhttp_Update.send(null);
}
function changeURL()
{
	var pid = document.getElementById('processId').options[document.getElementById('processId').selectedIndex].value;
	location.href = "home.php?page=map&pid="+pid;
}
</script>
<script>
<?php
	if($_REQUEST['pid'] != -1 && $_REQUEST['pid'] != '')
	{
?>		
ajaxLoad();
<?php
	}
?>
</script>
