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
	header("Location: index.php");// redirect out
	die(); // need this, or the rest of the script executes
}
$message=$_SESSION['msg'];
$url= $_GET['id'];

	if($_REQUEST['id']!="")
	{
		$selectQuery = mysql_query("SELECT fname,lname,userName,status,customerId,typeOfUser FROM users WHERE userId = '".$_REQUEST['id']."'",$connection);
		$row = mysql_fetch_row($selectQuery);
		$fname = $row[0];
		$lname = $row[1];
		$uname = $row[2];
		$status = $row[3]; 
		$client = $row[4]; 
		$typeofuser = $row[5]; 
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
<script language="javascript">
function reload()
{
	document.customerAddUser.method="post";
	var url = window.location.search.substring(1);
	document.customerAddUser.action="./customerAddUsers.php?"+url;
	document.customerAddUser.submit();
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
function Check()
{
	id = document.getElementById('editid').value;
	document.customerAddUser.action = "./editCustomerUsersListProcess.php?id="+id;
	document.customerAddUser.submit();
	
}
</script>
</head>
<div id="container">
<div id="templatemo_container">
    <div id="templatemo_banner">
    	<div id="logo"></div>
        <div id="project_name"><img src="images/isys.png"></div>
    </div>
    <div id="templatemo_menu_search">
        <div id="templatemo_menu">
<?php
	include("menu.php");
	if($Gtype != 'admin')
		$access = explode("~",$permissionArr[1]);
?>
</head>
<form name="customerAddUser" method="post">
<div id="maincontainer_fullpannel" class="clearfix">
    <div id="fullpannel">
    	<div class="f_top"></div>
        <div class="f_mid">     
			    <table  cellSpacing="0" cellPadding="0" width="495" style="margin:auto;" border="0" align="center">
					<tr height="100%" align="center">
						<td>
							<table cellSpacing="0" cellPadding="5" align="center"   border="0" width=80%  >
								<tr><td>
									<table border=0 cellPadding="0" cellspacing="10" align="center">
										<tr>
											<td>
												<LABEL class=sublinks>First Name</LABEL>
											</td>
											<td>
								
											<input type="text" name="txtFname" value="<?php echo $fname; ?>" maxlength="50" size=26>
											</td>
										</tr>
										<tr>	
											<td>
												<LABEL class=sublinks>Last Name</LABEL>
											</td>
											<td>
								
											<input type="text" name="txtLname" value="<?php echo $lname; ?>" maxlength="50" size=26>
											</td>
										</tr>
										<tr>
											<td>
												<LABEL class=sublinks>User Name</LABEL>
											</td>
											<td>
								
											<input type="text" name="txtUname" value="<?php echo $uname; ?>" maxlength="50" size=26>
											</td>
										</tr>
									
								
										<tr>
											<td align="left">
												<LABEL class=sublinks>Status</LABEL>
											</td>
											<td align="left">
												<select name=Status  style="width:170Px;" class="input">
												    <option value="Active" <?php if($status=="Active") {?> select <?php } ?>>Active</option>
												    <option value="InActive" <?php if($status=="InActive") {?> select <?php } ?>>InActive</option>
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
											<td colspan=3 align="center"><font color="red"></font></td> 
										</tr>
									</table>
								</td></tr>
							</table>
						</td>
					</tr>
				</table>
				<input type="hidden" name="editid" id="editid" value ="<?php echo $_REQUEST['id'] ?>">
	</div>
	<div class="f_bottom"></div>
	</div>
    </div>
<?php include('footer.php');?>
</form>
</body>

</html>

