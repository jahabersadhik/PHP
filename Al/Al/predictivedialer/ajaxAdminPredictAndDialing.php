<?php
include("../connection.php");
$today = date('Y-m-d');
$accountQuery = "select * from current where dialertype=2";
$resultAccount=mysql_query($accountQuery)or die(mysql_error());
$accountCount = mysql_num_rows($resultAccount);
$strContext = "outbound";
$strWaitTime = "30";
$strPriority = "1";
$strMaxRetry = "2";
//$_SESSION['queueNumber'];
$clickToCallContext = "clicktocall";
$strChannel = "SIP/".$ringGroupExtension;

if($accountCount==0)
	echo "No Clients to Call";
else	
{
	while($accountRow = mysql_fetch_array($resultAccount))
	{
		if($strChannel != "")
		{
			$strExten = $accountRow['phone'];
			$destinationNumber = $strExten;
			$strCallerId = "<$strExten>";
			$length = strlen($strExten);
			$queuenameQuery = mysql_query("SELECT queueName from queues WHERE processId LIKE '%".$accountRow['processid'].",%'");
			$queuenameResult = mysql_fetch_row($queuenameQuery);
			$sourceNumber = $queuenameResult[0];
			if (is_numeric($strExten))
			{
				$oSocket = fsockopen($IPAddress, 5038, $errnum, $errdesc) or die("Connection to host failed");
				fputs($oSocket, "Action: login\r\n");
				fputs($oSocket, "Events: off\r\n");
				fputs($oSocket, "Username: $asteriskUsername\r\n");
				fputs($oSocket, "Secret: $asteriskPassword\r\n\r\n");
				fputs($oSocket, "Action: originate\r\n");
				fputs($oSocket, "Channel: Local/$strExten@local/n\r\n");
				fputs($oSocket, "WaitTime: $strWaitTime\r\n");
				fputs($oSocket, "CallerId: $callerIDToShow\r\n");
				fputs($oSocket, "Exten: $sourceNumber\r\n");
				fputs($oSocket, "Variable: var1=$sourceNumber|var2=$strExten|var3=".$accountRow['id']."|var4=".$accountRow['callDate']."|var5=".$sourceNumber."|var6=".$Fpassword."|var7=".$assignQueue."|var8=".$ExtnToCall."\r\n");
				fputs($oSocket, "Context: $strContext\r\n");
				fputs($oSocket, "Priority: $strPriority\r\n\r\n");
				fputs($oSocket, "Action: Logoff\r\n\r\n");
				fclose($oSocket);
				$calledDateTime = date('Y-m-d H:i:s');
				//mysql_query("update predictiveDialing set calledDateTime='$calledDateTime' where phone='".$accountRow['phone']."'");
				echo "Called $strExten";
				sleep(10);
				continue;
			}
		}
		else 
		{
			echo "All the agents are busy.Waiting...";
		}
	}
}
?>
