<?php
	include "check.php";
	require("connection.php");
	$hierarchyResult = mysql_query("SELECT *FROM hierarchy ORDER BY level");
	$modulesResult = mysql_query("SELECT *FROM modules ORDER BY moduleId");
	while($modules = mysql_fetch_row($modulesResult))
	{
		$modulesArr[] = $modules[0];
		$modulesNamesArr[] = $modules[1];
	}
	$hierarchyNumber = mysql_num_rows($hierarchyResult);
?>
<form method="POST" action="privilegesProcess.php">
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
	<td width="100%">
	<?php
		while($hierarchy = mysql_fetch_row($hierarchyResult))
		{
			$modulesAssigned = explode(",",$hierarchy[3]);
			echo "<div style='width:97%;background:#fff;margin-top:15px;font-weight:bold;padding-left:15px;'>".str_replace("~"," ",$hierarchy[1])."</div>";
			echo "<table width='100%' align='center' cellpadding='3' cellspacing='3'>";
			echo "<tr>";
			for($i=0,$j=1;$i<count($modulesArr);$i++,$j++)
			{
				if(in_array($modulesArr[$i],$modulesAssigned))
					echo "<td><input type='checkbox' name='chk$hierarchy[2][]' checked value='$modulesArr[$i]' />&nbsp;$modulesNamesArr[$i]</td>";
				else 
					echo "<td><input type='checkbox' name='chk$hierarchy[2][]' value='$modulesArr[$i]' />&nbsp;$modulesNamesArr[$i]</td>";
				if($j%3 == 0)
					echo "</tr><tr>";
			}
			echo "</tr>";
			echo "</table>";
		}
	?>
	<input type="hidden" name="hierarchyNumber" value="<?php echo $hierarchyNumber; ?>" />
	</td>
</tr>
<tr>
	<td><input type="submit" name="submit" value="Save" /></td>
</tr>
</table>
</form>