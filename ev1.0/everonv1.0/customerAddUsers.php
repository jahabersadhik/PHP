<?php
session_start();
include("mysql_connection.php");
$loginname=$_SESSION['hid']; 
$name=$_SESSION['hidd'];
$cust_cId=$_SESSION['u_Customer_Id'];
$cust_uId=$_SESSION['c_Customer_Id'];
$Gtype=$_SESSION['group'];
$C_IP=$_SESSION['c_ip'];

$FSERVER=$_SESSION['Fserver'];
$Fusername=$_SESSION['Fusername'];
$Fpassword=$_SESSION['Fpassword'];
$custApp_name_message=$_SESSION['Name'];

if(!isset($_SESSION['hid']))
{
	header("Location: index.php");
	die(); 
}
$message=$_SESSION['msg'];
$url= $_GET['id'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
<title>PERI iSystem Web Portal</title>
<link href="./css/templatemo_style.css" rel="stylesheet" type="text/css" />
<link href="./css/style_new.css" rel="stylesheet" type="text/css" />
<style>
#stylefour li a:hover, #stylefour li .<?php echo $current; ?>
{
color: #999;
background: url(./images/templatemo_menu_hover.jpg) repeat-x;	
}
</style>
<script language="javascript">
function reload()
{
	document.customerAddUser.method="post";
	var url = window.location.search.substring(1);
	document.customerAddUser.action="./customerAddUsers.php?"+url;
	document.customerAddUser.submit();
}

</script>
</head>
<form name="customerAddUser" method="post">
<div id="container">
		<div id="templatemo_container">
    <div id="templatemo_banner">
    	<div id="logo"></div>
        <div id="project_name"></div>
    </div>
    <div id="templatemo_menu_search">
        <div id="">
		<?php include('menu.php'); ?>
	<div id="maincontainer_fullpannel" class="clearfix">
    		<div id="fullpannel">
    			<div class="f_top"><!-- space --></div>
        		<div class="f_mid">     
				<table  cellSpacing="0" cellPadding="0" width="495" style="margin:auto;" border="0" align="center">
					<tr height="100%">
					<td>
						<table cellSpacing="0" cellPadding="0" border="0" width=100% >
							<tr><td>
								<table border=0 cellspacing="10" cellPadding="0" align="center" width="100%">
									<tr>
										<td>
											<LABEL class=sublinks>First Name</LABEL>
										</td>
										<td>
								
										<input type="text" name="txtFname" maxlength="50" size=26 class="input">
										</td>
									</tr>
									<tr>	
										<td>
											<LABEL class=sublinks>Last Name</LABEL>
										</td>
										<td>
								
										<input type="text" name="txtLname" maxlength="50" size=26 class="input">
										</td>
									</tr>
									<tr>
										<td>
											<LABEL class=sublinks>User Name</LABEL>
										</td>
										<td>
								
										<input type="text" name="txtUname" maxlength="50" size=26 class="input">
										</td>
									</tr>
									<tr>	
										<td>
											<LABEL class=sublinks>Password</LABEL>
										</td>
										<td>
								
										<input type="password" name="txtpassword" maxlength="50" size=26 class="input">
								</td>
						</tr>
						<tr>	
							<td>
								<div id='visible' style='display:none'>
								<LABEL class=sublinks>Select Client</LABEL>
								</div>
							</td>
							<td>
								<div id='visible1' style='display:none' >
								<select name='seluser' class='input' style='width:170px;'>
									<option value='1'>PERI SOFTWARE SOLUTION</option>
					<?php
					$sql_select_customer = mysql_query("SELECT customerId,customerName FROM customerDetails");
					while($row = mysql_fetch_array($sql_select_customer))
					{ 
					?>
					<option value="<?=$row['customerId']?>"><?php echo $row['customerName']; ?></option>
					<?php
					}
					?>
								</select>
								</div>
							</td>
						</tr>
							
									<tr>
										<td align="left">
											<LABEL class=sublinks>Status</LABEL>
										</td>
										<td align="left">
											<select name=Status  style="width:170Px;" class="input">
											    <option value="Active">Active</option>
											    <option value="InActive">InActive</option>
											</select>
										</td>
									</tr>
									<tr height=30%>								
										<td colspan=2 align=center>
		
		<div class="buttonwrapper" style="width:69%;">
			<a class="squarebutton" href="#" id='save' onclick="javascript:return Check();"><span>Save </span></a>
			<a class="squarebutton" href="#" id='back' onclick="javascript:location.href='./customerUsersList.php';" style="margin-left:10px;"><span>Back </span></a>
		</div>

										</td>
									</tr>
									<tr>
										<td colspan="3" ><LABEL class=sublinks>* Password value should be more than 8  
               <br>characters and less than 16 characters in length<br><br>Password should contain atleast one number and one special character</LABEL></td> 
									</tr>
								</table>
							</td></tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
		<div class="f_bottom"></div>
	</div>
    </div>
<?php include('footer.php');?>
<script>
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
function Check()
{
	var rxPassword = new RegExp(/(([\d].*[^\w])|([^\w].*[\d])|([^\w].*[\d]))+/);
	var val = /[A-Z]/;
	var sPassword = new String();	
	sPassword = document.customerAddUser.txtpassword.value; // Should be false, missing special character
	if (IsEmpty(document.customerAddUser.txtFname.value))
	{
		alert("Please enter the first name.");
		document.customerAddUser.txtFname.focus();
		return false;
	}			
	else if (IsEmpty(document.customerAddUser.txtLname.value))
	{		
		alert("Please enter the last name.");
		document.customerAddUser.txtLname.focus();
		return false;
	}
	else if (IsEmpty(document.customerAddUser.txtUname.value))
	{		
		alert("Please enter the username.");
		document.customerAddUser.txtUname.focus();
		return false;
	}
	else if (IsEmpty(document.customerAddUser.txtpassword.value))
	{		
		alert("Please enter the password.");
		document.customerAddUser.txtpassword.focus();
		return false;
	}
	else if(document.customerAddUser.txtpassword.value =="")
	{
		alert("Enter PassWord");
		document.customerAddUser.txtpassword.focus();
	}
	else if(document.customerAddUser.txtpassword.value.length <= 8)
	{
		//alert(changePassWord.txtnew.value.length);
		alert("Password value should be more than 8 characters and less than 16 characters in length");
		document.customerAddUser.txtpassword.focus();
	}
	else if(!val.test(document.customerAddUser.txtpassword.value))
	{
		//alert(changePassWord.txtnew.value.length);
		alert("Password should have atleast one capital letter");
		document.customerAddUser.txtpassword.focus();
	}
 	else if(!rxPassword.test(sPassword))
	{
	 	alert("Password should contain atleast one number and one special character");
		document.customerAddUser.txtpassword.focus();
	 }
	else
	{
		document.customerAddUser.action = "./customerAddUsersProcess.php";
		document.customerAddUser.submit();
	}
}
</script>
</form>
</body>

</html>

