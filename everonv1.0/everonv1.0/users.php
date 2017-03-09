<?php
require_once("mysql_connection.php");

if(!isset($_SESSION['hid']))
{
	header("Location: index.php");// redirect out
	die(); // need this, or the rest of the script executes
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
$curlOutput = curl_exec($ch);
$expOutput = explode(";",$curlOutput);
$expResponse = explode("chunked",$expOutput[3]);
$finalResult['response']=$expResponse[1];
/*if($_SESSION['userIp'] == '61.246.55.96')
	$finalResult['response'] = $expOutput[2];*/
$file2 = explode("\n",$finalResult['response']);
curl_close($ch);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
<title>PERI iSystem Web Portal</title>
<!--<link href="./css/templatemo_style.css" rel="stylesheet" type="text/css" />-->
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
    function voicemailoption() {

        /*if (document.users.hasvoicemail.checked == true) {
            // alert(document.user.hasvoicemail.checked);
            alert("Provide Voice Mail Password");
            document.getElementById("secret1").style.display = 'block';
	     document.getElementById("sec1").style.display = 'block';
            //document.getElementById("secret2").style.display = 'block';
            document.user.vmsecret.focus();
        }
        else {
            document.getElementById("secret1").style.display = 'block';
	     document.getElementById("sec1").style.display = 'block';
            //document.getElementById("secret2").style.display = 'block';  
        }*/
    }
    function sipoption() 
    {
        if ((document.users.hassip.checked == true)) 
        {
        	if(document.users.secret.value=='')
            	alert("Provide SIP Password");
            document.getElementById("pass1").style.display = 'block';
	     	document.getElementById("pass12").style.display = 'block';
            document.users.secret.focus();
        }
        else 
        {
            document.getElementById("pass1").style.display = 'block';
	     	document.getElementById("pass12").style.display = 'block';
        }
    }
    function deleteuser() 
    {
        if (document.users.usersvalue.value == "") 
        {
            alert("Select User from the List");
            document.users.usersvalue.focus();
        }
        else 
        {
            if (confirm("Are you sure you want to Delete") == true) 
            {
                document.users.method = "post";
                document.users.action = "./deleteUser_Conf.php";
                document.users.submit();
            }
        }
    }
   
   
    
    function newUser() 
    {	
    	document.getElementById('responseText').innerHTML = "<font color='green'>Create New User<br>Provide Needed Information</font>";
		setTimeout('document.getElementById(\'responseText\').innerHTML = \"\";location.href=location.href;',3000);
    }
	function editUserExtension() 
	{	
	    if (document.users.usersvalue.value == "") {
	        alert("Select User from the List");
	        document.users.usersvalue.focus();
	    }else{
	    	selectedExtension = document.getElementById("usersvalue").value;
	       	var xmlhttp = ajaxFunction();
			xmlhttp.onreadystatechange=function()
			{
				if(xmlhttp.readyState==4){
					var checkboxArr = document.getElementsByTagName('input');
				    for (i = 0; i < checkboxArr.length; i++) {
				         if (checkboxArr[i].type == 'checkbox'){
				               checkboxArr[i].checked = false;
				         }
				         if(checkboxArr[i].type == 'text'){
				         	checkboxArr[i].value = "";
				         }
				    }
				    document.getElementById("context").value = "";
				    var userExtensionInformationArr = xmlhttp.responseText;
					sptUserExtensionInformation = userExtensionInformationArr.split("~");
					sptUserExtensionKeyArr = sptUserExtensionInformation[0].split("^");
					sptUserExtensionValueArr = sptUserExtensionInformation[1].split("^");
					document.getElementById("extension").value = document.getElementById("usersvalue").value;
					document.getElementById("userOperation").value = 'edit';
					for(i_Inc=0;i_Inc<sptUserExtensionKeyArr.length;i_Inc++){
						if(trim(sptUserExtensionKeyArr[i_Inc]) == 'callwaiting'){
							if(trim(sptUserExtensionValueArr[i_Inc]) == 'yes'){
								if(document.getElementById("callwaiting")){
									document.getElementById("callwaiting").checked = true;
									document.getElementById("callwaiting").value = "yes";
								}
							}else{
								if(document.getElementById("callwaiting")){
									document.getElementById("callwaiting").checked = false;
									document.getElementById("callwaiting").value = "no";
								}
							}
						}else if(trim(sptUserExtensionKeyArr[i_Inc]) == 'fullname'){
							document.getElementById("fullname").value = trim(sptUserExtensionValueArr[i_Inc]);
						}else if(trim(sptUserExtensionKeyArr[i_Inc]) == 'hasagent'){
							if(trim(sptUserExtensionValueArr[i_Inc]) == 'yes'){
								if(document.getElementById("hasagent")){
									document.getElementById("hasagent").checked = true;
									document.getElementById("hasagent").value = "yes";
								}
							}else{
								if(document.getElementById("hasagent")){
									document.getElementById("hasagent").checked = false;
									document.getElementById("hasagent").value = "no";
								}
							}
						}else if(trim(sptUserExtensionKeyArr[i_Inc]) == 'hasdirectory'){
							if(trim(sptUserExtensionValueArr[i_Inc]) == 'yes'){
								if(document.getElementById("hasdirectory")){
									document.getElementById("hasdirectory").checked = true;
									document.getElementById("hasdirectory").value = "yes";
								}
							}else{
								if(document.getElementById("hasdirectory")){
									document.getElementById("hasdirectory").checked = false;
									document.getElementById("hasdirectory").value = "no";
								}
							}
						}else if(trim(sptUserExtensionKeyArr[i_Inc]) == 'hasiax'){
							if(trim(sptUserExtensionValueArr[i_Inc]) == 'yes'){
								if(document.getElementById("hasiax")){
									document.getElementById("hasiax").checked = true;
									document.getElementById("hasiax").value = "yes";
								}
							}else{
								if(document.getElementById("hasiax")){
									document.getElementById("hasiax").checked = false;
									document.getElementById("hasiax").value = "no";
								}
							}
						}else if(trim(sptUserExtensionKeyArr[i_Inc]) == 'hasmanager'){
							if(trim(sptUserExtensionValueArr[i_Inc]) == 'yes'){
								if(document.getElementById("hasmanager")){
									document.getElementById("hasmanager").checked = true;
									document.getElementById("hasmanager").value = "yes";
								}
							}else{
								if(document.getElementById("hasmanager")){
									document.getElementById("hasmanager").checked = false;
									document.getElementById("hasmanager").value = "no";
								}
							}
						}else if(trim(sptUserExtensionKeyArr[i_Inc]) == 'hassip'){
							if(trim(sptUserExtensionValueArr[i_Inc]) == 'yes'){
								if(document.getElementById("hassip")){
									document.getElementById("hassip").checked = true;
									document.getElementById("hassip").value = "yes";
								}
							}else{
								if(document.getElementById("hassip")){
									document.getElementById("hassip").checked = false;
									document.getElementById("hassip").value = "no";
								}
							}
						}else if(trim(sptUserExtensionKeyArr[i_Inc]) == 'hasvoicemail'){
							if(trim(sptUserExtensionValueArr[i_Inc]) == 'yes'){
								if(document.getElementById("hasvoicemail")){
									document.getElementById("hasvoicemail").checked = true;
									document.getElementById("hasvoicemail").value = "yes";
								}
							}else{
								if(document.getElementById("hasvoicemail")){
									document.getElementById("hasvoicemail").checked = false;
									document.getElementById("hasvoicemail").value = "no";
								}
							}
						}else if(trim(sptUserExtensionKeyArr[i_Inc]) == 'deletevoicemail'){
							if(trim(sptUserExtensionValueArr[i_Inc]) == 'yes'){
								if(document.getElementById("deletevoicemail")){
									document.getElementById("deletevoicemail").checked = true;
									document.getElementById("deletevoicemail").value = "yes";
								}
							}else{
								if(document.getElementById("deletevoicemail")){
									document.getElementById("deletevoicemail").checked = false;
									document.getElementById("deletevoicemail").value = "no";
								}
							}
						}else if(trim(sptUserExtensionKeyArr[i_Inc]) == 'mailbox'){
							if(trim(sptUserExtensionValueArr[i_Inc]) == 'yes'){
								if(document.getElementById("mailbox")){
									document.getElementById("mailbox").checked = true;
									document.getElementById("mailbox").value = "yes";
								}
							}else{
								if(document.getElementById("mailbox")){
									document.getElementById("mailbox").checked = false;
									document.getElementById("mailbox").value = "no";
								}
							}
						}else if(trim(sptUserExtensionKeyArr[i_Inc]) == 'threewaycalling'){
							if(trim(sptUserExtensionValueArr[i_Inc]) == 'yes'){
								if(document.getElementById("threewaycalling")){
									document.getElementById("threewaycalling").checked = true;
									document.getElementById("threewaycalling").value = "yes";
								}
							}else{
								if(document.getElementById("threewaycalling")){
									document.getElementById("threewaycalling").checked = false;
									document.getElementById("threewaycalling").value = "no";
								}
							}
						}else if(trim(sptUserExtensionKeyArr[i_Inc]) == 'vmsecret'){
							document.getElementById("vmsecret").value = trim(sptUserExtensionValueArr[i_Inc]);
						}else if(trim(sptUserExtensionKeyArr[i_Inc]) == 'registeriax'){
							if(trim(sptUserExtensionValueArr[i_Inc]) == 'yes'){
								if(document.getElementById("registeriax")){
									document.getElementById("registeriax").checked = true;
									document.getElementById("registeriax").value = "yes";
								}
							}else{
								if(document.getElementById("registeriax")){
									document.getElementById("registeriax").checked = false;
									document.getElementById("registeriax").value = "no";
								}
							}
						}else if(trim(sptUserExtensionKeyArr[i_Inc]) == 'registersip'){
							if(trim(sptUserExtensionValueArr[i_Inc]) == 'yes'){
								if(document.getElementById("registersip")){
									document.getElementById("registersip").checked = true;
									document.getElementById("registersip").value = "yes";
								}
							}else{
								if(document.getElementById("registersip")){
									document.getElementById("registersip").checked = false;
									document.getElementById("registersip").value = "no";
								}
							}
						}else if(trim(sptUserExtensionKeyArr[i_Inc]) == 'autoprov'){
							if(trim(sptUserExtensionValueArr[i_Inc]) == 'yes'){
								if(document.getElementById("autoprov")){
									document.getElementById("autoprov").checked = true;
									document.getElementById("autoprov").value = "yes";
								}
							}else{
								if(document.getElementById("autoprov")){
									document.getElementById("autoprov").checked = false;
									document.getElementById("autoprov").value = "no";
								}
							}
						}else if(trim(sptUserExtensionKeyArr[i_Inc]) == 'canreinvite'){
							if(trim(sptUserExtensionValueArr[i_Inc]) == 'yes'){
								if(document.getElementById("canreinvite")){
									document.getElementById("canreinvite").checked = true;
									document.getElementById("canreinvite").value = "yes";
								}
							}else{
								if(document.getElementById("canreinvite")){
									document.getElementById("canreinvite").checked = false;
									document.getElementById("canreinvite").value = "no";
								}
							}
						}else if(trim(sptUserExtensionKeyArr[i_Inc]) == 'nat'){
							if(trim(sptUserExtensionValueArr[i_Inc]) == 'yes'){
								if(document.getElementById("nat")){
									document.getElementById("nat").checked = true;
									document.getElementById("nat").value = "yes";
								}
							}else{
								if(document.getElementById("nat")){
									document.getElementById("nat").checked = false;
									document.getElementById("nat").value = "no";
								}
							}
						}else if(trim(sptUserExtensionKeyArr[i_Inc]) == 'cti'){
							if(trim(sptUserExtensionValueArr[i_Inc]) == 'yes'){
								if(document.getElementById("cti")){
									document.getElementById("cti").checked = true;
									document.getElementById("cti").value = "yes";
								}
							}else{
								if(document.getElementById("cti")){
									document.getElementById("cti").checked = false;
									document.getElementById("cti").value = "no";
								}
							}
						}else if(trim(sptUserExtensionKeyArr[i_Inc]) == 'secret'){
							document.getElementById("secret").value = trim(sptUserExtensionValueArr[i_Inc]);
						}else if(trim(sptUserExtensionKeyArr[i_Inc]) == 'signalling'){
							if(trim(sptUserExtensionValueArr[i_Inc]) == 'yes'){
								if(document.getElementById("signalling")){
									document.getElementById("signalling").checked = true;
									document.getElementById("signalling").value = "yes";
								}
							}else{
								if(document.getElementById("signalling")){
									document.getElementById("signalling").checked = false;
									document.getElementById("signalling").value = "no";
								}
							}
						}else if(trim(sptUserExtensionKeyArr[i_Inc]) == 'context'){
							document.getElementById("context").value = trim(sptUserExtensionValueArr[i_Inc]);
						}else if(trim(sptUserExtensionKeyArr[i_Inc]) == 'dtmfmode'){
							if(trim(sptUserExtensionValueArr[i_Inc]) == 'yes'){
								if(document.getElementById("dtmfmode")){
									document.getElementById("dtmfmode").checked = true;
									document.getElementById("dtmfmode").value = "yes";
								}
							}else{
								if(document.getElementById("dtmfmode")){
									document.getElementById("dtmfmode").checked = false;
									document.getElementById("dtmfmode").value = "no";
								}
							}
						}else if(trim(sptUserExtensionKeyArr[i_Inc]) == 'disallow'){
							if(trim(sptUserExtensionValueArr[i_Inc]) == 'yes'){
								if(document.getElementById("disallow")){
									document.getElementById("disallow").checked = true;
									document.getElementById("disallow").value = "all";
								}
							}else{
								if(document.getElementById("disallow")){
									document.getElementById("disallow").checked = false;
									document.getElementById("disallow").value = "all";
								}
							}
						}else if(trim(sptUserExtensionKeyArr[i_Inc]) == 'allow'){
							if(trim(sptUserExtensionValueArr[i_Inc]) == 'yes'){
								if(document.getElementById("allow")){
									document.getElementById("allow").checked = true;
									document.getElementById("allow").value = "all";
								}
							}else{
								if(document.getElementById("allow")){
									document.getElementById("allow").checked = false;
									document.getElementById("allow").value = "all";
								}
							}
						}else if(trim(sptUserExtensionKeyArr[i_Inc]) == 'linenumber'){
							if(document.getElementById("linenumber"))
							document.getElementById("linenumber").value = trim(sptUserExtensionValueArr[i_Inc]);
						}else if(trim(sptUserExtensionKeyArr[i_Inc]) == 'email'){
							document.getElementById("email").value = trim(sptUserExtensionValueArr[i_Inc]);
						}else if(trim(sptUserExtensionKeyArr[i_Inc]) == 'cid_number'){
							document.getElementById("cid_number").value = trim(sptUserExtensionValueArr[i_Inc]);
						}
					}
					if(xmlhttp.responseText){
				  		document.getElementById('responseText').innerHTML = "";
					}else{
						document.getElementById('responseText').innerHTML = "";
					}
			}
		}
		var url = "controlUserExtension.php";  
		var parameters = "action=edit&task=users&selectedExtension="+selectedExtension;  
		xmlhttp.open("GET", url+"?"+parameters, true);
		xmlhttp.send(null);
    }
}

function saveUserExtension() {	
   	var xmlhttp = ajaxFunction();
	xmlhttp.onreadystatechange=function()
	{
		if(xmlhttp.readyState==4){
			if(xmlhttp.responseText){
		  		document.getElementById('responseText').innerHTML = "<font color='green'>User Extension "+xmlhttp.responseText+" Successfully</font>";
		  		setTimeout('document.getElementById(\'responseText\').innerHTML = \"\";location.href=\"users.php\";',3000);
			}else{
				document.getElementById('responseText').innerHTML = "<font color='red'>User Extension Saving Failed</font>";
				setTimeout('document.getElementById(\'responseText\').innerHTML = \"\"',3000);
			}
		}
	}
	var parameters = "action=save&task=users&userOperation="+document.getElementById("userOperation").value+"&extension="+document.getElementById("extension").value;  
	if(document.getElementById("callwaiting")){
		if(document.getElementById("callwaiting").checked == true){
			document.getElementById("callwaiting").value = 'yes';
		}else{
			document.getElementById("callwaiting").value = 'no';
		}
		callwaiting = document.getElementById("callwaiting").value;
		parameters += "&callwaiting="+callwaiting;
	}
	if(document.getElementById("hasagent")){
		if(document.getElementById("hasagent").checked == true){
			document.getElementById("hasagent").value = 'yes';
		}else{
			document.getElementById("hasagent").value = 'no';
		}
		hasagent = document.getElementById("hasagent").value;
		parameters += "&hasagent="+hasagent;
	}
	if(document.getElementById("fullname")){
		fullname = document.getElementById("fullname").value;
		parameters += "&fullname="+fullname;
	}
	if(document.getElementById("secret")){
		secret = document.getElementById("secret").value;
		parameters += "&secret="+secret;
	}
	if(document.getElementById("vmsecret")){
		vmsecret = document.getElementById("vmsecret").value;
		parameters += "&vmsecret="+vmsecret; 
	}
	if(document.getElementById("hasdirectory")){
		if(document.getElementById("hasdirectory").checked == true){
			document.getElementById("hasdirectory").value = 'yes';
		}else{
			document.getElementById("hasdirectory").value = 'no';
		}
		hasdirectory = document.getElementById("hasdirectory").value;
		parameters += "&hasdirectory="+hasdirectory;
	}
	if(document.getElementById("hasiax")){
		if(document.getElementById("hasiax").checked == true){
			document.getElementById("hasiax").value = 'yes';
		}else{
			document.getElementById("hasiax").value = 'no';
		}
		hasiax = document.getElementById("hasiax").value;
		parameters += "&hasiax="+hasiax;
	}
	if(document.getElementById("hasmanager")){
		if(document.getElementById("hasmanager").checked == true){
			document.getElementById("hasmanager").value = 'yes';
		}else{
			document.getElementById("hasmanager").value = 'no';
		}
		hasmanager = document.getElementById("hasmanager").value;
		parameters += "&hasmanager="+hasmanager;
	}
	if(document.getElementById("hassip")){
		if(document.getElementById("hassip").checked == true){
			document.getElementById("hassip").value = 'yes';
		}else{
			document.getElementById("hassip").value = 'no';
		}
		hassip = document.getElementById("hassip").value;
		parameters += "&hassip="+hassip;
	}
	if(document.getElementById("hasvoicemail")){
		if(document.getElementById("hasvoicemail").checked == true){
			document.getElementById("hasvoicemail").value = 'yes';
		}else{
			document.getElementById("hasvoicemail").value = 'no';
		}
		hasvoicemail = document.getElementById("hasvoicemail").value;
		parameters += "&hasvoicemail="+hasvoicemail;
	}
	if(document.getElementById("deletevoicemail")){
		if(document.getElementById("deletevoicemail").checked == true){
			document.getElementById("deletevoicemail").value = 'yes';
		}else{
			document.getElementById("deletevoicemail").value = 'no';
		}
		deletevoicemail = document.getElementById("deletevoicemail").value;
		parameters += "&deletevoicemail="+deletevoicemail;
	}
	if(document.getElementById("mailbox")){
		if(document.getElementById("mailbox").checked == true){
			document.getElementById("mailbox").value = 'yes';
		}else{
			document.getElementById("mailbox").value = 'no';
		}
		mailbox = document.getElementById("mailbox").value;
		parameters += "&mailbox="+mailbox;
	}
	if(document.getElementById("threewaycalling")){
		if(document.getElementById("threewaycalling").checked == true){
			document.getElementById("threewaycalling").value = 'yes';
		}else{
			document.getElementById("threewaycalling").value = 'no';
		}
		threewaycalling = document.getElementById("threewaycalling").value;
		parameters += "&threewaycalling="+threewaycalling;
	}
	if(document.getElementById("registeriax")){
		if(document.getElementById("registeriax").checked == true){
			document.getElementById("registeriax").value = 'yes';
		}else{
			document.getElementById("registeriax").value = 'no';
		}
		registeriax = document.getElementById("registeriax").value;
		parameters += "&registeriax="+registeriax;
	}
	if(document.getElementById("registersip")){
		if(document.getElementById("registersip").checked == true){
			document.getElementById("registersip").value = 'yes';
		}else{
			document.getElementById("registersip").value = 'no';
		}
		registersip = document.getElementById("registersip").value;
		parameters += "&registersip="+registersip;
	}
	if(document.getElementById("autoprov")){
		if(document.getElementById("autoprov").checked == true){
			document.getElementById("autoprov").value = 'yes';
		}else{
			document.getElementById("autoprov").value = 'no';
		}
		autoprov = document.getElementById("autoprov").value;
		parameters += "&autoprov="+autoprov;
	}
	if(document.getElementById("canreinvite")){
		if(document.getElementById("canreinvite").checked == true){
			document.getElementById("canreinvite").value = 'yes';
		}else{
			document.getElementById("canreinvite").value = 'no';
		}
		canreinvite = document.getElementById("canreinvite").value;
		parameters += "&canreinvite="+canreinvite;
	}
	if(document.getElementById("nat")){
		if(document.getElementById("nat").checked == true){
			document.getElementById("nat").value = 'yes';
		}else{
			document.getElementById("nat").value = 'no';
		}
		nat = document.getElementById("nat").value;
		parameters += "&nat="+nat
	}
	if(document.getElementById("cti")){
		if(document.getElementById("cti").checked == true){
			document.getElementById("cti").value = 'yes';
		}else{
			document.getElementById("cti").value = 'no';
		}
		cti = document.getElementById("cti").value;
		parameters += "&cti="+cti
	}
	if(document.getElementById("context")){
		context = document.getElementById("context").value;
		var contextname = document.getElementById(context).value;
		parameters += "&context="+context+"&contextname="+contextname;
	}
	if(document.getElementById("dtmfmode")){
		dtmfmode = document.getElementById("dtmfmode").value;
		parameters += "&dtmfmode="+dtmfmode;
	}
	if(document.getElementById("disallow")){
		disallow = document.getElementById("disallow").value;
		parameters += "&disallow="+disallow;
	}
	if(document.getElementById("allow")){
		allow = document.getElementById("allow").value;
		parameters += "&allow="+allow;
	}
	if(document.getElementById("linenumber")){
		linenumber = document.getElementById("linenumber").value;
		parameters += "&linenumber="+linenumber;
	}
	if(document.getElementById("email")){
		email = document.getElementById("email").value;
		parameters += "&email="+email;
	}
	if(document.getElementById("cid_number")){
		cid_number = document.getElementById("cid_number").value;
		parameters += "&cid_number="+cid_number;
	}

	var url = "controlUserExtension.php";  
	xmlhttp.open("GET", url+"?"+parameters, true);
	xmlhttp.send(null);
}

function deleteUserExtension() {	
	if (confirm("Are you sure you want to Delete") == true) {
	   	var xmlhttp = ajaxFunction();
		xmlhttp.onreadystatechange=function()
		{
			if(xmlhttp.readyState==4){
				if(xmlhttp.responseText){
			  		document.getElementById('responseText').innerHTML = "<font color='green'>User Extension Deleted Successfully</font>";
			  		setTimeout('document.getElementById(\'responseText\').innerHTML = \"\";location.href=\"users.php\";',3000);
				}else{
					document.getElementById('responseText').innerHTML = "<font color='red'>User Extension Deletion Failed</font>";
					setTimeout('document.getElementById(\'responseText\').innerHTML = \"\"',3000);
				}
			}
		}
		var url = "controlUserExtension.php";  
		var parameters = "action=delete&task=users&userOperation="+document.getElementById("userOperation").value+"&extension="+document.getElementById("usersvalue").value;  
		xmlhttp.open("GET", url+"?"+parameters, true);
		xmlhttp.send(null);
	}
}
function exportExtenstion()
{
	location.href='export_extenson.php';
}
</script>
</head>
<body>
<form name="users">
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
		$access = explode("~",$moduleid[1]);
		
?>
<div id="maincontainer_fullpannel" class="clearfix">
    <div id="fullpannel">
    	<div class="f_top"><!--&nbsp;--></div>
        <div class="f_mid">     
			<table width="100%">
	<tr>
	   <td width=100% valign=top>
	    <table class="mainscreenTable" align="center" width="100%" border=0>
	    	<tr><td align="center" colspan="3"><div id="responseText"></div></td></tr>
	    	<tr><td align="right" colspan="3">
	    		<div class="buttonwrapper">
<a class="squarebutton" href="#" onclick="javascript:activate();"><span>Activate Changes</span></a>
</div>
		</td></tr>
			<tr valign="top">
				<td align=center><strong>User Extensions:</strong> </td>
				<td align=center>&nbsp;</td>
				<td align=center>&nbsp;</td>
			</tr>
			
			<tr valign="top" >
				<td width="50%" align=center >
					<?php
					$j=0;
					for($i=0;$i<count($file);$i++){
						if(strstr($file[$i],"Category-")){
							$extension=substr(trim($file[$i]),-5);
							if(!is_numeric(trim($extension)))
							$extension=substr(trim($file[$i]),-4);
							$userArr[$j]['exten']=$extension;
							$userExtensionArr[$j] = trim($extension);
						}
						if(strstr($file[$i],": fullname")){
							$fullname=substr(trim($file[$i]),29);
							$userArr[$j]['fullname']=$fullname;
							$userFullnameArr[$j] = $fullname;
							$j++;
						}
					}
					asort($userExtensionArr);
					
					$j_Int=0;$k_Int=0;$l_Int=0;
					for($i_Int=0;$i_Int<count($file2);$i_Int++){
						$strPlanName="";
						if(strstr($file2[$i_Int]," DLPN_")){
							$strDialPlan = explode(":",trim($file2[$i_Int]));
							if(strstr($file2[$i_Int+1],"Plancomment")){
								$strDialPlanName = explode("Plancomment=",trim($file2[$i_Int+1]));
								$strPlanName = $strDialPlanName[1];
							}
							$dialPlan=str_replace("DLPN_","",trim($strDialPlan[1]) );
							$dialPlan1 = "Dial Plan";
							if($strPlanName != ""){
					
								$userDialplanArr[$j_Int] = $dialPlan1." - ".$strPlanName;
							}else{
								$userDialplanArr[$j_Int] = $dialPlan;
							}
							$userDialplanValueArr[$j_Int] = trim($strDialPlan[1]);
							$j_Int++;
						}
						
						if(strstr($file2[$i_Int],"VoicemailMain")){
							$strVoiceMail = explode(":",trim($file2[$i_Int]));
							$voiceMailValue=str_replace("exten=","",trim($strVoiceMail[1]) );
							if(!strstr((trim(substr(trim($voiceMailValue),0,4))),",")){
								$userVoiceMailValueArr[$k_Int] = substr(trim($voiceMailValue),0,4);
								$k_Int++;
							}
						}
						if(strstr($file2[$i_Int],"MeetMe")){
							$strMeetMe = explode(":",trim($file2[$i_Int]));
							$MeetMeValue=str_replace("exten=","",trim($strMeetMe[1]) );
							if(!strstr((trim(substr(trim($MeetMeValue),0,4))),",")){
								$userMeetMeArr[$l_Int] = substr(trim($MeetMeValue),0,4);
								$l_Int++;
							}
						}
					}
					
					?>
			 		<select size="25" id="usersvalue" style="width:250px; height:420px;background:none;border:1px solid #999;" <?php if($access[1][1] == "0"){ echo "disabled";} ?>  name="usersvalue" onchange="javascript:editUserExtension();" class="Mandatorytextbox">
						<option value="0" style="color:Grey;background:none;"> -Select User- </option>
						<?php
						foreach($userExtensionArr as $extenKey => $extenValue){	
							if(trim($userExtensionArr[$extenKey]) != "" && trim($userFullnameArr[$extenKey]) != "" && is_numeric(trim($userExtensionArr[$extenKey]))){
							?>   <option style="color:Green" value="<?php echo trim($userExtensionArr[$extenKey]);?>" ><?php echo trim($userExtensionArr[$extenKey])."--".$userFullnameArr[$extenKey];?></option> 
						<?php	
							}
						}
			?>
			    	</select>
				</td>
				
				<td style="width:100px;" align=center>
			   		<table cellspacing='0' cellpadding='0'  border=0>
						<tr valign="top">
							<td align="center">
								<table border=0 width=100% cellpadding=0 cellspacing="10">
									<tr>
									    <td tip="en,users,1" style="display:block" class="label_class" id="NameTrue">
									        <label id="NameLbl" class="label_class"> Name:</label>
									    </td>
									    <td>
									        <input class="textbox_bg" size='20' maxlength=50 id='fullname' pattern='^[a-zA-Z_0-9]*$' class="NormalTextBox" name="fullname" onfocus="this.style.backgroundColor='#E2E2E2'" onblur="this.style.backgroundColor='white'">
									    </td>
									</tr>
									<tr>
									    <td id="ExtFalse" tip="en,users,0" style="display:block"  class="label_class">
									        <label id="ExtLbl"> Extension:</label>
						
						<input type="hidden" name="Conf_Ext" id="Conf_Ext" value="<?php echo implode(",",$userMeetMeArr);?>">
						<input type="hidden" name="User_Ext" id="User_Ext" value="<?php echo implode(",",$userExtensionArr);?>">
						<input type="hidden" name="Voice_Ext" id="Voice_Ext" value="<?php echo implode(",",$userVoiceMailValueArr);?>">
						<input type="hidden" name="userOperation" id="userOperation" value="new">
						
									    </td>
									    <td>
									        <input class="textbox_bg"  maxlength=4 id='extension' pattern='?[0-9]+' class=NormalTextBox name="extension" readonly onfocus="this.style.backgroundColor='#E2E2E2'" onblur="javascript:isInteger(this);">
									    </td>
									</tr>
									<tr id="ExtExists" style="display:none;">
									    <td colspan=2 align=right> 
									        <font color=red size=-1>* Not Available</font>
									    </td>
									</tr>
									<tr>
									    <td  tip="en,users,2" style="display:block" id="PassTrue">
											<table border=0 cellpadding=0 cellspacing=0>
												<tr tip="en,users,16" id="pass1" style="display:block;">
												<td>
									        		<label id="PassLbl">SIP Password:</label>
												</td>
												</tr>
											</table>
										</td>
										<td>
											<table border=0 cellpadding=0 cellspacing=0>
												<tr id="pass12" style="display:block;">
												<td tip="en,users,16">
										        	<input class="textbox_bg"  type=text   id='secret'  class="NormalTextBox" pattern='^[a-zA-Z_0-9]*$'   name="secret" onfocus="this.style.backgroundColor='#E2E2E2'" onblur="this.style.backgroundColor='white'">
												</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
									    <td tip="en,users,16" style="display:block" id="VMPassTrue">
										    <table border=0 cellpadding=0 cellspacing=0>
												<tr tip="en,users,16" id="secret1" style="display:block;">
													<td><label id="VMPassLbl">VM Password:</label></td>
												</tr>
											</table>
									    </td>
									    <td>
											<table border=0 cellpadding=0 cellspacing=0>
											<tr tip="en,users,16" id="sec1" style="display:block;">
											<td tip="en,users,16" >
												        <input class="textbox_bg"  type=text size='20' id='vmsecret'   class="NormalTextBox" pattern='^[0-9*]*$'   name="vmsecret"  onfocus="this.style.backgroundColor='#E2E2E2'" onblur="this.style.backgroundColor='white'">
											</td>
											</tr>
											</table>
									    </td>
									</tr>
									<tr style="display:none;">
									    <td tip="en,users,25" style="display:block" id="emailTrue">
									        <label id="NameLbl"> Email:</label>
									    </td>
									    <td>
									        <input class="textbox_bg"  size='20' id='email'  class="NormalTextBox" pattern='^[0-9a-zA-Z\.\-\_\@]*$'  name="email">
									    </td>
									</tr>
									
									<tr style="display:none;">
									    <td tip="en,users,26" style="display:block" id="callerIdTrue">
									        <label id="NameLbl"> Caller Id:</label>
									    </td>
									    <td>
									       <input class="textbox_bg"  size='20' id='cid_number'  pattern='^[\d\-]*$' class="NormalTextBox" name="cid_number" onfocus="this.style.backgroundColor='#E2E2E2'" onblur="this.style.backgroundColor='white'">
									    </td>
									</tr>
									<?php //print_r($userDialplanArr); ?>
									<tr>
										<td>Dial Plan:</td>
									    <td><select class="textbox_bg"  size='1' id='context' style='width:150px;background:#fff;border:1px solid #999;' class="DropDownBox" name="context">
										    <!--<option value="" >Select Dial Plan</option>-->
											<?php
											foreach($userDialplanArr as $dialPlanKey => $dialPlanValue)
											{
												if(trim($userDialplanArr[$dialPlanKey])!='')
												{
											?>
											
							            	<option value="<?php echo trim($userDialplanValueArr[$dialPlanKey]);?>" ><?php echo trim($userDialplanArr[$dialPlanKey]);?> </option>
											<?php
												}
											}
											?>
										</select>
										<?php
										//For adding the Dial PLan comment in the Access Log DB
										foreach($userDialplanArr as $dialPlanKey => $dialPlanValue)
										{
											echo "<input type='hidden' name='".trim($userDialplanValueArr[$dialPlanKey])."' value='".trim($userDialplanArr[$dialPlanKey])."' id='".trim($userDialplanValueArr[$dialPlanKey])."' />";
										}
										//
										?>
										</td>
									</tr>
									</table>
								</td>
							</tr>
				<tr>
					<td align="center">
					<div class="buttonwrapper" style="width:20%;margin:5px auto;padding-right:110px;">
<a class="squarebutton" href="#" id='save' onclick="validate();"><span>Save </span></a>
</div>
					<fieldset style="width:280px;visibility:hidden;">
					<legend>&nbsp;<font color="Blue"> Extension Options:&nbsp;</font></legend>
					<table align='center' width=270 cellpadding=1 border=0 cellspacing=5>
					<tr>
						<td width=40 align=right><input type='checkbox' id='hasvoicemail' onclick="javascript:voicemailoption();" name="hasvoicemail"></td >
						<td tip="en,users,8">Voicemail</td>
						<td align=right><input type='checkbox' id='hasdirectory' name="hasdirectory"></td>
						<td tip="en,users,9">In Directory</td>
					</tr>
					
					<tr>
						<td align=right><input type='checkbox' onclick="javascript:sipoption();" id='hassip' name="hassip"></td>
						<td tip="en,users,10" style="display:block" id="SIPTrue"><label id="SIPLbl">SIP</label></td>
						<td align=right><input type='checkbox' id='hasiax' name="hasiax"></td>
						<td tip="en,users,11">IAX</td>
					</tr>
					<tr>
						<td align=right><input type='checkbox' id='hasagent' name="hasagent"></td>
						<td tip="en,users,23" style="display:block" id="agentTrue"><label id="SIPLbl">Is Agent</label></td>
						<td align=right><input type='checkbox' id='cti' name="cti"></td>
						<td tip="en,users,24">CTI</td>
					</tr>
					<tr>
						<td align=right> <input type='checkbox' id='registersip' name='registersip'></td>
						<td tip="en,users,23" style="display:block" id="agentTrue"><label id="SIPLbl">Register SIP</label></td>
						<td align=right> <input type='checkbox' id='registeriax' name='registeriax'></td>
						<td tip="en,users,23" style="display:block" id="agentTrue"><label id="SIPLbl">Register IAX</label></td>
						<input type='hidden' id='mailbox'><input type='hidden' id='group'>
					</tr>				
					<tr>
						<td align=right><input type='checkbox' id='hasmanager' name="hasmanager"></td>
						<td tip="en,users,12">Has Manager</td>
						<td align=right>
							<input type='checkbox' id='autoprov' dfalt='1' name="autoprov">
							<input type='hidden' dfalt='dynamic' id='host' name='host'>
						</td>
						<td tip="en,users,15">Auto Prov</td>
					</tr>
					<tr>
						<td align=right><input type='checkbox' id='callwaiting' name="callwaiting"></td>
						<td tip="en,users,13">Call&nbsp;Waiting</td>
	
						<td align=right><input type='checkbox' id='threewaycalling' name="threewaycalling"></td>
						<td tip="en,users,14">3-Way&nbsp;Calling</td>
					</tr>
					<tr>
						<td align=right><input type='checkbox' id='canreinvite' name="canreinvite"></td>
						<td tip="en,users,21">Can Reinvite</td>
	
						<td align=right><input type='checkbox' id='nat' name="nat"></td>
						<td tip="en,users,22">NAT</td>
					</tr>
					</table>
					</fieldset>
					</td>
				</tr>
				<tr align=center id="RevFalse" style="display:none">
				    <td> <label><font color=red size=-1 >Please review the highlighted fields</font></label>   </td>
				</tr>
				</table>
			</td>
			</tr>				
			<tr>	
		        <td align='center'>
		        <?php
		        	if($access[1][0] == "1" || $Gtype=='admin')
		        	{
		        ?>	
					<div class="buttonwrapper" style="width:63%;">
					<!--<a class="squarebutton" href="#" id='new' onclick="newUser();"><span>New </span></a>
		        		<input type='button' id='new' value='New' onclick="javascript:newUser();"> -->
		         <?php
		        	}  
				if($access[1][3] == "1" || $Gtype=='admin')
				{
			 ?>
					<a class="squarebutton" href="#" id='Excel' onclick="exportExtenstion();" ><span>Export to EXL </span></a>
			 <?php
				}
		         	if($access[1][2] == "1") 
		         	{
		         ?>	
					<a class="squarebutton" href="#" id='Delete' onclick="deleteUserExtension();" style="margin-left:20px;"><span>Delete </span></a>
		            	<!--<input type='button' id='delete' value='Delete' onclick="javascript:deleteUserExtension();"  >-->
				</div>
		          <?php
		         	}
		          ?>
				
		        </td>
				<td style="text-align:center;">
				<!--<div class="buttonwrapper" style="width:20%;margin:5px auto;padding-right:110px;">
<a class="squarebutton" href="#" id='save' onclick="validate();"><span>Save </span></a>
</div>-->
					<!--<input type='button' id='save' value='Save' onclick="validate();">&nbsp;&nbsp;-->
				</td>
			</tr>
			</table>
	</table>
	
		</div>
	<div class="f_bottom"></div>
	</div>
</div>
<?php include('footer.php');?>
<script language="javascript">
var users = document.users;
function validate() {
	
    var i = 0;
    if (users.extension.value == "") {

        users.extension.style.backgroundColor = "#E2E2E2";
        document.getElementById("ExtLbl").style.color = "Red";
        i = 1;
    }
    else {
        if (isInteger(users.extension)) {
        }
        else {
            i = 1;
        }
        
    }
    if (users.extension.value.length < 4) {

        users.extension.style.backgroundColor = "#E2E2E2";
        document.getElementById("ExtLbl").style.color = "Red";
        i = 1;
    }
    else {
        if (isInteger(users.extension)) {
        }
        else {
            i = 1;
        }
        
    }
    if (users.fullname.value == "") {
        users.fullname.style.backgroundColor = "#E2E2E2";
        document.getElementById("NameLbl").style.color = "Red";

        i = 1;

    }
    else {
        document.getElementById("NameLbl").style.color = "Black";
    }
    if (document.users.hassip.checked == true) {
        if (users.secret.value == "") {
            users.secret.style.backgroundColor = "#E2E2E2";
            document.getElementById("PassLbl").style.color = "Red";

            i = 1;
         }
         else {
             document.getElementById("PassLbl").style.color = "Black";
        }
    }
    else {
        document.getElementById("SIPLbl").style.color = "Red";
        i = 1;
    }
    if (document.users.hasvoicemail.checked == true) {
        if (users.vmsecret.value == "") {
            users.vmsecret.style.backgroundColor = "#E2E2E2";
            document.getElementById("VMPassLbl").style.color = "Red";
            i = 1;
        }
        else {
            document.getElementById("VMPassLbl").style.color = "Black";
        }
    }
    else {
        document.getElementById("VMPassLbl").style.color = "Black";
    }
    if(i==0) {
    	saveUserExtension();
    }
    else
    {
        document.getElementById("RevFalse").style.display = "Block"; 
        return false;
    }
}
function isInteger(s) {
    var i;
    a = s.value.toString();
    for (i = 0; i < a.length; i++) {
        var c = a.charAt(i);
        if (isNaN(c)) {
            document.getElementById("ExtLbl").style.color = "Red";
            s.focus();
            return false;
        }
    }
    users.extension.style.backgroundColor = "white";
    document.getElementById("ExtLbl").style.color = "Black";
    return validateExtn();
}
function validateExtn() {
    var Ext_Arr = new Array();
    var Ext_Arr1 = new Array();
    var Ext_Arr2 = new Array();
    var i;
    var x=1;
    Extlists = users.Conf_Ext.value;
    New_Ext = users.extension.value;
    Extlists.toString();
    Ext_Arr = Extlists.split(",");
    userOperation = document.getElementById("userOperation").value;
    usersValue = document.getElementById("usersvalue").value;
    for (i = 0; i < Ext_Arr.length; i++) {
    	if (New_Ext == Ext_Arr[i]) {
            document.getElementById("ExtExists").style.display = "block";
            users.extension.focus();
			x = x + 1;
            return false;
        }
    }
    if(x==1 && users.User_Ext.value!="")
	{
    Extlists = users.User_Ext.value;
    New_Ext = users.extension.value;
    Extlists.toString();
    Ext_Arr1 = Extlists.split(",");
    for (i = 0; i < Ext_Arr1.length; i++) {
        if(userOperation == 'edit' && (trim(usersValue) == trim(New_Ext))){
    		
    	}else if (New_Ext == Ext_Arr1[i]) {
            document.getElementById("ExtExists").style.display = "block";
            users.extension.focus();
            x = x + 1;
            return false;
        }
    }
	}
	if (x==1 && users.Voice_Ext.value!="")
	{
    Extlists = users.Voice_Ext.value;
    New_Ext = users.extension.value;
    Extlists.toString();
    Ext_Arr2 = Extlists.split(",");
    for (i = 0; i < Ext_Arr2.length; i++) {
        if (New_Ext == Ext_Arr2[i]) {
            document.getElementById("ExtExists").style.display = "block";
            users.extension.focus();
            return false;
        }
    }
	} 
    document.getElementById("ExtExists").style.display = "none";
    return true;
}
</script>
</form>
</body>
</html>

