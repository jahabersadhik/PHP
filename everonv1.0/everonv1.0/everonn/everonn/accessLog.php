<?php
require_once("mysql_connection.php");
if(!isset($_SESSION['hid']))
{
	header("Location: index.php");// redirect out
	die(); // need this, or the rest of the script executes
}
if($_SESSION['username'] == 'admin')
{
	if($_REQUEST['seluser']!='0' && $_POST)
		$query = "SELECT * FROM userAccessTracking WHERE userName = '".$_POST['seluser']."' ORDER BY trackDate DESC";
	else
		$query = "SELECT * FROM userAccessTracking ORDER BY trackDate DESC";
}
else
{
	$query = "SELECT * FROM userAccessTracking WHERE userName = '".$_SESSION['username']."' ORDER BY trackDate DESC";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
<title>PERI iSystem Web Portal</title>
<link href="./css/style_new.css" rel="stylesheet" type="text/css" />
<style>
#stylefour li a:hover, #stylefour li .<?php echo $current; ?>
{
	color: #999;
	background: url(./images/templatemo_menu_hover.jpg) repeat-x;	
}
</style>
<script language="javascript">
		function submitprocess()
		{
			document.customerUsersList.method = 'POST';
			document.customerUsersList.action = 'accessLog.php';	
			document.customerUsersList.submit();	
		}
	</script>
</head>
<script language="javascript">
	var url_name = window.location.search.substring(1);
</script>
<form method=post name="customerUsersList" >
<div id="container">
		<div id="templatemo_container">
    <div id="templatemo_banner">
    	<div id="logo"></div>
        <div id="project_name"></div>
    </div>
    <div id="templatemo_menu_search">
        <div id="">
		<?php include("menu.php");

			if($Gtype != 'admin')
				$access = explode("~",$moduleid[2]);
		?>
		<div id="maincontainer_fullpannel" class="clearfix">
    		<div id="fullpannel">
        		<div class="f_top"><!-- space --></div>
            		<div class="f_mid">
				<div style=" height:auto; width:100%; overflow:auto;">
	<table width=100% cellpadding=1 cellspacing=1 border=0>			
		

		<tr align=right valign="top">
			<td colspan=6> 
			<?php
				if($_SESSION['username'] == 'admin')
				{
			?>
			<table cellpadding=0 cellspacing=0 align=center style='margin:auto;width:300px;'>
			<tr>
				<td width="200" valign="top" style="padding-top:10px;">	
				<?php
					
					$sql = "SELECT * FROM users";
					$result_users=mysql_query($sql,$connection)or die(mysql_error());
				?>
				<select name="seluser">
					<option value="0">-Select User-</option>
					<?php while ($row = mysql_fetch_array($result_users)) { ?>
					<option value="<?=$row['userName']?>" <?php if($row['userName']==$_REQUEST['seluser']) { ?> selected <?php } ?>><?=$row["fname"].$row["lname"]."[".$row["userName"]."]"?></option>
					<?php } ?>
				</select>
				</td>
				<td width="100">
				<div class="buttonwrapper" style="width:100%;margin:5px auto;">
					<a class="squarebutton" href="#" id='' onclick="submitprocess();"><span>Filter </span></a>
				</div>
				</td>
			</tr>
			</table>
			<?php
				}
			?>
			</td>
			</tr>
			<tr>
				<td width="50" class='stylefour'>Updated By</td >
				<td width="50" class='stylefour'>Time Stamp</td >
				<td width="50" class='stylefour'>Module</td >
				<td width="50" class='stylefour'>User/Extension</td >
				<td width="200" class='stylefour'>Previous State</td >
				<td width="200" class='stylefour'>Current State</td >
			</tr>
<?php
	$result=mysql_query($query,$connection)or die(mysql_error());
	while($row = mysql_fetch_array($result)) 
	{
		$count=0;
		if(stristr($row[3],'Extensions'))
		{
			$str = explode("~",$row[6]);
			if(count($str)>=2)
			$row[6] = "";
			for($j=0;$j<count($str);$j++)
			{
				if(stristr($str[$j],"contextname") ||stristr($str[$j],"fullname") ||stristr($str[$j],"vmsecret") ||stristr($str[$j],"secret"))
				{
					if(stristr($str[$j],"context") && $count==0)
					{
						$row[6].= str_replace(","," : ",$str[$j])."<br>";
						$count++;
					}	
					if(!stristr($str[$j],"context"))
						$row[6].= str_replace(","," : ",$str[$j])."<br>";
				}
			}
			$count=0;
			$str1 = explode("~",$row[5]);
			if(count($str1)>=2)
			$row[5] = "";
			for($j=0;$j<count($str1);$j++)
			{
				if(stristr($str1[$j],"contextname") ||stristr($str1[$j],"fullname") ||stristr($str1[$j],"vmsecret") ||stristr($str1[$j],"secret"))
				{
					if(stristr($str1[$j],"context") && $count==0)
					{
						$row[5].= str_replace(","," : ",$str1[$j])."<br>";
						$count++;
					}	
					if(!stristr($str1[$j],"context"))
						$row[5].= str_replace(","," : ",$str1[$j])."<br>";
				}
			}
		}
if($i%2==0 && $i!=0)
{
?>
<tr>
	<td width="50" class='stylefourtext' style="padding-top:5px;padding-bottom:5px;">
		<?php echo $row[1];?>
		</td>	
	<td width="50" class='stylefourtext' style="padding-top:5px;padding-bottom:5px;">
		<?php echo $row[2];?>
		</td>
	<td width="50" class='stylefourtext' style="padding-top:5px;padding-bottom:5px;">
		<?php echo $row[3];?>
		</td>
	<td width="50" class='stylefourtext' style="padding-top:5px;padding-bottom:5px;">
		<?php echo $row[4];?>
	</td>
	<td width="200" class='stylefourtext' style="padding-top:5px;padding-bottom:5px;" valign="top">
		<?php echo $row[5];?>
	</td>
	<td width="200" class='stylefourtext' style="padding-top:5px;padding-bottom:5px;" valign="top">
		<?php echo $row[6];?>
	</td>	
</tr>
<?php 
} 
else 
{ 
 ?>
<tr>
	<td width="50" class='stylefourtext1' style="padding-top:5px;padding-bottom:5px;">
		<?php echo $row[1];?>
		</td>	
	<td width="50" class='stylefourtext1' style="padding-top:5px;padding-bottom:5px;">
		<?php echo $row[2];?>
		</td>
	<td width="50" class='stylefourtext1' style="padding-top:5px;padding-bottom:5px;">
		<?php echo $row[3];?>
		</td>
	<td width="50" class='stylefourtext1' style="padding-top:5px;padding-bottom:5px;">
		<?php echo $row[4];?>
	</td>
	<td width="200" class='stylefourtext1' style="padding-top:5px;padding-bottom:5px;" valign="top">
		<?php echo $row[5];?>
	</td>	
	<td width="200" class='stylefourtext1' style="padding-top:5px;padding-bottom:5px;" valign="top">
		<?php echo $row[6];?>
	</td>
</tr>
<?php 
}
	$i++;
} ?>
</table>
</div>
			</div>
            <div class="f_bottom"></div>
        </div>
    </div>
<?php include("footer.php") ?>
</form>
</html>
