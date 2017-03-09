<?php
	include "check.php";
	if(isset($_REQUEST['submit']))
	{
		//include "connection.php";
		$numberOfLevels = $_REQUEST['numberOfLevels'];
		$checkHierarchyResult = mysql_query("SELECT *FROM hierarchy",$connection);
		if(mysql_num_rows($checkHierarchyResult))
		{
			for($i=1;$i<=$numberOfLevels;$i++)
			{
				$name = 'name'.$i;
				$level = 'level'.$i;
				$designation = $_REQUEST[$name];
			 	$level = $_REQUEST[$level];
			 	//echo"UPDATE hierarchy SET level='$level' WHERE hierarchy='$designation'";
				mysql_query("UPDATE hierarchy SET level='$level' WHERE hierarchy='$designation'",$connection);
				echo "<br>";
			}
		}
		else 
		{
			for($i=1;$i<=$numberOfLevels;$i++)
			{
				$name = 'name'.$i;
				$level = 'level'.$i;
				$designation = $_REQUEST[$name];
				$level = $_REQUEST[$level];
				mysql_query("INSERT INTO hierarchy VALUES(NULL,'$designation','$level')",$connection);
			}
		}
		header("location:home.php?page=hierarchy");
	}
?>
<?php
	require_once("connection.php");
	
	$hierarchyResult = mysql_query("SELECT *FROM hierarchy",$connection);
	while($hierarchy = mysql_fetch_row($hierarchyResult))
		$hierarchyArr[$hierarchy[2]] = $hierarchy[1];
	$designationsResult = mysql_query("SELECT designation FROM userData GROUP BY designation",$connection);
	$numberOfLevels = mysql_num_rows($designationsResult);
?>
<script language="javascript">
	function Validate()
	{
		var num = document.getElementById('numberOfLevels').value;
		var count = 0;
		for(var i=1;i<=num;i++)
		{
			var elemId = 'level'+i;
			if(document.getElementById(elemId).options[document.getElementById(elemId).selectedIndex].value == '-1')
			{
				count++;
			}
		}
		if(count>0)
		{
			alert("Please select all the levels");
			return false;
		}
		return true;
	}
	function test(elemId)
	{
		var num = document.getElementById('numberOfLevels').value;
		var count = 0;
		var sel = document.getElementById(elemId).options[document.getElementById(elemId).selectedIndex].value;
		for(var i=1;i<=num;i++)
		{
			var elem = 'level'+i;
			if(elem!=elemId)
			if(sel == document.getElementById(elem).options[document.getElementById(elem).selectedIndex].value && sel!='-1')
				count++;
		}
		if(count>0)
		{
			alert("No two levels can be equal");
			document.getElementById(elemId).options[0].selected = '1';
		}
	}
</script>
<form method='post' action='home.php?page=hierarchy' onsubmit="return Validate();">
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
	<th align="left">Designation</th>
	<th align="left">Hierarchy Level</th>
</tr>
<?php
	$k=1;
	$checkQuery = mysql_query("SELECT hierarchy FROM hierarchy WHERE hierarchy NOT IN (SELECT designation FROM userData)",$connection);
	if(mysql_num_rows($checkQuery)>0)
	{
		while($r = mysql_fetch_row($checkQuery))
		{
			mysql_query("DELETE FROM `hierarchy` WHERE `hierarchy` = '$r[0]'",$connection);
		}
	}
	while($designations = mysql_fetch_row($designationsResult))
	{
		
		echo "
				<tr>
					<td height='25'>".str_replace('~',' ',$designations[0])."</td>
			 ";
		echo "<td><select name='level$k' id='level$k' onchange='test(\"level$k\")'>
				<option value='-1' selected>--Select Level--</option>";
		for($i=1;$i<=$numberOfLevels;$i++)
		{
			if($hierarchyArr[$i] == $designations[0])
				echo "<option value='$i' selected>Level$i</option>";
			else 	
				echo "<option value='$i'>Level$i</option>";
		}
		echo "</select></td>";
		echo "	
				</tr>
			 ";
		echo "<input type='hidden' name='name$k' value='$designations[0]' />";
		$k++;
		
	}
	
?>
<input type="hidden" name="numberOfLevels" id="numberOfLevels" value="<?php echo $numberOfLevels; ?>" />
<tr><td colspan="2">&nbsp;</td></tr>
<tr>
	<td colspan="2" align="center"><input type="submit" name="submit" value="Save" /></td>
</tr>
</table>
</form>
