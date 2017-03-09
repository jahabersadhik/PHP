<?php

require_once("mysql_connection.php");
$loginname=$_SESSION['hid']; 
$name=$_SESSION['hidd'];
$cust_cId=$_SESSION['u_Customer_Id'];
$cust_uId=$_SESSION['c_Customer_Id'];

if(!isset($_SESSION['hid']))
{
	header("Location: index.php");// redirect out
	die(); // need this, or the rest of the script executes
}
$expPhpSelf = explode("/",$_SERVER['PHP_SELF']);
$strToAdd = "";
if(!strstr($expPhpSelf[1],"."))
{
	$strToAdd = $expPhpSelf[1]."/";
}

?>
<?php
	//Start of function for getting content between two strings
function find($start,$end,$string)
{
	$startlength = strpos($string,$start)+strlen($start);
	$endlength = strpos($string,$end)-strlen($end);
	$finallength = $endlength - $startlength;
	$final = substr($string,$startlength,$finallength-3);
	return $final;
}
//End of function for getting content between two strings


//Start of Authentication and Initialisation of cookies	
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
/*if($FSERVER == '61.246.55.96')
	$expOutput[1] = $expOutput[0];*/
$expCookeInfo = explode("mansession_id=",$expOutput[1]);
$cookieValue = str_replace('"','',$expCookeInfo[1]);
//echo $_SERVER['SERVER_ADDR'];
setcookie("mansession_id",$cookieValue,time()+3600,"/periisystem/",$_SERVER['SERVER_ADDR']);
curl_close($ch);
//End of Authentication and Initialisation of cookies

//start of fetching users.conf
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
$curlOutput_users = curl_exec($ch);
curl_close($ch);
$curlOutput_users = explode("Category-",$curlOutput_users);
$usersStr="";
for($m = 0;$m<count($curlOutput_users);$m++)
{
	$name = substr($curlOutput_users[$m],8,4);
	if(is_numeric($name) && strlen($name) == 4)
		$usersStr = $usersStr.$name.",";
}
//echo $usersStr;
//end of fetching users.conf


//Start of fetching Meetme.conf
$ch = curl_init();
$testurl = "https://$IPAddress/asterisk/rawman?action=getconfig&filename=meetme.conf";
curl_setopt($ch, CURLOPT_URL, $testurl);
curl_setopt($ch, CURLOPT_HEADER, 1);
@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
$mansession_id = $_COOKIE['mansession_id'];
@curl_setopt($ch, CURLOPT_COOKIE, "mansession_id = ".$cookieValue);
$curlOutput_meetme = curl_exec($ch);
curl_close($ch);
$curlOutput_meetme = str_replace("Line-000000-","",$curlOutput_meetme);
//End of Fetching meetme.conf


//Start of fetching voicemail.conf
$ch = curl_init();
$testurl = "https://$IPAddress/asterisk/rawman?action=getconfig&filename=extensions.conf";
curl_setopt($ch, CURLOPT_URL, $testurl);
curl_setopt($ch, CURLOPT_HEADER, 1);
@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
$mansession_id = $_COOKIE['mansession_id'];
@curl_setopt($ch, CURLOPT_COOKIE, "mansession_id = ".$cookieValue);
$curlOutput_extensions = curl_exec($ch);
curl_close($ch);
$curlOutput_extensions = explode("Line-",$curlOutput_extensions);
$voicemail = "";
for($ln=0;$ln<count($curlOutput_extensions);$ln++)
{
	if(stristr($curlOutput_extensions[$ln],'VoiceMailMain'))
	{
		$newextn = explode(":",$curlOutput_extensions[$ln]);
		/*if(is_numeric(substr(trim(str_replace("exten=","",$curlOutput_extensions[$ln])),0,4)))
			echo substr(trim(str_replace("exten=","",$curlOutput_extensions[$ln])),0,4)."<br>";*/
		if(is_numeric(substr(trim(str_replace("exten=","",$newextn[1])),0,4)))
			$voicemail .= substr(trim(str_replace("exten=","",$newextn[1])),0,4).",";
	}
	
}
//echo $voicemail;


//start Displaying meetme numbers
$arr_meetme = explode(":",$curlOutput_meetme);
$j=0;
$confnum = array();
for($i=11;$i<count($arr_meetme);$i++)
{
	$newarr_meetme = explode("=",trim($arr_meetme[$i]));
	if(strstr($newarr_meetme[1],","))
	{
		$confnum[$j] = $newarr_meetme[1]; 
		$j++;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
<title>PERI iSystem Web Portal</title>
<link href="./css/templatemo_style.css" rel="stylesheet" type="text/css" />
<link href="./css/style_new.css" rel="stylesheet" type="text/css" />
<style>
#templatemo_menu li a:hover, #templatemo_menu li .<?php echo $current; ?>
{
color: #ffffff;
background: url(./images/templatemo_menu_hover.jpg) repeat-x;	
}
</style>
<script language="JavaScript" src="./js/ajaxCreation.js"></script>
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
function stateChanged1()
{
	if (xmlhttp_Update.readyState==4)
  	{
		alert("Updated Successfully");
  		location.href = 'conferencing.php';
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
function editConf()
{
	if(document.getElementById('conference').options[document.getElementById('conference').selectedIndex].value == '-1')
	{
		document.getElementById('editbox').style.display = 'none';
		return;
	}
	else
	{
		document.getElementById('addbox').style.display = 'none';
		document.getElementById('editbox').style.display = 'block';
		var num = document.getElementById('conference').options[document.getElementById('conference').selectedIndex].value;
		var arr = num.split(',');
		document.getElementById('extNo').value = arr[0];
		document.getElementById('extPass').value = arr[1];
		xmlhttp_curl=GetXmlHttpObject();	
		if (xmlhttp_curl==null)
		{
			alert("Your browser does not support XMLHTTP!");
			return;
		}
		var url="conferenceOptions.php";
		url=url+"?phn="+arr[0]+"&pass="+arr[1];
		url=url+"&sid="+Math.random();
		xmlhttp_curl.onreadystatechange=stateChanged;
		xmlhttp_curl.open("GET",url,true);
		xmlhttp_curl.send(null);
	}
}
function ajaxUpdate(mode)
{
	//selection of Options
	//alert(mode);
	if(mode=='add')
	{
		
		if(document.getElementById('achk1').checked)
			var opt1 = 'M';
		else
			var opt1 = '';
		if(document.getElementById('achk2').checked)
			var opt2 = 's';
		else
			var opt2 = '';
		if(document.getElementById('achk3').checked)
			var opt3 = 'I';
		else
			var opt3 = '';
		
		var ext = document.getElementById('aextNo').value;
		var pass = document.getElementById('aextPass').value;
		
		if(isNaN(ext) || ext.length!=4)
		{
			alert("Enter 4 digit Conference Number");
			return;
		}
		if(pass.length<4 & pass.replace(" ","")=="")
		{
			alert("Enter PIN Number");
			return;
		} else if(isNaN(pass))
		{
			alert("Please enter password integer only");
			return;
		}
		var existingNumbers = (document.getElementById('existingNumbers').value).split(",");
		for(var i=0;i<existingNumbers.length;i++)
		{
			if(existingNumbers[i] == ext)
			{
				alert("Number already in the List")
				return;
			}
		}
		xmlhttp_Update=GetXmlHttpObjectUpdate();	
		if (xmlhttp_Update==null)
		{
			alert("Your browser does not support XMLHTTP!");
			return;
		}
		var url="conferenceUpdate.php";
		url = url+"?extn="+ext+"&pass="+pass+"&m="+opt1+"&s="+opt2+"&i="+opt3;
		/*+"&q="+opt4+"&a="+opt5+"&w="+opt6*/
		url=url+"&mode="+mode+"&sid="+Math.random();
		xmlhttp_Update.open("GET",url,true);
		xmlhttp_Update.send(null);
		xmlhttp_Update.onreadystatechange=stateChanged1;
	}
	if(mode=='edit' || mode=='delete')
	{
		if(document.getElementById('chk1').checked)
			var opt1 = 'M';
		else
			var opt1 = '';
		if(document.getElementById('chk2').checked)
			var opt2 = 's';
		else
			var opt2 = '';
		if(document.getElementById('chk3').checked)
			var opt3 = 'I';
		else
			var opt3 = '';
		
		var ext = document.getElementById('extNo').value;
		var pass = document.getElementById('extPass').value;
		if(isNaN(ext) || ext.length!=4)
		{
			alert("Enter 4 digit Conference Number");
			return;
		}
		if(pass.length<4 & pass.replace(" ","")=="")
		{
			alert("Enter PIN Number");
			return;
		} else if(isNaN(pass))
		{
			alert("Please enter password integer only");
			return;
		}
		var existingNumbers = (document.getElementById('existingNumbers').value).split(",");
		if(mode!='delete')
		{
			var validatenum = document.getElementById('conference').options[document.getElementById('conference').selectedIndex].value;
			var validatearr = validatenum.split(',');
			for(var i=0;i<existingNumbers.length;i++)
			{
				
				if(existingNumbers[i] == ext && validatearr[0] != ext)
				{
					alert("Number already in the List")
					return;
				}
			}
		}
		var oldopt1 = document.getElementById('oldchk1').value;
		var oldopt2 = document.getElementById('oldchk2').value;
		var oldopt3 = document.getElementById('oldchk3').value;
		var oldext = document.getElementById('oldextn').value;
		var oldpass = document.getElementById('oldpass').value;
		xmlhttp_Update=GetXmlHttpObjectUpdate();	
		if (xmlhttp_Update==null)
		{
			alert("Your browser does not support XMLHTTP!");
			return;
		}
		var url="conferenceUpdate.php";
		url = url+"?extn="+ext+"&pass="+pass+"&m="+opt1+"&s="+opt2+"&i="+opt3;
		if(mode == 'edit')
		url = url+"&pextn="+oldext+"&ppass="+oldpass+"&pm="+oldopt1+"&ps="+oldopt2+"&pi="+oldopt3;
		url=url+"&mode="+mode+"&sid="+Math.random();
		xmlhttp_Update.onreadystatechange=stateChanged1;
		
		xmlhttp_Update.open("GET",url,true);
		xmlhttp_Update.send(null);
	}
	
}
function viewchange()
{
	document.getElementById('addbox').style.display = 'block';
	document.getElementById('editbox').style.display = 'none';
}
</script>
</head>
<body>
<form name="user">
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
	if($Gtype != 'admin')
		$access = explode("~",$moduleid[4]);
	
?>
	<div id="maincontainer_fullpannel" class="clearfix">
    		<div id="fullpannel">
    			<div class="f_top"><!--&nbsp;--></div>
        		<div class="f_mid">     
	<div style=" height:auto; width:100%; overflow:auto;" id='mainDivLoading'>
	<table width="100%" height="50" align=center>
	<tr>
	    <td align="center" width="60%">
		    <div id="responseText" ></div>
	    </td>
	    <td align="right" colspan="2">
	    	<div class="buttonwrapper" style="width:50%;">
<a class="squarebutton" href="#" onclick="javascript:activate();"><span>Activate Changes</span></a>
</div>
		
	    </td>
	</tr>
	</table>
	<div  STYLE="width:90%;margin:auto; height:340;" class='dialog'>

	<?php
	$numbersArray = "";
	if($access[1][1] == "1" || $Gtype == 'admin')
	{
		$disabled = "";
	}
	else 
	{
		$disabled = "disabled";
	}
	
	echo "<div style='width:50%;float:left;text-aling:right'><strong>Conference Numbers</strong><br>";
	echo "<select class='Mandatorytextbox' name='conference' style='width:150px; height:260px;background:none;border:1px solid #999;' $disabled id='conference' size='25' onchange='editConf()'>";
	echo "<option value='-1' selected>Select Number to Edit</option>";
	for($i=0;$i<count($confnum);$i++)
	{
		echo "<option value='$confnum[$i]' style='padding-left:5px;'>".substr($confnum[$i],0,strpos($confnum[$i],','))."</option>";
		$numbersArray = $numbersArray.",".substr($confnum[$i],0,strpos($confnum[$i],','));
	}
	echo $numbersArray.=$usersStr.$voicemail;
	echo "</select>";
	if($access[1][0] == "1" || $Gtype == 'admin')
	{
		echo "<br><br>
				<div class='buttonwrapper' style='width:85%;'>
					<a class='squarebutton' href='#' id='Add' onclick='viewchange();'><span>New </span></a>
				</div></div>
	";	
	}
	else 
	{
		echo "<br></div>";
	}

	echo "<input type='hidden' id='existingNumbers' name='existingNumbers' value='$numbersArray' />";
	echo "<div style='width:45%;float:left;display:none;' id='editbox'>
			<table border='0' cellpadding='0' cellspacing='10'>
			<tr>
				<td>Conference No:</td><td class='Normalfont'><input type='text' name='extNo' id='extNo'></td>
			</tr>
			<tr>
				<td>PIN No:</td>
				<td><input type='text' name='extPass' id='extPass'></td>
			</tr>
			<tr>
				<td colspan='2'>&nbsp;</td>
			</tr>
			<tr>
				<td colspan='2'>
					<span id='txtHint'></span>
				</td>
			</tr>";
	echo 	"<tr>
				<td colspan='2'>
					<div class='buttonwrapper' style='width:85%;'>
					
					<a class='squarebutton' href='#' id='Edit' onclick='ajaxUpdate(\"edit\")'><span>Save </span></a>
					<a class='squarebutton' href='#' id='Delete' onclick='ajaxUpdate(\"delete\")' style='margin-left:10px;'><span>Delete </span></a>
				</div>
					
					
				</td>
			</tr>";
	echo 	"</table>
		 </div>";
	echo "<div style='width:45%;float:left;display:none;' id='addbox'>
			<table>
			<tr>
				<td>Conference No:</td><td class='Normalfont'><input type='text' name='aextNo' id='aextNo'></td>
			</tr>
			<tr>
				<td>PIN No:</td>
				<td ><input type='text' name='aextPass' id='aextPass'></td>
			</tr>
			<tr>
				<td colspan='2'>&nbsp;</td>
			</tr>
			<tr>
				<td colspan='2' class='Normalfont'>
		  		  <input type='checkbox' name='achk1' id='achk1' />&nbsp;Play Hold Music for First Caller<br><br>
				  <input type='checkbox' name='achk2' id='achk2' />&nbsp;Enable caller menu<br><br>
				  <input type='checkbox' name='achk3' id='achk3' />&nbsp;Announce Callers<br><br>
				 </td>
			</tr>";
	echo 	"<tr>
				<td colspan='2'>
					
					<div class='buttonwrapper' style='width:60%;'>
					<a class='squarebutton' href='#' id='Add' onclick='ajaxUpdate(\"add\");'><span>Save </span></a>
					</div>
				</td>
			</tr>";
	echo 	"</table>
		 </div>";

//end of displaying meetme numbers

?>
</div>
</div>
</div>
	<div class="f_bottom"></div>
	</div>
    </div>
<?php include('footer.php');?>
</form>

</body>
</html>


