<?php
include("mysql_connection.php");

if(!isset($_SESSION['hid']))
{
	header("Location: index.php");// redirect out
	die(); // need this, or the rest of the script executes
}

$sql = "SELECT * FROM users WHERE typeOfUser != 'admin'";
$result_users=mysql_query($sql,$connection)or die(mysql_error());

if($_POST)
{
	$userModule = mysql_query("SELECT modules FROM users WHERE userId='".addslashes($cust_cId)."' AND typeOfUser = 'admin'",$connection);
	$row_val = mysql_fetch_row($userModule);
	$moduleid= explode(",",$row_val[0]);
	
	$userTypeModule = mysql_query("SELECT modules FROM users WHERE userId='".addslashes($_POST['seluser'])."'",$connection);
	$row_type_val = mysql_fetch_row($userTypeModule);
	$usermoduleid = explode(",",$row_type_val[0]);
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
<title>PERI iSystem Web Portal</title>
<!--<link href="./css/templatemo_style.css" rel="stylesheet" type="text/css" />-->
<link href="./css/style_new.css" rel="stylesheet" type="text/css" />
<script language="javascript">
		function redirect()
		{
			document.customerprivileges.method = 'POST';
			document.customerprivileges.action = 'userPrivileges.php';	
			document.customerprivileges.submit();	
		}
		function submitprocess()
		{
			document.customerprivileges.method = 'POST';
			document.customerprivileges.action = 'userPrivilegesprocess.php';	
			document.customerprivileges.submit();	
		}
</script>
</head>
<form method=post name="customerprivileges" >
<div id="container">
		<div id="templatemo_container">
    <div id="templatemo_banner">
    	<div id="logo"></div>
        <div id="project_name"></div>
    </div>
    <div id="templatemo_menu_search">
        <div id="">
<?php
	include("menu.php");
	if($Gtype != 'admin')
		$access = explode("~",$moduleid[3]);
?>
		
			<div id="maincontainer_fullpannel" class="clearfix">
			    <div id="fullpannel">
			    	<div class="f_top"></div>
				<div class="f_mid">     
				<div style="height: auto; width: 430px;margin:auto ">
					<table width=100% class=normalfont cellpadding=0 border=0>	
						
						<tr align=right>
							<td colspan=3> </td>
						</tr>
						
						<tr class=bold>
							<td width="110">Select User</td >
							<td width="110">
								<select name="seluser" onchange="javascript:redirect();">
									<option value="0">-------Select-------</option>
									<?php 
											while ($row = mysql_fetch_array($result_users)) 
											{ 
									?>
												<option value="<?=$row['userId']?>" <?php if($row['userId']==$_POST['seluser']) { echo "selected"; } ?>><?=$row["fname"]." ".$row["lname"]." [".$row["userName"]."]"?></option>
									<?php 	
											} 
									?>
								</select>
							</td >
							<td><!--<INPUT type="button" value="Back" name="back" onclick="javascript:location.href='headings.php';" class="NormalButton">-->&nbsp;</td>
						</tr>
						
						<tr height="30">
							<td colspan="3" align="center" style="color:#ff9900;"><?php echo $_SESSION['mes'];?></td>
						</tr>
						<?php 
							if($_POST)
							{ 
						?>
						<tr>
							<td colspan=3>
								<?php
								
									echo "<table width='100%' align='center' cellpadding='3' cellspacing='3'>";
									echo "<tr>";
									for($i=0; $i<count($moduleid);$i++)
									{
										$privilege = explode("~",$moduleid[$i]);
										$mod = explode("~",$usermoduleid[$i+1]);
										
										if($mod[1][0] == 1)
											$checked1 = "checked";
										else 
											$checked1 = "";
										if($mod[1][1] == 1)
											$checked2 = "checked";
										else 
											$checked2 = "";
										if($mod[1][2] == 1)
											$checked3 = "checked";
										else 
											$checked3 = "";
										if($mod[1][3] == 1)
											$checked4 = "checked";
										else 
											$checked4 = "";	
										
										if($i%2==0 && $i!=0)
											echo "</tr><tr>";
										$modulesResult = mysql_query("SELECT moduleId,moduleName,privilege FROM modules WHERE moduleId = $privilege[0]",$connection);
										$row = @mysql_fetch_row($modulesResult);
										if($row[0]!="")
											echo "<td>&nbsp;$row[1] <br> &nbsp;&nbsp;&nbsp;&nbsp;";
										if($row[2]=='1')
										{
											if($row[1]!="Extensions" && $row[1]!="User Privileges")
											{
											   echo "<input type='checkbox' name='chk$row[0]1' $checked1  />&nbsp;Add &nbsp;&nbsp;";
											}
											echo "<input type='checkbox' name='chk$row[0]2' $checked2   />&nbsp;Edit &nbsp;&nbsp;";
											if($row[1]!="User Privileges")
											{
											   echo "<input type='checkbox' name='chk$row[0]3' $checked3   />&nbsp;Delete &nbsp;";
											}
											if($row[1]=="Extensions")
											{
											   echo "<input type='checkbox' name='chk$row[0]4' $checked4   />&nbsp;Export &nbsp;";
											}
											if($row[2]=='0')
											{
											   echo "<input type='checkbox' name='chk$row[0]4' $checked4  />&nbsp;View &nbsp;&nbsp;";
											}
										        echo "<br></td>"; 
										        
										}
									}	
									echo "</tr>";
									echo "</table>";
									
								?>
							</td>
							<input type="hidden" name="modulecount" value="<?php echo $count = count($moduleid)-1; ?>">
							<input type="hidden" name="moduleid" value="<?php echo $row_val[0]; ?>">
						</tr>
						<tr height="20"></tr>
						<tr><td colspan="3" align="center">
						<div class="buttonwrapper" style="width:69%;">
			<a class="squarebutton" href="#" id='btnSubmit' onclick="javascript:submitprocess();"><span>submit </span></a>	
			</div>
</td>
						</tr>
						<?php } ?>
				</table>
				</div>
				</div>
<div class="f_bottom"></div>
	</div>
    </div>
<?php include('footer.php');unset($_SESSION['mes']);?>
</form>
</body>

</html>
