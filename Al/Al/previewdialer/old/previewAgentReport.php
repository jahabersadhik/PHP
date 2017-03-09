<?php
/*****************************************************************************
 *	File Name   : PredictiveDialingReport.php                             *
 *	Created by  : Jahaber sadhik M.H                                      *
 *	Version     : 1.0.0.0.0.0                                             *
 *	Connection  : PredictDialing.php                                      *
 *	Created On  : 26 Dec 2009                                             *
 *	Last Updated:                                                         *
 *	Updated By  :                                                         *
 *****************************************************************************/
session_start();

include("mysql_connection.php");

$loginname=$_SESSION['hid']; 
$cust_cId=$_SESSION['u_Customer_Id'];

if(!isset($_SESSION['hid'])){
	header("Location: index.php");// redirect out
die(); // need this, or the rest of the script executes
}
?>

<html>
<head>
<title>PERI Software Solutions iSystem Web Portal</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="ButtonStyle.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="scripts/ajaxCreation.js"></script>
<script language="javascript">
	var url_name = window.location.search.substring(1);
	//document.write(url_name);
</script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="00" marginheight="0"  class=NormalFont>
<form method=post name="predictiveDialingList" id="catcampform">
<?php include('frame.php');?>
			<div style=" height:auto; width:100%; overflow:auto;">
			<table width=100% class=normalfont cellpadding=0 border=0>			
<?php
	echo "<font color=red><b>$cust_type_message<b></font>";
	echo "<font color=red><b>$cust_type_message1<b></font>";
	unset($_SESSION['type']);
	unset($_SESSION['type1']);
	
	$Sql_Select = mysql_query("SELECT * FROM users WHERE extensionNo = '".$_REQUEST['ID']."'");
	$row = mysql_fetch_array($Sql_Select);
?>
			<tr>
			<td  align="right" style="padding-left:200px;">
			<INPUT type="button" value="Back" name="back" onclick="javascript:location.href='previewDialingReport.php';" class="NormalButton">
			</td>
			</tr>
			<table>
			<tr>
				<td align="left">
					<LABEL class=sublinks>Agent Name: </LABEL>
				</td>
				<td>
					<LABEL class=sublinks><?php echo $row['fname'].$row['lname']; ?></LABEL>
				</td>
			</tr>
			<tr>
				<td align="left">
					<LABEL class=sublinks>Employee ID :</LABEL>
				</td>
				<td>
					<LABEL class=sublinks><?php echo $row['employeeId']; ?></LABEL>
				</td>
			</tr>
			<tr>
				<td align="left">
					<LABEL class=sublinks>Extension No :</LABEL>
				</td>
				<td>
					<LABEL class=sublinks><?php echo $row['extensionNo']; ?></LABEL>
				</td>
			</tr>
			<tr>
				<td align="left">
					<LABEL class=sublinks>Email Id :</LABEL>
				</td>
				<td>
					<LABEL class=sublinks><?php echo $row['userEmailId']; ?></LABEL>
				</td>
			</tr>
			
			</table>
<?php 
	$Sql_Select_Preview = mysql_query("SELECT firstname,lastname,Comments FROM previewDialing WHERE calledExtension = '".$_REQUEST['ID']."'");	
	$accountRow = mysql_fetch_array($Sql_Select_Preview);
	
	if($accountRow['Comments']!=""){ 
	$commentsRow = split("~!",$accountRow['Comments']);
	$count = count($commentsRow);
	$name = $accountRow['firstname'].$accountRow['lastname'];
	for ($I=1; $I<=$count; $I++){ 
		$No = $I ;
		$val = split("~",$commentsRow[$I]);
		$cDates=date("jS M, Y H:i:s",strtotime("$val[0]"));
		$comms = $val[1]; 
		$Ccomms = $val[2]; 
?>
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr bgcolor="#027BE6">
	<td>
		<?php echo "<font color='white'>Comment #"." "."$No"."</font>"?>
	</td>
	<td>
		<?php //echo "<font color='white'>$count</font>"; ?>
	</td>						
	<td align="center">
		<?php echo "<font color='white'>&nbsp;</font>";?>
	</td>
	<td>
		<?php //echo "<font color='white'>$commBys</font>";?>
	</td>							
	<td align="right">
		<?php echo "<font color='white'>$cDates</font>";?>
	</td>											
	</tr>
	</table>
	<br>
	<table width="100%">
	<tr>
	<td>
		<?php echo "Client Name:".$name."<BR><BR>Agent Comments:".$comms ."<BR><BR> Client Commentd:".$Ccomms ;?>
	</td>
	</tr>
	</table>
	<br>
	<?php //$count=$count-1; 
	} } else { ?>
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr bgcolor="#027BE6">
	<td colspan = 5>
		<?php echo "<font color='white'> No record found...</font>"?>
	</td>
	</tr>
	</table>
	<?php } ?>
</tr>
</table>
</div>
<?php include('footer1.php');?>
<script language="javascript">
function submitform()
{
	document.getElementById('catcampform').action="./predictiveDialingRerport.php";
	document.getElementById('catcampform').submit();
}

</script>

</form>
</body>
</html>
