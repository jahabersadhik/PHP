<?php
$phn=$_POST['txtsfrom'];
$phnTo=$_POST['txtsto'];
if($_POST)
{
	//echo "entere";
	$strHost = "$ipaddress";
	$strUser = "$asteriskUsername";
	$strSecret = "$asteriskPassword";
	$strChannel = "SIP/$phn";
	$strContext = "CallSnoopAndCallBarge";
	$strWaitTime = "30";
	$strPriority = "1";
	$strMaxRetry = "2";
	$strExten = "123";
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
			fputs($oSocket, "SetGlobalVar: $phnTo\r\n");
			fputs($oSocket, "Variable: test=$phnTo\r\n");
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
	var url="includes/other/getvalue.php";
	var txtsto;
	if(document.getElementById('txtsto'))
	{
		txtsto = document.getElementById('txtsto').options[document.getElementById('txtsto').selectedIndex].value;
	}
	url=url+"?phn=<?php echo $phn; ?>&txtsto="+txtsto;
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
<form method=post name="frmsnooping" >
<?php
echo "<font color=red><b>$cust_type_message<b></font>";
echo "<font color=red><b>$cust_type_message1<b></font>";
unset($_SESSION['type']);
unset($_SESSION['type1']);
?>
<div style=" height:auto; width:100%; overflow:auto;">
<table border=0 height="290" width="60%" style="margin:auto;" align=center>
<tr height="40"> 
	<td colspan="2" align='center' class="heading">Call Snoop</td>
</tr>
	
<tr>
	<td>
	<div style="width:50%;height:300px;float:left;">
	<?php
		echo "<table><tr><td>
				<label class=\"sublinks\">From Extension</label>
			  </td>
			  <td>
				<input type=\"text\" maxlength=\"4\" name=\"txtsfrom\" value=\"$phn\" id=\"txtsfrom\">
			  </td></tr></table>";
	?>
	</div>
	<div id="txtHint" style="width:50%;height:300px;float:left;"></div>
	</td>
</tr> 
</table>
</div>
