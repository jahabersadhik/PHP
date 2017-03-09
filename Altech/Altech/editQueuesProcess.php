<?php
	include "check.php";
	require("connection.php");
	$queueName = $_REQUEST['queueName'];
	$queueNameOld = $_REQUEST['queueNameOld'];
	$checkQueueNameResult = mysql_query("SELECT *FROM queues WHERE queueName='$queueName'");
	$numberOfRows = mysql_num_rows($checkQueueNameResult);
	$process = $_REQUEST['process'];
	if($queueName == $queueNameOld)
	$numberOfRows = 0;
	if($numberOfRows==0)
	{
		if(count($_REQUEST['chk'])>0)
		{
			$extensionArr = implode(",",$_REQUEST['chk']);
			mysql_query("UPDATE queues SET queueName='$queueName',members='$extensionArr',processId='$process' WHERE queueName = '$queueNameOld'");
			mysql_query("UPDATE availability SET queueName='$queueName' WHERE queueName = '$queueNameOld'");
			mysql_query("UPDATE incomingavailability SET queueName='$queueName' WHERE queueName = '$queueNameOld'");

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
			$expPhpSelf = explode("/",$_SERVER['PHP_SELF']);
			$strToAdd = "";
			if(!strstr($expPhpSelf[1],"."))
			{
				$strToAdd = $expPhpSelf[1]."/";
			}
			setcookie("mansession_id",$cookieValue,time()+3600,"/".$strToAdd,$_SERVER['SERVER_ADDR']);
			curl_close($ch);
			
			$ch = curl_init();
			$testurl = "https://$IPAddress/asterisk/rawman?action=updateconfig&srcfilename=queues.conf&dstfilename=queues.conf&Action-000000=delcat&Cat-000000=$queueNameOld&Var-000000=&Value-000000=&Action-000001=newcat&Cat-000001=$queueName&Var-000001=&Value-000001=&Action-000002=append&Cat-000002=$queueName&Var-000002=fullname&Value-000002=$queueName&Action-000003=append&Cat-000003=$queueName&Var-000003=strategy&Value-000003=rrmemory&Action-000004=append&Cat-000004=$queueName&Var-000004=timeout&Value-000004=15&Action-000005=append&Cat-000005=$queueName&Var-000005=wrapuptime&Value-000005=15&Action-000006=append&Cat-000006=$queueName&Var-000006=musicclass&Value-000006=default&Action-000007=append&Cat-000007=$queueName&Var-000007=maxlen&Value-000007=3&Action-000008=append&Cat-000008=$queueName&Var-000008=autofill&Value-000008=yes&Action-000009=append&Cat-000009=$queueName&Var-000009=updatecdr&Value-000009=yes";
			$agentExtensions = $_REQUEST['chk'];
			for($j=0,$i=10;$j<count($agentExtensions);$i++,$j++)
			{
				if(strlen($i)==2)
					$zeros = "0000";
				if(strlen($i)>2)
					$zeros = "000";
				$testurl .=  "&Action-$zeros$i=append&Cat-$zeros$i=$queueName&Var-$zeros$i=member&Value-$zeros$i=AGENT/$agentExtensions[$j]";
			}
			curl_setopt($ch, CURLOPT_URL, $testurl);
			curl_setopt($ch, CURLOPT_HEADER, 1);
			@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			$mansession_id = $_COOKIE['mansession_id'];
			@curl_setopt($ch, CURLOPT_COOKIE, "mansession_id = ".$cookieValue);
			$curlOutput = curl_exec($ch);
			curl_close($ch);
			
			$ch = curl_init();
			//$match = "$queueNameOld,1,Queue(%24{EXTEN})";
			$match = "$queueNameOld,1,Macro(incoming,$queueNameOld,%24{EXTEN})";
			$testurl = "https://$IPAddress/asterisk/rawman?action=updateconfig&srcfilename=extensions.conf&dstfilename=extensions.conf&Action-000000=delete&Cat-000000=queues&Var-000000=exten&Match-000000=$match";
			curl_setopt($ch, CURLOPT_URL, $testurl);
			curl_setopt($ch, CURLOPT_HEADER, 1);
			@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			$mansession_id = $_COOKIE['mansession_id'];
			@curl_setopt($ch, CURLOPT_COOKIE, "mansession_id = ".$cookieValue);
			$curlOutput = curl_exec($ch);
			curl_close($ch);
			
			$ch = curl_init();
			//$testurl = "https://$IPAddress/asterisk/rawman?action=updateconfig&srcfilename=extensions.conf&dstfilename=extensions.conf&Action-000000=append&Cat-000000=queues&Var-000000=exten&Value-000000=$queueName,1,Queue(%24{EXTEN})";
			$testurl = "https://$IPAddress/asterisk/rawman?action=updateconfig&srcfilename=extensions.conf&dstfilename=extensions.conf&Action-000000=append&Cat-000000=queues&Var-000000=exten&Value-000000=$queueName,1,Macro(incoming,$queueName,%24{EXTEN})";
			curl_setopt($ch, CURLOPT_URL, $testurl);
			curl_setopt($ch, CURLOPT_HEADER, 1);
			@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			$mansession_id = $_COOKIE['mansession_id'];
			@curl_setopt($ch, CURLOPT_COOKIE, "mansession_id = ".$cookieValue);
			$curlOutput = curl_exec($ch);
			curl_close($ch);
			
			$ch = curl_init();
			//$testurl = "https://$IPAddress/asterisk/rawman?action=updateconfig&srcfilename=extensions.conf&dstfilename=extensions.conf&Action-000000=append&Cat-000000=queues&Var-000000=exten&Value-000000=$queueName,1,Queue(%24{EXTEN})";
			$testurl = "https://$IPAddress/asterisk/rawman?action=command&command=reload";
			curl_setopt($ch, CURLOPT_URL, $testurl);
			curl_setopt($ch, CURLOPT_HEADER, 1);
			@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			$mansession_id = $_COOKIE['mansession_id'];
			@curl_setopt($ch, CURLOPT_COOKIE, "mansession_id = ".$cookieValue);
			$curlOutput = curl_exec($ch);
			curl_close($ch);

			

			echo "
					<script language='javascript'>
						alert(\"Queue Created Successfully\");
						location.href='home.php?page=queues';
					</script>
			 	 ";
		}
		else 
		{
			echo "
					<script language='javascript'>
						alert(\"Please select atleast one member to Create the Queue\");
						location.href='home.php?page=queues';
					</script>
			 	 ";
		}
	}
	else
	{
		echo "
				<script language='javascript'>
					alert(\"This Queue Already Exist\");
					location.href='home.php?page=queues';
				</script>
			 ";
	}
?>
