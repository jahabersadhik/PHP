<?php
session_start();

include("../connection.php");

$url= $_POST['dialingId'];
$currentTime = date('Y-m-d H:i:s');
$fromdatetime = str_replace("/","-",$_POST['callbackdate'])." ".$_POST['callbacktime'];
$fromdatetime = date('Y-m-d',strtotime($fromdatetime));
$todatetime = date('Y-m-d',strtotime("$fromdatetime +120 minute"));


if($url != ""){ 
	$sql_current = mysql_query("select * from current where id = '$url'");
	$row = mysql_fetch_array($sql_current);
	$callDate = $row['calleddatetime'];
	$processid = $row['processid'];
	$phone = $row['phone'];
	$dialerDispostion = $row['dialerdisposition'];
	$calledextension = $_REQUEST['agentExtensionNo'];
	if($_REQUEST['userDisposition']=="Call Back"){	
		$status = "1";
	}else {
		$status = "0";
	}
	$dateBack = split("/",$_REQUEST['callbackdate']);
	$callBack = $dateBack[2]."-".$dateBack[1]."-".$dateBack[0];
	
	//Change Database (ie) dialer database to CDR Database.	
	$db_name_cdr = "cdr";
	mysql_select_db("$db_name_cdr",$connection)or die("Cannot select DB");
	
	$QueryCDR = mysql_query("SELECT billsec FROM cdr WHERE calldate like '%".$callDate."%' AND dst = '".$phone."' ORDER BY calldate ASC LIMIT 0,1");
	$rowCDR = mysql_fetch_array($QueryCDR);
	$billsec = $rowCDR['billsec'];
	
	$totTime = strtotime(".$callDate.") + $billsec;
	$endDateTime = date("Y-m-d G:i:s",$totTime);

	@mysql_close();
	
	//Change Datebase (ie) CDR Database to dialer Databse
	include("../connection.php");
	if($_REQUEST['userDisposition'] == 'Block')
	{
		asteriskLog($phone);
		mysql_query("UPDATE master SET status='Block' WHERE phone='$phone'");
	}
	if($_REQUEST['userDisposition'] == 'DNC')
	{
		//asteriskLog($phone);
		mysql_query("UPDATE master SET status='DNC' WHERE phone='$phone'");
	}
	$wrappTime = strtotime(date("Y-m-d G:i:s")) - $totTime; 
	$agentComment = $_REQUEST['txtADesc']."~".$wrappTime;
	
	$sql_inser = mysql_query("INSERT into comment values (NULL,'$phone','$calledextension','$callDate','$dialerDispostion','".$_REQUEST['userDisposition']."','$status','".$callBack."','".$_REQUEST['txtCDesc']."','".$agentComment."','1','$processid')");

	$sql_delete = mysql_query("DELETE FROM current WHERE id = '$url'");


}
if($sql_inser)
{ 
?>
<script>
	opener.location.reload(true);
	self.close();
</script>
<?php 
} 
?>



