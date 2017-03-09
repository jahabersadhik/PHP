<?php
//session_start();

include("../connection.php");
?>

<html>
<head>
<title>PERI Software Solutions iSystem Web Portal</title>
<script language="JavaScript" src="ts_picker.js"></script>
<link rel="stylesheet" type="text/css" href="../epoch_styles.css" /> <!--Epoch's styles--> 
<script type="text/javascript" src="../scripts/epoch_classes.js"></script>
<script language="JavaScript" src="../scripts/ajaxCreation.js"></script>
<script type="text/javascript">
var calendar1, calendar2, calendar3; /*must be declared in global scope*/ 
 function loadcal() { 
	 calendar2 = new Epoch('cal2','popup',document.getElementById('calendar2_container'),false); 
 }; 
</script>

<form method=post name="callCustomerAccounts" action="agentPreviewCallInformationProcess.php">
<table width=883 class=normalfont cellpadding=0 border=0>			
<?php
	
	$acctQuery = "select b.*,a.* from current as a JOIN master as b ON(b.phone=a.phone) where a.id = '".$_REQUEST['id']."' AND a.dialertype='1' AND a.calltype='0' ";
	//$acctQuery = "select * from current where id = '".$_REQUEST['id']."'";
	$accountResult=mysql_query($acctQuery)or die(mysql_error());
	$accountRow = mysql_fetch_array($accountResult);
	$agentExtensionNo = $accountRow['dialedextension'];
?>
	<input type="hidden" name="agentExtensionNo" value="<?php echo $_REQUEST['ae'];?>"/>	
	<input type="hidden" name="dialType" value="<?php echo $_REQUEST['act'];?>"/>	

			<tr align=right valign="top">
						<td colspan=2>&nbsp;
			</td>
			</tr>
			<tr align=center valign="top">
				<td colspan=7 align="center" class="NormalFont"><span id="responseText" ></span>				
				</td>
			</tr>
			<tr  bgcolor="#027BE6"  class=bold>
			  <tr> 
	        	 <td align=left> 
	        	 <label class="sublinks"> First Name</label>
	          	 </td>
	          	 <td class="NormalFont">
	          	 <label class="sublinks"><font color="red"><?php echo $accountRow['firstname'];?></font></label>
	          	 </td>
             </tr> 
             <tr> 
	        	 <td align=left> 
	        	 <label class="sublinks"> Last Name</label>
	          	 </td>
	          	 <td class="NormalFont">
	          	 <label class="sublinks"><font color="red"><?php echo $accountRow['lastname'];?></font></label>
	          	 </td>
             </tr>
               <tr> 
	        	 <td align=left> 
	        	 <label class="sublinks">Address</label>
	          	 </td>
	          	 <td class="NormalFont">
	          	 <label class="sublinks"><font color="red"><?php echo $accountRow['address'];?></font></label>
	          	 </td>
             </tr>
             <tr> 
	        	 <td align=left> 
	        	 <label class="sublinks"> Phone</label>
	          	 </td>
	          	 <td class="NormalFont">
	          	 <label class="sublinks"><font color="red"><?php echo $accountRow['phone'];?></font></label>
	          	 <input type="hidden" name="txtPhoneNumber" id="txtPhoneNumber" value="<?php echo $accountRow['phone'];?>" />
	          	 </td>
             </tr> 
			<tr> 
	        	 <td align=left> 
	        	 <label class="sublinks"> Email</label>
	          	 </td>
	          	 <td class="NormalFont">
	          	 <label class="sublinks"><font color="red"><?php echo $accountRow['email'];?></font></label>
	          	 <input type="hidden" name="txtEmail" id="txtPhoneNumber" value="<?php echo $accountRow['phone'];?>" />
	          	 </td>
             </tr> 
             <!--<tr> 
	        	 <td align=left> 
	        	 <label class="sublinks"> Call Disposition</label>
	          	 </td>
	          	 <td class="NormalFont">
	          	 <label class="sublinks">
	          	 <select name=callDisposition  style="width:170Px;" class="NormalTextBox">
				<option value="0" >-Select Call Disposition-</option>
				<option value="Abandoned Call">Abandoned Call</option>
				<option value="Escalated Call">Escalated Call</option>
				<option value="Invalid Call">Invalid Call</option>
				<option value="Do Not Call">Do Not Call</option>
				<option value="Dropped Call">Dropped Call</option>
				</select>
	          	 </label>
	          	 </td>
             </tr>-->
             <tr> 
	        	 <td align=left> 
	        	 <label class="sublinks"> Dialer Disposition</label>
	          	 </td>
	          	 <td class="NormalFont">
	          	 <label class="sublinks">
	          	<select name=userDisposition  style="width:170Px;" class="NormalTextBox">
				<option value="0" >-Select Dialer Disposition-</option>
				<option value="Block">Block List</option>
				<option value="Call Back">Call Back</option>
				<option value="Call Closed">Call Closed</option>
				<option value="DNC">Do Not Call</option>
				<option value="Not Interested">Not Interested</option>
				</select>
	          	 </label>
	          	 </td>
             </tr>
             <tr> 
	        	 <td align=left> 
	        	 <span class="sublinks">Call Back Date</span>
	          	 </td>
	          	 <td class="NormalFont">
	          	
	          	 <input type="text" id="calendar2_container" name="callbackdate" value="" />
	          	 </td>
		<td width="100" class="NormalFont">
	<INPUT type="button" value="Call" name="call" onclick="javascript : clickToCall('<?php echo $accountRow['phone'];?>','<?php echo $accountRow['id'];?>')" class="NormalButton">
</td>
             </tr>
             <tr> 
	        	 <td align=left> 
	        	 <label class="sublinks">Agent Comments</label>
	          	 </td>
	          	 <td>
	          	 <input type="hidden" name="hdAccountId" id="hdAccountId" value = "<?php echo $_REQUEST['id'];?>"/>
	          	 <textarea rows="10" cols="70" name=txtADesc class="MandatoryTextBox"></textarea>
	          	 </td>
             </tr> 
	     <tr> 
	        	 <td align=left> 
	        	 <label class="sublinks">Client Comments</label>
	          	 </td>
	          	 <td>
	          	 <textarea rows="10" cols="70" name=txtCDesc class="MandatoryTextBox"></textarea>
	          	 </td>
             </tr> 
	     <tr> 
	        	 <td align=left> 
	        	 <label class="sublinks">After Submiting</label>
	          	 </td>
	          	 <td>
	          	 <select name=selAfterSubmitting  style="width:170Px;" class="NormalTextBox">
				<option value="0" >-Select After Submiting-</option>
				<option value="loggedIn">Logged In</option>
				<option value="wentOut">Went Out</option>
				<option value="loggedOut">Logged Out</option>
			</select>
	          	 </td>
             </tr>
             <tr height=30% align="center">								
				<td colspan=2 align=center>
				<input type=submit class="NormalButton" value=" Submit " id=button6 name=button6 onclick="Check();">&nbsp;
				<input type=button class="NormalButton" value="Close " id=button6 name=button6 onclick="window.close();">
				</td>
			</tr>	

</table>
<?php 
	$No = 1 ;
	if($accountRow['comments']!="")
	{
		$commentsRow = split("~!",$accountRow['comments']);
		$count = count($commentsRow);
		for ($I=1; $I<$count; $I++)
		{ 
			$No = $I;
			$val = split("~",$commentsRow[$I]);
			$cDates=date("jS M, Y H:i:s",strtotime("$val[0]"));
			$comms = $val[1]; 
			$Ccomms = $val[2]; 
?>
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr bgcolor="#027BE6">
		<td><?php echo "<font color='white'>Comment #"." "."$No"."</font>"?></td>
		<td></td>						
		<td align="center"></td>
		<td></td>							
		<td align="right"><?php echo "<font color='white'>$cDates</font>";?></td>											
	</tr>
	</table>
	<table width="100%">
	<tr>
		<td><?php echo "Agent Comments:".$comms ."<BR><BR> Client Commentd:".$Ccomms ;?></td>
	</tr>
	</table>
	<?php  
		} 
	}
	?>
<?php $No = $No + 1; ?>
<input type ="hidden" name="dialingId" value="<?php echo $_REQUEST['id'];?>">
</form>
<script language="javascript">
loadcal();
function ShowNext(a) {

	var b=a;
	var win = null;
        if (win == null || win.closed) {
            win = window.open("", "newwin", "width=200, height=150");
            win.document.write("<html><head><title>Confirm Dialog</title><\/head>");
            win.document.write("<body><center><b>Are You Sure You Want To DELETE?<br><br></b><input type='button' value='YES' onclick='window.close();window.opener.SaveTonextPage("+b+");'><\/input> ");
            win.document.write("<input type='button' value='NO' onclick='window.close();window.opener.nextPage();'><\/input></center><\/body><\/html>");
        }
       win.focus();
    }
//document.write(b);
function SaveTonextPage(a1){
var c=a1;
location.href="deleteCustomerUsersProcess.php?id="+c;
}
function nextPage(){
location.href="customerUsersList.php";
}
function loadAccountInformation(){
window.open("agentAccountInformation.php",null,
"height=600,width=800,left = 350,top = 300,status=no,status=yes,toolbar=no,menubar=no,location=no");
}
function Check()
{ 
	if (IsEmpty(callCustomerAccounts.txtADesc.value)){
		alert("Agent comments cannot be empty");
		callCustomerAccounts.txtADesc.focus();
	} else if(IsEmpty(callCustomerAccounts.txtCDesc.value)){
		alert("Client comments cannot be empty");
		callCustomerAccounts.txtCDesc.focus();
	} else{ 
		//callCustomerAccounts.flag.value = "1";
		callCustomerAccounts.action = "./agentPreviewCallInformationProcess.php";
		callCustomerAccounts.submit();
	}
}
function IsEmpty(MyValue)
{
	if (MyValue.replace(/\s+/g,"").length<=0)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function clickToCall(toNumber,id)
{	
	{
		fromNumber = "<?php echo $agentExtensionNo; ?>";
		var xmlhttp = ajaxFunction();
		xmlhttp.onreadystatechange=function()
		{
			if(xmlhttp.readyState==4){
				if(xmlhttp.responseText){
			  		document.getElementById('responseText').innerHTML = "Call Activated Successfully";
				}else{
					document.getElementById('responseText').innerHTML = "Call Activation Failed";
				}
				setTimeout('location.href=location.href',10000);
				//}
			}
		}
		var url = "previewAjaxClickToCall.php";  
		var parameters = "fromNumber="+fromNumber+"&toNumber="+toNumber+"&type=preview&dialingId=<?php echo $_REQUEST['id']; ?>";  
		xmlhttp.open("GET", url+"?"+parameters, true);
		xmlhttp.send(null);
	}
}

</script>
</body>
</html>
