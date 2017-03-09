<?php
$phn=$_POST['txtbfrom'];
$phnTo=$_POST['txtbto'];
if($_POST)
{
	$strHost = "$IPAddress";
	$strUser = "$asteriskUsername";
	$strSecret = "$asteriskPassword";
	$strChannel = "SIP/$phn";
	$strContext = "CallSnoopAndCallBarge";
	$strWaitTime = "30";
	$strPriority = "1";
	$strMaxRetry = "2";
	$strExten = "321";
	if($strExten != "")
	{
		$strCallerId = "<$strExten>";
		$length = strlen($strExten);
		if ($length && is_numeric($strExten))
		{
			$oSocket = fsockopen($strHost, 5038, $errnum, $errdesc) or die("Connection to host failed");
			fputs($oSocket, "Action: login\r\n");
			fputs($oSocket, "Events: off\r\n");
			fputs($oSocket, "Username: $strUser\r\n");
			fputs($oSocket, "Secret: $strSecret\r\n\r\n");
			fputs($oSocket, "Action: originate\r\n");
			fputs($oSocket, "Channel: $strChannel\r\n");
			fputs($oSocket, "WaitTime: $strWaitTime\r\n");
			fputs($oSocket, "CallerId: $strCallerId\r\n");
			fputs($oSocket, "Exten: $strExten\r\n");			
			fputs($oSocket, "Context: $strContext\r\n");
			//fputs($oSocket, "SetGlobalVar: $phnTo\r\n");
			fputs($oSocket, "Variable: var1=$phnTo\r\n");
			fputs($oSocket, "Priority: $strPriority\r\n\r\n");
		}
	}
}
$url_cpm_name= $_GET['cn'];
?>
<script language="javascript">
var xmlhttp_curl
function showHint()
{
	xmlhttp_curl=GetXmlHttpObject();	
	if (xmlhttp_curl==null)
	{
		alert("Your browser does not support XMLHTTP!");
		return;
	}
	var txtsbto;
	if(document.getElementById('txtbto'))
	{
		txtsbto = document.getElementById('txtbto').options[document.getElementById('txtbto').selectedIndex].value;
	}
	var url="callbarging/getvalue_barge.php";
	url=url+"?phn=<?php echo $phn; ?>&txtbto="+txtsbto;
	url=url+"&sid="+Math.random();
	xmlhttp_curl.onreadystatechange=stateChanged;
	xmlhttp_curl.open("GET",url,true);
	xmlhttp_curl.send(null);
	setTimeout('showHint()',10000);
}

function stateChanged()
{
	if (xmlhttp_curl.readyState==4)
  	{
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
</script>
<script language="javascript">
  showHint();
</script>
<form method=post name="callCustomerAccounts" >
<table border=0 height="290" width="90%" align=center>
<tr>
<td>
<div style="width:50%;height:300px;float:left;">
<?php
	echo "<table><tr><td>
			<label class=\"sublinks\">From Extension</label>
		  </td>
	  	  <td>
			<input type=\"text\" maxlength=\"4\" name=\"txtbfrom\" value=\"$phn\">
		  </td></tr></table>";
?>
</div>
<div id="txtHint" style="width:50%;height:300px;float:left;">
	
</div>
</td>
</tr> 
</table>			
<input type ="hidden" name="flag" value="0">
</form>
