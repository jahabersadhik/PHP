<?php
	session_start();
	include "check.php";
//	require('connection.php');

?>
<html>
<head>
	<title>iSystem-Call Center Application</title>
</head>
<style type="text/css">
body,table
{
	font-family:Arial;
	font-size:12px;
	color:#0f0f0f;
	padding-top:0px;
	margin-top:0px;
}
i
{
	font-size:11px;
	color:#ff0000;
}
a
{
	text-decoration:underline;
	color:#000;
	font-size:12px;
	font-weight:bold;
}
a:hover
{
	text-decoration:none;
	color:#000;
	font-size:12px;
	font-weight:bold;
}
</style>
<link rel="stylesheet" type="text/css" href="./epoch_styles.css" />
<?php
if($_REQUEST['page']!='record'&& $_REQUEST['page']!='qa')
{
?>
<script type="text/javascript" src="./epoch_classes.js"></script>
<?php
}
else
{
?>
<script type="text/javascript" src="./epoch_classes1.js"></script>
<?php
}
?>
<script type="text/javascript" src="scripts/ajaxCreation.js"></script>
<script type="text/javascript" src="./js/jquery.js"></script>
<script src="./js/thickbox-compressed.js" type="text/javascript"></script>
<link rel="stylesheet" href="./css/thickbox.css" type="text/css" media="screen" />

<?php
	if($_REQUEST['page'] == 'process' || $_REQUEST['page'] == 'activityreport' || $_REQUEST['page'] == 'activityreportout' || $_REQUEST['page'] == 'record')
	{
?>
<script language="javascript">
var calendar2,calendar1; /*must be declared in global scope*/ 
	function loadcal() 
	{ 
		 calendar2 = new Epoch('cal2','popup',document.getElementById('startDate'),false); 
		 calendar1 = new Epoch('cal1','popup',document.getElementById('endDate'),false); 
	}
</script>
<?php
	}
?>
<?php
	if($_REQUEST['page'] == 'teams')
	{
?>
<script language="javascript">
var calendar1; /*must be declared in global scope*/ 
	function loadcal() 
	{ 
		 calendar1 = new Epoch('cal1','popup',document.getElementById('doj'),false); 
	}
</script>
<?php
	}
?>
<body style="padding-top:0px;margin-top:0px;">
<table width="800" align="center" cellpadding="0" cellspacing="0" style="border:1px solid #000000;background:url(images/bg.png);">
<tr>
	<td colspan="2" height="100"><img src="images/headerimg.png" width="800" height="100" /></td>
</tr>
<tr>
	<td width="22%" valign="top" style="padding:10px;border-right:1px solid #ff9900;height:438px;">
		<?php include "menu.php";?>
	</td>
	<td width="78%" valign="top" style="padding:10px;">
	<div style="width:100%;height:auto;">
		<?php include "body.php";?>
	</div>		
	</td>
</tr>

</table>
<?php
	if($_REQUEST['page'] == 'teams' || $_REQUEST['page'] == 'process' || $_REQUEST['page'] == 'activityreport' || $_REQUEST['page'] == 'activityreportout' || $_REQUEST['page'] == 'record')
	{
?>
<script language="javascript">
	loadcal(); 
</script>
<?php
	}
	if($_REQUEST['page'] == 'teams' && $_REQUEST['id']=='')
	{
?>
<script language="javascript">
	document.getElementById('addemployee').style.display = 'none';
</script>
<?php
	}
?>

</body>
</html>
