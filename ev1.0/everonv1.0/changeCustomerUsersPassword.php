<?php
/*****************************************************************************
 *	File Name   : changeCustomerUsersPassword.php                            *
 *	Created by	: Madhusudhanan V P                                          *
 *	Version     : 1.0.0.0.0.0                                                *
 *	Connection  : mysql_connection.php                                       *
 *	Tables      : users                                                      *
 *	Links       : index.php,frame.php,changeCustomerUsersPasswordProcess.php,*
 *                customerUsersList.php                                      *
 *	Created On  : 16 January 2009                                            *
 *	Last Updated:                                                            *
 *	Updated By  :                                                            *
 *****************************************************************************/
session_start();
include("mysql_connection.php");

$uid=$_SESSION['hidden'];
$password=$_SESSION['pass'];

$loginname=$_SESSION['hid']; 
$name=$_SESSION['hidd'];
$cust_cId=$_SESSION['u_Customer_Id'];
$cust_uId=$_SESSION['c_Customer_Id'];
$Gtype=$_SESSION['group'];
$C_IP=$_SESSION['c_ip'];

if(!isset($_SESSION['hid'])){
header("Location: index.php");// redirect out
die(); // need this, or the rest of the script executes
}

$user_cpwd_id=$_GET['id'];

			$sql_lname="SELECT userName,passWord,status FROM users WHERE userId='$user_cpwd_id';";
			$result_lname=mysql_query($sql_lname,$connection)or die(mysql_error());
			$row_lname= mysql_fetch_array($result_lname);
			$Login_Name=$row_lname['userName'];
			$pass_word=$row_lname['passWord'];
			$status=$row_lname['status'];
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
<script language="JavaScript" src="ts_picker.js"></script>
<link rel="stylesheet" type="text/css" href="./css/epoch_styles.css" /> <!--Epoch's styles--> 
<script type="text/javascript" src="./js/epoch_classes.js"></script>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"  class=NormalFont>
<form method=post name="changeCustomerUsersPassWord" >
<div id="container">
	<div id="templatemo_container">
	    <div id="templatemo_banner">
	    	<div id="logo"></div>
	        <div id="project_name"></div>
	    </div>
    	    <div id="templatemo_menu_search">
            	<div id="templatemo_menu">
			<?php 	include('menu.php'); ?>
			<div id="maincontainer_fullpannel" class="clearfix">
		    		<div id="fullpannel">
		    			<div class="f_top"></div>
		        		<div class="f_mid"> 
<table width="100%" height="340" border="0" cellpadding="0" cellspacing="0">
     <tr>
       <td align=center>
       <table border="0"  align=center>
         <tr>
         <td>
            <table cellspacing="2" cellpadding="4" border="0" width="100%" class=headings>
	       <tr> 
        	  <td> 
        	  <LABEL class=sublinks>Login Name</LABEL>
          	  </td>
          	  <td><LABEL class=sublinks><?php echo "$Login_Name";?></LABEL>
               </tr>  
	       <tr> 
        	  <td> 
        	  <LABEL class=sublinks>Old Password</LABEL>
          	  </td>
          	  <td><input type="password" name=txtold class=MandatoryTextbox maxlength=15 value="<?php echo $pass_word;?>" readonly>
               </tr>
	       <tr> 
        	  <td> 
        	  <LABEL class=sublinks>New Password</LABEL>
          	  </td>
          	  <td><input type="password" name=txtnew class=MandatoryTextbox maxlength=15>
               </tr>
	       <tr> 
        	  <td> 
        	  <LABEL class=sublinks>Confirm Password</LABEL>
          	  </td>
          	  <td><input type="password" name=txtconfirm class=MandatoryTextbox maxlength=15>
               </tr>                              

		<TR>
								<TD align="left"><LABEL class=sublinks>Status</LABEL></TD>
								<TD align="left"><select name=Status class="NormalTextBox">
								                    <option value="Active"<?php if($status=="Active"){?> selected <?php } ?>>Active</option>
								                    <option value="InActive"<?php if($status=="InActive"){?> selected <?php } ?>>InActive</option>
								</select></TD>
								
							</TR>

              <tr height=30%> 
        	  <td  align="center" width="100%" colspan=2> 
          	     <input type="button" class="save"  onClick="gosubmit()" id=button1 name=button1>
          	     &nbsp;
          	     <input type="button" class="back" onClick="javascript:location.href = './customerUsersList.php'" id=button2 name=button2> </td>
         		<td>
          	      
          	  </td>
               </tr>
            </table>
                                   <table>
                          <tr>
                              <td>
               <LABEL class=sublinks>* Password value should be more than 8  
               <br>characters and less than 16 characters in length<br><br>Password should contain atleast one number and one special character</LABEL>
               </td>
               </tr>
           </table>
             </td>
           </tr>
           </table>
           
       </td>
     </tr>
   </table>
	<input type ="hidden" name="flag" value="0">
	<input type ="hidden" name="oldpass" value="<?php echo $password;?>">
</div>
            <div class="f_bottom"></div>
        </div>
    </div>
    <?php include('footer.php');?>
</div>
</form>
</body>
<script language="javascript">
var pwd_url = window.location.search.substring(1);

 function gosubmit()
  {	
  	var rxPassword = new RegExp(/(([\d].*[^\w])|([^\w].*[\d])|([^\w].*[\d]))+/);
	var val = /[A-Z]/;
	var sPassword = new String();	
	sPassword = document.changeCustomerUsersPassWord.txtnew.value; // Should be false, missing special character
	if(document.changeCustomerUsersPassWord.txtnew.value =="")
	{
		alert("Enter PassWord");
		document.changeCustomerUsersPassWord.txtnew.focus();
	}
	else if(document.changeCustomerUsersPassWord.txtconfirm.value =="")
	{
		alert("Enter Confirm Password");
		document.changeCustomerUsersPassWord.txtconfirm.focus();
	}
	else if(document.changeCustomerUsersPassWord.txtnew.value.length <= 8)
	{
		//alert(changePassWord.txtnew.value.length);
		alert("Password value should be more than 8 characters and less than 16 characters in length");
		document.changeCustomerUsersPassWord.txtnew.focus();
	}
	else if(!val.test(document.changeCustomerUsersPassWord.txtnew.value))
	{
		//alert(changePassWord.txtnew.value.length);
		alert("Password should have atleast one capital letter");
		document.changeCustomerUsersPassWord.txtnew.focus();
	}
 	else if(document.changeCustomerUsersPassWord.txtconfirm.value.length <= 8)
	{
		alert("Password value should be more than 8 characters and less than 16 characters in length");
		document.changeCustomerUsersPassWord.txtconfirm.focus();
	}	 
	else if(document.changeCustomerUsersPassWord.txtnew.value != document.changeCustomerUsersPassWord.txtconfirm.value )
	 {
		alert("Mismatch of New password and Confirm password!");
		document.changeCustomerUsersPassWord.txtnew.focus();
	 }else if(!rxPassword.test(sPassword)){
	 	alert("Password should contain atleast one number and one special character");
		document.changeCustomerUsersPassWord.txtnew.focus();
	 }
	else
	 {
		document.changeCustomerUsersPassWord.flag.value = 1;
		document.changeCustomerUsersPassWord.action = "./changeCustomerUsersPasswordProcess.php?"+pwd_url;
		document.changeCustomerUsersPassWord.submit();     
     }
  }
</script>

</html>
