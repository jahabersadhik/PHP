<?php
/*****************************************************************************
 *	File Name   : PreviewDialingReport.php                                *
 *	Created by  : Jahaber sadhik M.H                                      *
 *	Version     : 1.0.0.0.0.0                                             *
 *	Connection  : previewDialing.php                                      *
 *	Created On  : 29 Dec 2009                                             *
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

	
	$sqlSelect = mysql_query("SELECT * FROM users WHERE typeOfUser='Call Agent' AND  parentUsers like '%$cust_cId%'");

?>
			<tr align=right valign="top">
						<td colspan=7> 
				<?php
	          	 	$categoryQuery = "select * from previewDialing group by catId,campId order by dialingId";
				$resultCategory=mysql_query($categoryQuery)or die(mysql_error());
	          	?>
	          	 <select name=catcampId id="catcampId"  style="width:170Px;" class="NormalTextBox" onchange="submitform()">
				<option value="0" >--Select a Category-Campaign--</option>
					<?php while($categoryRow = mysql_fetch_array($resultCategory)){ ?>
					<option value="<?php echo $categoryRow['catId'].",".$categoryRow['campId'];?>" <?php if($_POST['catcampId']==($categoryRow['catId'].",".$categoryRow['campId'])) {?> selected <?php } ?>><?php echo $categoryRow['catId']."-".$categoryRow['campId'];?></option>
				     <?php } ?>
				</select>
				<INPUT type="button" value="Back" name="back" onclick="javascript:location.href='headings.php';" class="NormalButton">
				
			</td>
			</tr>
			<tr align=center valign="top">
				<td colspan=7 align="center" class="NormalFont"><span id="loggedInUsers" ></span>				
				</td>
			</tr>
			<tr align=center valign="top">
				<td colspan=7 align="center" class="NormalFont"><span id="responseText" ></span>				
				</td>
			</tr>
			<tr  bgcolor="#ff9900" class="headings">
			<td width="100">S.No
			</td >
			<td width="150">Agent Id
			</td >
			<td width="150">Agent Extension
			</td >
			<td width="150">Assign Calls
			</td >			
			<td width="150">No.of Called
			</td >
			<td width="150">Action
			</td>
			</tr>
<?php 
	$No = 1;
	while($rowusers = mysql_fetch_array($sqlSelect)){ 
		$Sql_Count = mysql_query("SELECT calledExtension FROM previewDialing WHERE calledExtension = '".$rowusers['extensionNo']."'");
		$callCount = mysql_num_rows($Sql_Count);
		$Sql_AssignCount = mysql_query("SELECT calledExtension FROM previewDialing WHERE assignAgent = '".$rowusers['extensionNo']."'");
		$asignCallCount = mysql_num_rows($Sql_AssignCount);
?>
<tr bgcolor="#F2F5F8">
<td width="100" class="NormalFont">
	<A style="COLOR: black; TEXT-DECORATION: none" href="callCustomerAccounts.php?id=<?php echo $No; ?>"><?php echo $No; ?></A>
	<input type="hidden" name="hdDialTo" id="hdDialTo" value="<?php echo $No;?>" />
</td>
<td width="100" class="NormalFont">
	<span id="responseStatus<?php echo $rowusers['employeeId'];?>" ><?php echo $rowusers['employeeId'];?></span>
	<input type="hidden" name="hdDialStatus" id="hdDialStatus" value="<?php echo $rowusers['employeeId'];?>" />
</td>
<td width="150" class="NormalFont">
	<A style="COLOR: black; TEXT-DECORATION: none"><?php echo $rowusers['extensionNo'];?></A>
	<input type="hidden" name="extensionNo" id="extensionNo" value="<?php echo $rowusers['extensionNo'];?>" />
</td>
<td width="150" class="NormalFont">
	<A style="COLOR: black; TEXT-DECORATION: none" ><?php echo $asignCallCount;?></A>
	<input type="hidden" name="hdDialToDate" id="hdDialToDate" value="<?php echo $callCount;?>" />
</td>
<td width="150" class="NormalFont">
	<A style="COLOR: black; TEXT-DECORATION: none" ><?php echo $callCount;?></A>
	<input type="hidden" name="hdDialToDate" id="hdDialToDate" value="<?php echo $callCount;?>" />
</td>
<td width="150" class="NormalFont">
	<span id="responseComment<?php echo $No; ?>"><A style="COLOR: black; TEXT-DECORATION: none" href="previewAgentReport.php?ID=<?php echo $rowusers['extensionNo']; ?>">View</A></span>
	<input type="hidden" name="hdDialComment" id="hdDialComment" value="View" />
</td>
<?php $No = $No + 1;   }  ?>
</tr>

</table>
</div>
<?php include('footer1.php');?>
<script language="javascript">
function submitform()
{
	document.getElementById('catcampform').action="./previewDialingReport.php";
	document.getElementById('catcampform').submit();
}

</script>

</form>
</body>
</html>
