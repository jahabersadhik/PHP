<?php
include("connection.php");
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://$IPAddress/asterisk/rawman?action=login&username=$asteriskUsername&secret=$asteriskPassword");
curl_setopt($ch, CURLOPT_HEADER, 1);
@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
$curlOutput = curl_exec($ch);
$expOutput = explode(";",$curlOutput);
$expCookeInfo = explode("mansession_id=",$expOutput[1]);
$cookieValue = str_replace('"','',$expCookeInfo[1]);
setcookie("mansession_id",$cookieValue,time()+3600,"/periisystem/",$_SERVER['SERVER_ADDR']);
curl_close($ch);

if($_GET['action'] == 'edit' && $_GET['task'] == 'users'){
	$ch = curl_init();
	$testurl = "https://$IPAddress/asterisk/rawman?action=getconfig&filename=users.conf";
	curl_setopt($ch, CURLOPT_URL, $testurl);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
	//@curl_setopt ($ch , CURLOPT_REFERER, $params['referer'] );
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	$mansession_id = $_COOKIE['mansession_id'];
	@curl_setopt($ch, CURLOPT_COOKIE, "mansession_id = ".$cookieValue);
	$curlOutput = curl_exec($ch);
	$expOutput = explode(";",$curlOutput);
	$expResponse = explode("chunked",$expOutput[3]);
	$finalResult['response']=$expResponse[1];
	
	$selectedExtensionArr = explode(": ".$_GET['selectedExtension'],$finalResult['response']);
	$selectedExtensionDetailsArr = explode("Category-",$selectedExtensionArr[1]);
	$selectedExtensionLineArr = explode("\n",$selectedExtensionDetailsArr[0]);
	//$file = explode("\n",$finalResult['response']);
	//echo "<pre>";print_r($file);
	curl_close($ch);
	
	$ch = curl_init();
	$testurl = "https://$IPAddress/asterisk/rawman?action=getconfig&filename=extensions.conf";
	curl_setopt($ch, CURLOPT_URL, $testurl);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
	//@curl_setopt ($ch , CURLOPT_REFERER, $params['referer'] );
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	$mansession_id = $_COOKIE['mansession_id'];
	@curl_setopt($ch, CURLOPT_COOKIE, "mansession_id = ".$cookieValue);
	$curlOutput = curl_exec($ch);
	$expOutput = explode(";",$curlOutput);
	$expResponse = explode("chunked",$expOutput[3]);
	$finalResult['response']=$expResponse[1];
	$file2 = explode("\n",$finalResult['response']);
	//echo "<pre>";print_r($file2);die;
	curl_close($ch);
	
	//echo "<PRE>";print_r($selectedExtensionLineArr);
	$j=0;
	for($i_Inc=0;$i_Inc<count($selectedExtensionLineArr);$i_Inc++){
		if(trim($selectedExtensionLineArr[$i_Inc]) != ""){
			$extensionLineArr = explode(": ",$selectedExtensionLineArr[$i_Inc]);
			$extensionDetailsArr = explode("=",$extensionLineArr[1]);
			$userExtensionInformation[$extensionDetailsArr[0]] = $extensionDetailsArr[1];
		}
	}
	echo implode("^",array_keys($userExtensionInformation))."~".implode("^",$userExtensionInformation);
	//echo "<PRE>";print_r($userExtensionInformation);
}

if($_GET['action'] == 'save'  && $_GET['task'] == 'users'){
	if($_GET['userOperation'] == 'edit'){
		//		echo "<PRE>";print_r($_GET);die;
		$ch = curl_init();
		$saveUrl1 = "https://$IPAddress/asterisk/rawman?action=updateconfig&srcfilename=users.conf&dstfilename=users.conf&Action-000000=update&Cat-000000=".$_GET['extension']."&Var-000000=fullname&Value-000000=".str_replace(" ","%20",$_GET['fullname'])."&Action-000001=update&Cat-000001=".$_GET['extension']."&Var-000001=context&Value-000001=".$_GET['context']."&Action-000002=update&Cat-000002=".$_GET['extension']."&Var-000002=cid_number&Value-000002=".$_GET['cid_number']."&Action-000003=update&Cat-000003=".$_GET['extension']."&Var-000003=hasvoicemail&Value-000003=".(($_GET['hasvoicemail'] != "")?$_GET['hasvoicemail']:"yes")."&Action-000004=update&Cat-000004=".$_GET['extension']."&Var-000004=vmsecret&Value-000004=".$_GET['vmsecret']."&Action-000005=update&Cat-000005=".$_GET['extension']."&Var-000005=email&Value-000005=".$_GET['email']."&Action-000006=update&Cat-000006=".$_GET['extension']."&Var-000006=secret&Value-000006=".$_GET['secret']."&Action-000007=update&Cat-000007=".$_GET['extension']."&Var-000007=context&Value-000007=".$_GET['context']."&Action-000008=update&Cat-000008=".$_GET['extension']."&Var-000008=email&Value-000008=".$_GET['email'];
		$urlDetailsName1 = "Action-000000=update&Cat-000000=".$_GET['extension']."&Var-000000=fullname&Value-000000=".str_replace(" ","%20",$_GET['fullname'])."&Action-000001=update&Cat-000001=".$_GET['extension']."&Var-000001=context&Value-000001=".$_GET['context']."&Action-000002=update&Cat-000002=".$_GET['extension']."&Var-000002=cid_number&Value-000002=".$_GET['cid_number']."&Action-000003=update&Cat-000003=".$_GET['extension']."&Var-000003=hasvoicemail&Value-000003=".(($_GET['hasvoicemail'] != "")?$_GET['hasvoicemail']:"yes")."&Action-000004=update&Cat-000004=".$_GET['extension']."&Var-000004=vmsecret&Value-000004=".$_GET['vmsecret']."&Action-000005=update&Cat-000005=".$_GET['extension']."&Var-000005=email&Value-000005=".$_GET['email']."&Action-000006=update&Cat-000006=".$_GET['extension']."&Var-000006=secret&Value-000006=".$_GET['secret']."&Action-000007=update&Cat-000007=".$_GET['extension']."&Var-000007=context&Value-000007=".$_GET['context']."&Action-000008=update&Cat-000008=".$_GET['extension']."&Var-000008=email&Value-000008=".$_GET['email']; 
		curl_setopt($ch, CURLOPT_URL, $saveUrl1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
		//@curl_setopt ($ch , CURLOPT_REFERER, $params['referer'] );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		$mansession_id = $_COOKIE['mansession_id'];
		@curl_setopt($ch, CURLOPT_COOKIE, "mansession_id = ".$cookieValue);
		$curlOutput = curl_exec($ch);
		$expOutput = explode(";",$curlOutput);
		$expResponse = explode("chunked",$expOutput[3]);
		$finalResult['response']=$expResponse[1];
		$file2 = explode("\n",$finalResult['response']);
		//echo "<pre>";print_r($file2);die;
		curl_close($ch);
		
		//$paramToExecute = "";

		if(isset($_GET['callwaiting']) && $_GET['callwaiting'] != ""){
			$callWaiting = ($_GET['callwaiting'] != "")?$_GET['callwaiting']:"yes";
			$paramToExecute .= "&Action-000000=update&Cat-000000=".$_GET['extension']."&Var-000000=callwaiting&Value-000000=".$callWaiting;
		}
		if(isset($_GET['hasagent']) && $_GET['hasagent'] != ""){
			$hasagent = ($_GET['hasagent'] != "")?$_GET['hasagent']:"yes";
			$paramToExecute .= "&Action-000001=update&Cat-000001=".$_GET['extension']."&Var-000001=hasagent&Value-000001=".$hasagent;
		}
		if(isset($_GET['hasdirectory']) && $_GET['hasdirectory'] != ""){
			$hasdirectory = ($_GET['hasdirectory'] != "")?$_GET['hasdirectory']:"yes";
			$paramToExecute .= "&Action-000002=update&Cat-000002=".$_GET['extension']."&Var-000002=hasdirectory&Value-000002=".$hasdirectory;
		}
		if(isset($_GET['hasiax']) && $_GET['hasiax'] != ""){
			$hasiax = ($_GET['hasiax'] != "")?$_GET['hasiax']:"no";
			$paramToExecute .= "&Action-000003=update&Cat-000003=".$_GET['extension']."&Var-000003=hasiax&Value-000003=".$hasiax;
		}
		if(isset($_GET['hasmanager']) && $_GET['hasmanager'] != ""){
			$hasmanager = ($_GET['hasmanager'] != "")?$_GET['hasmanager']:"no";
			$paramToExecute .= "&Action-000004=update&Cat-000004=".$_GET['extension']."&Var-000004=hasmanager&Value-000004=".$hasmanager;
		}
		if(isset($_GET['hasvoicemail']) && $_GET['hasvoicemail'] != ""){
			$hasvoicemail = ($_GET['hasvoicemail'] != "")?$_GET['hasvoicemail']:"yes";
			$paramToExecute .= "&Action-000005=update&Cat-000005=".$_GET['extension']."&Var-000005=hasvoicemail&Value-000005=".$hasvoicemail;
		}
		if(isset($_GET['mailbox']) && $_GET['mailbox'] != ""){
			$mailbox = ($_GET['mailbox'] != "")?$_GET['mailbox']:"no";
			$paramToExecute .= "&Action-000006=update&Cat-000006=".$_GET['extension']."&Var-000006=mailbox&Value-000006=".$mailbox;
		}
		if(isset($_GET['threewaycalling']) && $_GET['threewaycalling'] != ""){
			$threewaycalling = ($_GET['threewaycalling'] != "")?$_GET['threewaycalling']:"yes";
			$paramToExecute .= "&Action-000007=update&Cat-000007=".$_GET['extension']."&Var-000007=threewaycalling&Value-000007=".$threewaycalling;
		}
		if(isset($_GET['registeriax']) && $_GET['registeriax'] != ""){
			$registeriax = ($_GET['registeriax'] != "")?$_GET['registeriax']:"yes";
			$paramToExecute .= "&Action-000008=update&Cat-000008=".$_GET['extension']."&Var-000008=registeriax&Value-000008=".$registeriax;
		}
		if(isset($_GET['registersip']) && $_GET['registersip'] != ""){
			$registersip = ($_GET['registersip'] != "")?$_GET['registersip']:"yes";
			$paramToExecute .= "&Action-000009=update&Cat-000009=".$_GET['extension']."&Var-000009=registersip&Value-000009=".$registersip;
		}
		if(isset($_GET['autoprov']) && $_GET['autoprov'] != ""){
			$autoprov = ($_GET['autoprov'] != "")?$_GET['autoprov']:"yes";
			$paramToExecute .= "&Action-000010=update&Cat-000010=".$_GET['extension']."&Var-000010=autoprov&Value-000010=".$autoprov;
		}
		if(isset($_GET['canreinvite']) && $_GET['canreinvite'] != ""){
			$canreinvite = ($_GET['canreinvite'] != "")?$_GET['canreinvite']:"yes";
			$paramToExecute .= "&Action-000011=update&Cat-000011=".$_GET['extension']."&Var-000011=canreinvite&Value-000011=".$canreinvite;
		}
		if(isset($_GET['nat']) && $_GET['nat'] != ""){
			$nat = ($_GET['nat'] != "")?$_GET['nat']:"yes";
			$paramToExecute .= "&Action-000012=update&Cat-000012=".$_GET['extension']."&Var-000012=nat&Value-000012=".$nat;
		}
		if(isset($_GET['disallow']) && $_GET['disallow'] != ""){
			$disallow = ($_GET['disallow'] != "")?$_GET['disallow']:"all";
			$paramToExecute .= "&Action-000013=update&Cat-000013=".$_GET['extension']."&Var-000013=disallow&Value-000013=".$disallow;
		}
		if(isset($_GET['allow']) && $_GET['allow'] != ""){
			$allow = ($_GET['allow'] != "")?$_GET['allow']:"alaw";
			$paramToExecute .= "&Action-000014=update&Cat-000014=".$_GET['extension']."&Var-000014=allow&Value-000014=".$allow;
		}
		if(isset($_GET['cti']) && $_GET['cti'] != ""){
			$cti = ($_GET['cti'] != "")?$_GET['cti']:"all";
			$paramToExecute .= "&Action-000015=update&Cat-000015=".$_GET['extension']."&Var-000015=cti&Value-000015=".$cti;
		}
		
		$ch = curl_init();
		//echo $paramToExecute."<br>";
		$saveUrl2 = "https://$IPAddress/asterisk/rawman?action=updateconfig&srcfilename=users.conf&dstfilename=users.conf$paramToExecute";
		curl_setopt($ch, CURLOPT_URL, $saveUrl2);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
		//@curl_setopt ($ch , CURLOPT_REFERER, $params['referer'] );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		$mansession_id = $_COOKIE['mansession_id'];
		@curl_setopt($ch, CURLOPT_COOKIE, "mansession_id = ".$cookieValue);
		$curlOutput = curl_exec($ch);
		$expOutput = explode(";",$curlOutput);
		$expResponse = explode("chunked",$expOutput[3]);
		$finalResult['response']=$expResponse[1];
		$file2 = explode("\n",$finalResult['response']);
		//echo "<pre>";print_r($file2);die;
		curl_close($ch);
		
		$sptQueryString = $urlDetailsName1.$paramToExecute;
		$sptUsersValues = explode("&",$sptQueryString);
		//echo "<PRE>";print_r($sptUsersValues);
		$sptFunctionValue = explode("=",$sptUsersValues[0]);
		$functionValue = $sptFunctionValue[1];
		$expTextValueArr="";
		for($i_Int=0;$i_Int<count($sptUsersValues);$i_Int = $i_Int+4){
			$expTextValue  = explode("=",$sptUsersValues[$i_Int+2]);
			$expValueValue  = explode("=",$sptUsersValues[$i_Int+3]);
			if($expTextValue[1] != ""){
				$expTextValueArr[]= $expTextValue[1];
			}
			if($expValueValue[1] != ""){
				$expTextValueArr[] = $expValueValue[1];
			}
			if(count($expTextValueArr)){
				$expAllValue[]=implode(",",$expTextValueArr);
			}
			unset($expTextValueArr);
		}
		
		$providedModificationValues = implode("~",$expAllValue);
		/*$insertUserTracking = "insert into userAccessTracking set userId='".$_SESSION['u_Customer_Id']."',trackDate='".date("Y-m-d H:i:s")."',trackFile='Users',trackFunction='".$functionValue."',trackExtension='".$_GET['extension']."',trackInformation='".$providedModificationValues."'";
		$resultUserTracking=mysql_query($insertUserTracking) or die(mysql_error());*/
		
		if(strstr($finalResult['response'],"Success")){
			echo " updated ";
		}		
	}else if($_GET['userOperation'] == 'new'){
		//echo "<PRE>";print_r($_GET);
		$ch = curl_init();
		$urlDetailsName1 = "Action-000000=delcat&Cat-000000=".$_GET['extension']."&Var-000000=&Value-000000=&Action-000001=newcat&Cat-000001=".$_GET['extension']."&Var-000001=&Value-000001=&Action-000002=append&Cat-000002=".$_GET['extension']."&Var-000002=username&Value-000002=".$_GET['extension']."&Action-000003=append&Cat-000003=".$_GET['extension']."&Var-000003=transfer&Value-000003=yes";
		$saveUrl1 = "https://$IPAddress/asterisk/rawman?action=updateconfig&srcfilename=users.conf&dstfilename=users.conf&$urlDetailsName1";
		curl_setopt($ch, CURLOPT_URL, $saveUrl1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
		//@curl_setopt ($ch , CURLOPT_REFERER, $params['referer'] );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		$mansession_id = $_COOKIE['mansession_id'];
		@curl_setopt($ch, CURLOPT_COOKIE, "mansession_id = ".$cookieValue);
		$curlOutput = curl_exec($ch);
		$expOutput = explode(";",$curlOutput);
		$expResponse = explode("chunked",$expOutput[3]);
		$finalResult['response']=$expResponse[1];
		$file1 = explode("\n",$finalResult['response']);
		//echo "<pre>";print_r($file1);
		curl_close($ch);
		
		$ch = curl_init();
		$urlDetailsName2 = "&Action-000000=append&Cat-000000=".$_GET['extension']."&Var-000000=mailbox&Value-000000=".$_GET['extension']."&Action-000001=append&Cat-000001=".$_GET['extension']."&Var-000001=call-limit&Value-000001=100&Action-000002=append&Cat-000002=".$_GET['extension']."&Var-000002=registersip&Value-000002=".(($_GET['registersip'] != "")?$_GET['registersip']:"yes")."&Action-000003=append&Cat-000003=".$_GET['extension']."&Var-000003=host&Value-000003=dynamic&Action-000004=append&Cat-000004=".$_GET['extension']."&Var-000004=registeriax&Value-000004=".(($_GET['registeriax'] != "")?$_GET['registeriax']:"no")."&Action-000005=append&Cat-000005=".$_GET['extension']."&Var-000005=dtmfmode&Value-000005=".(($_GET['dtmfmode'] != "")?$_GET['dtmfmode']:"rfc2833")."&Action-000006=append&Cat-000006=".$_GET['extension']."&Var-000006=signalling&Value-000006=".(($_GET['signalling'] != "")?$_GET['signalling']:"fxo_ks")."&Action-000007=append&Cat-000007=".$_GET['extension']."&Var-000007=secret&Value-000007=".$_GET['secret']."&Action-000008=append&Cat-000008=".$_GET['extension']."&Var-000008=hassip&Value-000008=".(($_GET['hassip'] != "")?$_GET['hassip']:"yes")."&Action-000009=append&Cat-000009=".$_GET['extension']."&Var-000009=hasiax&Value-000009=".(($_GET['hasiax'] != "")?$_GET['hasiax']:"no")."";
		$saveUrl2 = "https://$IPAddress/asterisk/rawman?action=updateconfig&srcfilename=users.conf&dstfilename=users.conf$urlDetailsName2";
		curl_setopt($ch, CURLOPT_URL, $saveUrl2);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
		//@curl_setopt ($ch , CURLOPT_REFERER, $params['referer'] );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		$mansession_id = $_COOKIE['mansession_id'];
		@curl_setopt($ch, CURLOPT_COOKIE, "mansession_id = ".$cookieValue);
		$curlOutput = curl_exec($ch);
		$expOutput = explode(";",$curlOutput);
		$expResponse = explode("chunked",$expOutput[3]);
		$finalResult['response']=$expResponse[1];
		$file2 = explode("\n",$finalResult['response']);
		//echo "<pre>";print_r($file2);
		curl_close($ch);
		
		$ch = curl_init();
		$urlDetailsName3 = "&Action-000000=append&Cat-000000=".$_GET['extension']."&Var-000000=fullname&Value-000000=".str_replace(" ","%20",$_GET['fullname'])."&Action-000001=append&Cat-000001=".$_GET['extension']."&Var-000001=context&Value-000001=".$_GET['context']."&Action-000002=append&Cat-000002=".$_GET['extension']."&Var-000002=cid_number&Value-000002=".$_GET['cid_number']."&Action-000003=append&Cat-000003=".$_GET['extension']."&Var-000003=hasvoicemail&Value-000003=".(($_GET['hasvoicemail'] != "")?$_GET['hasvoicemail']:"yes")."&Action-000004=append&Cat-000004=".$_GET['extension']."&Var-000004=vmsecret&Value-000004=".$_GET['vmsecret']."&Action-000005=append&Cat-000005=".$_GET['extension']."&Var-000005=email&Value-000005=".$_GET['email']."&Action-000006=append&Cat-000006=".$_GET['extension']."&Var-000006=threewaycalling&Value-000006=".(($_GET['threewaycalling'] != "")?$_GET['threewaycalling']:"yes")."&Action-000007=append&Cat-000007=".$_GET['extension']."&Var-000007=hasdirectory&Value-000007=".(($_GET['hasdirectory'] != "")?$_GET['hasdirectory']:"yes")."&Action-000008=append&Cat-000008=".$_GET['extension']."&Var-000008=callwaiting&Value-000008=".(($_GET['callwaiting'] != "")?$_GET['callwaiting']:"yes")."&Action-000009=append&Cat-000009=".$_GET['extension']."&Var-000009=hasmanager&Value-000009=".(($_GET['hasmanager'] != "")?$_GET['hasmanager']:"no")."&Action-000010=append&Cat-000010=".$_GET['extension']."&Var-000010=nat&Value-000010=".(($_GET['nat'] != "")?$_GET['nat']:"yes")."&Action-000011=append&Cat-000011=".$_GET['extension']."&Var-000011=canreinvite&Value-000011=".(($_GET['canreinvite'] != "")?$_GET['canreinvite']:"yes")."&Action-000012=append&Cat-000012=globals&Var-000012=CID_".$_GET['extension']."&Value-000012=".$_GET['extension']."&Action-000013=append&Cat-000013=".$_GET['extension']."&Var-000013=disallow&Value-000013=".(($_GET['disallow'] != "")?$_GET['disallow']:"all")."&Action-000014=append&Cat-000014=".$_GET['extension']."&Var-000014=allow&Value-000014=".(($_GET['allow'] != "")?$_GET['allow']:"alaw")."&Action-000015=append&Cat-000015=".$_GET['extension']."&Var-000015=macaddress&Value-000015=".(($_GET['macaddress'] != "")?$_GET['macaddress']:"123456")."&Action-000016=append&Cat-000016=".$_GET['extension']."&Var-000016=autoprov&Value-000016=".(($_GET['autoprov'] != "")?$_GET['autoprov']:"no")."&Action-000017=append&Cat-000017=".$_GET['extension']."&Var-000017=label&Value-000017=".$_GET['extension']."&Action-000018=append&Cat-000018=".$_GET['extension']."&Var-000018=linenumber&Value-000018=1&Action-000019=append&Cat-000019=".$_GET['extension']."&Var-000019=hasagent&Value-000019=".(($_GET['hasagent'] != "")?$_GET['hasagent']:"no")."";
		$saveUrl3 = "https://$IPAddress/asterisk/rawman?action=updateconfig&srcfilename=users.conf&dstfilename=users.conf$urlDetailsName3";
		//Action-000010=append&Cat-000010=".$_GET['extension']."&Var-000010=managerread&Value-000010=system%2Ccall%2Clog%2Cverbose%2Ccommand%2Cagent%2Cuser%2Cconfig%2Coriginate&Action-000011=append&Cat-000011=".$_GET['extension']."&Var-000011=managerwrite&Value-000011=system%2Ccall%2Clog%2Cverbose%2Ccommand%2Cagent%2Cuser%2Cconfig%2Coriginate&
		curl_setopt($ch, CURLOPT_URL, $saveUrl3);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
		//@curl_setopt ($ch , CURLOPT_REFERER, $params['referer'] );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		$mansession_id = $_COOKIE['mansession_id'];
		@curl_setopt($ch, CURLOPT_COOKIE, "mansession_id = ".$cookieValue);
		$curlOutput = curl_exec($ch);
		$expOutput = explode(";",$curlOutput);
		$expResponse = explode("chunked",$expOutput[3]);
		$finalResult['response']=$expResponse[1];
		$file3 = explode("\n",$finalResult['response']);
		//echo "<pre>";print_r($file3);
		curl_close($ch);
		
		$sptQueryString = $urlDetailsName1.$urlDetailsName2.$urlDetailsName3;
		$sptUsersValues = explode("&",$sptQueryString);
		//echo "<PRE>";print_r($sptUsersValues);
		$sptFunctionValue = explode("=",$sptUsersValues[0]);
		$functionValue = $sptFunctionValue[1];
		$expTextValueArr="";
		for($i_Int=0;$i_Int<count($sptUsersValues);$i_Int = $i_Int+4){
			$expTextValue  = explode("=",$sptUsersValues[$i_Int+2]);
			$expValueValue  = explode("=",$sptUsersValues[$i_Int+3]);
			if($expTextValue[1] != ""){
				$expTextValueArr[]= $expTextValue[1];
			}
			if($expValueValue[1] != ""){
				$expTextValueArr[] = $expValueValue[1];
			}
			if(count($expTextValueArr)){
				$expAllValue[]=implode(",",$expTextValueArr);
			}
			unset($expTextValueArr);
		}
		$providedModificationValues = implode("~",$expAllValue);
		/*$insertUserTracking = "insert into userAccessTracking set userId='".$_SESSION['u_Customer_Id']."',trackDate='".date("Y-m-d H:i:s")."',trackFile='Users',trackFunction='".$functionValue."',trackExtension='".$_GET['extension']."',trackInformation='".$providedModificationValues."'";
		$resultUserTracking=mysql_query($insertUserTracking) or die(mysql_error());*/
		
		if(strstr($finalResult['response'],"Success")){
			echo " added ";
		}
	}
}

if($_GET['action'] == 'delete'  && $_GET['task'] == 'users'){
	$ch = curl_init();
	//action=updateconfig&srcfilename=users.conf&dstfilename=users.conf&Action-000000=delcat&Cat-000000=6018&Var-000000=&Value-000000=
	$urlDetailsName1 = "Action-000000=delcat&Cat-000000=".$_GET['extension']."&Var-000000=&Value-000000=";
	$deleteUrl = "https://$IPAddress/asterisk/rawman?action=updateconfig&srcfilename=users.conf&dstfilename=users.conf&$urlDetailsName1";
	curl_setopt($ch, CURLOPT_URL, $deleteUrl);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
	//@curl_setopt ($ch , CURLOPT_REFERER, $params['referer'] );
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	$mansession_id = $_COOKIE['mansession_id'];
	@curl_setopt($ch, CURLOPT_COOKIE, "mansession_id = ".$cookieValue);
	$curlOutput = curl_exec($ch);
	$expOutput = explode(";",$curlOutput);
	$expResponse = explode("chunked",$expOutput[3]);
	$finalResult['response']=$expResponse[1];
	$file2 = explode("\n",$finalResult['response']);
	
	$sptQueryString = $urlDetailsName1;
	$sptUsersValues = explode("&",$sptQueryString);
	//echo "<PRE>";print_r($sptUsersValues);
	$sptFunctionValue = explode("=",$sptUsersValues[0]);
	$functionValue = $sptFunctionValue[1];
	$expTextValueArr="";
	for($i_Int=0;$i_Int<count($sptUsersValues);$i_Int = $i_Int+4){
		$expTextValue  = explode("=",$sptUsersValues[$i_Int+2]);
		$expValueValue  = explode("=",$sptUsersValues[$i_Int+3]);
		if($expTextValue[1] != ""){
			$expTextValueArr[]= $expTextValue[1];
		}
		if($expValueValue[1] != ""){
			$expTextValueArr[] = $expValueValue[1];
		}
		if(count($expTextValueArr)){
			$expAllValue[]=implode(",",$expTextValueArr);
		}
		unset($expTextValueArr);
	}
	$providedModificationValues = implode("~",$expAllValue);
	/*$insertUserTracking = "insert into userAccessTracking set userId='".$_SESSION['u_Customer_Id']."',trackDate='".date("Y-m-d H:i:s")."',trackFile='Users',trackFunction='".$functionValue."',trackExtension='".$_GET['extension']."',trackInformation='".$providedModificationValues."'";
	$resultUserTracking=mysql_query($insertUserTracking) or die(mysql_error());*/
	//echo "<pre>";print_r($file2);die;
	curl_close($ch);
	if(strstr($finalResult['response'],"Success")){
		echo "Deleted Successfully";
	}
}

if($_GET['action'] == 'edit' && $_GET['task'] == 'voicemail'){
	$ch = curl_init();
	$testurl = "https://$IPAddress/asterisk/rawman?action=getconfig&filename=voicemail.conf";
	curl_setopt($ch, CURLOPT_URL, $testurl);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
	//@curl_setopt ($ch , CURLOPT_REFERER, $params['referer'] );
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	$mansession_id = $_COOKIE['mansession_id'];
	@curl_setopt($ch, CURLOPT_COOKIE, "mansession_id = ".$cookieValue);
	$curlOutput = curl_exec($ch);
	$expOutput = explode(";",$curlOutput);
	$expResponse = explode("chunked",$expOutput[3]);
	$finalResult['response']=$expResponse[1];
	$file = explode("Category",$finalResult['response']);
	//echo "<pre>";print_r($file);die;
	curl_close($ch);
	
	$ch = curl_init();
	$testurl3 = "https://$IPAddress/asterisk/rawman?action=getconfig&filename=extensions.conf";
	curl_setopt($ch, CURLOPT_URL, $testurl3);
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
	$file3 = explode("\n",$finalResult['response']);
	//echo "<pre>";print_r($file3);die;
	curl_close($ch);
	
	$j=0;
	for($i_Inc=0;$i_Inc<count($file3);$i_Inc++){
		if(trim($file3[$i_Inc]) != ""){
			if(strstr($file3[$i_Inc],"VoiceMailMain(\${CALLERID(num)}")){
				$voiceMailArr = explode("exten=",$file3[$i_Inc]);
				$voiceMailExtension = substr($voiceMailArr[1],0,4);
				//$userExtensionInformation[$extensionDetailsArr[0]] = $extensionDetailsArr[1];
			}
		}
	}
	$i_Int=0;
	$voiceMailOptionsArr = explode("\n",$file[1]);
	//echo "<PRE>";print_r($voiceMailOptionsArr);die;
	for($i_Inc=0;$i_Inc<count($voiceMailOptionsArr);$i_Inc++){
		if(trim($voiceMailOptionsArr[$i_Inc]) != ""){
			if(!strstr($file3[$i_Inc],"general")){
				$voiceMailArr = explode(": ",$voiceMailOptionsArr[$i_Inc]);
				$voiceMailExtensionValue = explode("=",$voiceMailArr[1]);
				$userVoiceMailInformation[$voiceMailExtensionValue[0]] = $voiceMailExtensionValue[1];
			}
		}
	}
	echo $voiceMailExtension."~".implode("^",array_keys($userVoiceMailInformation))."~".implode("^",$userVoiceMailInformation);
	//echo implode("^",array_keys($userExtensionInformation))."~".implode("^",$userExtensionInformation);
	//echo "<PRE>";print_r($userExtensionInformation);
}

if($_GET['action'] == 'save'  && $_GET['task'] == 'voicemail'){
	if($_GET['userOperation'] == 'edit'){
		$ch = curl_init();
		$urlDetailsName1 = "Action-000000=delete&Cat-000000=default&Var-000000=exten&Value-000000=&Action-000001=append&Cat-000001=default&Var-000001=exten&Value-000001=".$_GET['extension']."%2C1%2CVoiceMailMain(%24%7BCALLERID(num)%7D%40default)";
		$saveUrl1 = "https://$IPAddress/asterisk/rawman?action=updateconfig&srcfilename=extensions.conf&dstfilename=extensions.conf&Action-000000=delete&Cat-000000=default&Var-000000=exten&Value-000000=&Match-000000=".$_GET['extension']."%2C1%2CVoiceMailMain(%24%7BCALLERID(num)%7D%40default)&Action-000001=append&Cat-000001=default&Var-000001=exten&Value-000001=".$_GET['extension']."%2C1%2CVoiceMailMain(%24%7BCALLERID(num)%7D%40default)";
		curl_setopt($ch, CURLOPT_URL, $saveUrl1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
		//@curl_setopt ($ch , CURLOPT_REFERER, $params['referer'] );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		$mansession_id = $_COOKIE['mansession_id'];
		@curl_setopt($ch, CURLOPT_COOKIE, "mansession_id = ".$cookieValue);
		$curlOutput = curl_exec($ch);
		$expOutput = explode(";",$curlOutput);
		$expResponse = explode("chunked",$expOutput[3]);
		$finalResult['response']=$expResponse[1];
		$file1 = explode("\n",$finalResult['response']);
		//echo "<pre>";print_r($file1);
		curl_close($ch);
		
		//$paramToExecute = "";

		if(isset($_GET['envelope']) && $_GET['envelope'] != ""){
			$envelope = ($_GET['envelope'] != "")?$_GET['envelope']:"yes";
			$paramToExecute .= "&Action-000000=update&Cat-000000=general&Var-000000=envelope&Value-000000=".$envelope;
		}
		if(isset($_GET['operator']) && $_GET['operator'] != ""){
			$operator = ($_GET['operator'] != "")?$_GET['operator']:"yes";
			$paramToExecute .= "&Action-000001=update&Cat-000001=general&Var-000001=operator&Value-000001=".$operator;
		}
		if(isset($_GET['review']) && $_GET['review'] != ""){
			$review = ($_GET['review'] != "")?$_GET['review']:"yes";
			$paramToExecute .= "&Action-000002=update&Cat-000002=general&Var-000002=review&Value-000002=".$review;
		}
		if(isset($_GET['saycid']) && $_GET['saycid'] != ""){
			$saycid = ($_GET['saycid'] != "")?$_GET['saycid']:"yes";
			$paramToExecute .= "&Action-000003=update&Cat-000003=general&Var-000003=saycid&Value-000003=".$saycid;
		}
		if(isset($_GET['sayduration']) && $_GET['sayduration'] != ""){
			$sayduration = ($_GET['sayduration'] != "")?$_GET['sayduration']:"yes";
			$paramToExecute .= "&Action-000004=update&Cat-000004=general&Var-000004=sayduration&Value-000004=".$sayduration;
		}
		if(isset($_GET['maxmsg']) && $_GET['maxmsg'] != ""){
			$maxmsg = $_GET['maxmsg'];
			$paramToExecute .= "&Action-000005=update&Cat-000005=general&Var-000005=maxmsg&Value-000005=".$maxmsg;
		}
		if(isset($_GET['maxmessage']) && $_GET['maxmessage'] != ""){
			$maxmessage = $_GET['maxmessage'];
			$paramToExecute .= "&Action-000006=update&Cat-000006=general&Var-000006=maxmessage&Value-000006=".$maxmessage;
		}
		if(isset($_GET['minmessage']) && $_GET['minmessage'] != ""){
			$minmessage = $_GET['minmessage'];
			$paramToExecute .= "&Action-000007=update&Cat-000007=general&Var-000007=minmessage&Value-000007=".$minmessage;
		}
		if(isset($_GET['maxgreet']) && $_GET['maxgreet'] != ""){
			$maxgreet = $_GET['maxgreet'];
			$paramToExecute .= "&Action-000008=update&Cat-000008=general&Var-000008=maxgreet&Value-000008=".$maxgreet;
		}

		$ch = curl_init();
		$saveUrl2 = "https://$IPAddress/asterisk/rawman?action=updateconfig&srcfilename=voicemail.conf&dstfilename=voicemail.conf$paramToExecute";
		curl_setopt($ch, CURLOPT_URL, $saveUrl2);
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
		$file2 = explode("\n",$finalResult['response']);
		//echo "<pre>";print_r($file2);
		curl_close($ch);
		
		$ch = curl_init();
		$urlDetailsName3 = "&Action-000000=update&Cat-000000=general&Var-000000=vmexten&Value-000000=".$_GET['extension']."";
		$saveUrl3 = "https://$IPAddress/asterisk/rawman?action=updateconfig&srcfilename=users.conf&dstfilename=users.conf$urlDetailsName3";
		curl_setopt($ch, CURLOPT_URL, $saveUrl3);
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
		$file3 = explode("\n",$finalResult['response']);
		//echo "<pre>";print_r($file3);
		curl_close($ch);
		
		$sptQueryString = $urlDetailsName1.$paramToExecute.$urlDetailsName3;
		$sptUsersValues = explode("&",$sptQueryString);
		//echo "<PRE>";print_r($sptUsersValues);
		$expTextValueArr="";
		for($i_Int=0;$i_Int<count($sptUsersValues);$i_Int = $i_Int+4){
			$expTextValue  = explode("=",$sptUsersValues[$i_Int+2]);
			$expValueValue  = explode("=",$sptUsersValues[$i_Int+3]);
			if($expTextValue[1] != ""){
				$expTextValueArr[]= $expTextValue[1];
			}
			if($expValueValue[1] != ""){
				$expTextValueArr[] = $expValueValue[1];
			}
			if(count($expTextValueArr)){
				$expAllValue[]=implode(",",$expTextValueArr);
			}
			unset($expTextValueArr);
		}
		$providedModificationValues = implode("~",$expAllValue);
		/*$insertUserTracking = "insert into userAccessTracking set userId='".$_SESSION['u_Customer_Id']."',trackDate='".date("Y-m-d H:i:s")."',trackFile='Voicemail',trackFunction='".$_GET['userOperation']."',trackExtension='".$_GET['extension']."',trackInformation='".$providedModificationValues."'";
		$resultUserTracking=mysql_query($insertUserTracking) or die(mysql_error());*/
		if(strstr($finalResult['response'],"Success")){
			echo " updated ";
		}		
	}
}
?>