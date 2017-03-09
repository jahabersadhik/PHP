<?php
/***********************************************************************************
Created By : Mallikarjuna Rao
Created Date: 7th August 2010
Modified Date : 7th August 2010
Purpose : To Create an Inbound(0) or Outbound(1) Process which helps in generating reports
Linked Tables : is_process,is_hierarchy

***********************************************************************************/
?>
<?php
if(!$_POST)
{
	$existingProcess = mysql_query("SELECT id,processname FROM is_process",$connection);
	echo "<table width='100%' cellpadding='0' cellspacing='0'>";
	echo "<tr><td colspan='3'>&nbsp;</td></tr>";
	echo "<tr><td colspan='3' align='center'><span class='heading'>Edit Hierarchy</span></td></tr>";
	echo "<tr><td colspan='3'>&nbsp;</td></tr>";
	echo "<tr>
			<td class='stylefour'>Process Name</td>
			<td class='stylefour'>Hierarchy</td>
			<td class='stylefour'>Edit</td>
		  </tr>";
	$i=1;
	while($row = mysql_fetch_row($existingProcess))
	{
		if($i%2==0 && $i!=0)
		{
			$hierarchyQuery = mysql_query("SELECT b.firstname,b.lastname,b.id FROM is_hierarchy as a JOIN is_users as b ON(a.userid=b.userid) WHERE a.processid='$row[0]'",$connection);
			while($name = mysql_fetch_row($hierarchyQuery))
			{
				$names .= $name[0]." ".$name[1].",";
				$userid .= $name[2].",";
			}
			echo "<tr>
					<td class='stylefourtext'>$row[1]</td>
					<td class='stylefourtext'>$names</td>
					<td class='stylefourtext'>
						<form name='frmhierarchy' method='post' action='home.php?".$_SERVER['QUERY_STRING']."'>
						<input type='hidden' name='userid' value='$userid'>
						<input type='hidden' name='processid' value='$row[0]'>
						<input type='image' src='images/edit.png' />
					</form>
					</td>
				 </tr>";
			$names='';
		}
		else 
		{
			$hierarchyQuery = mysql_query("SELECT b.firstname,b.lastname,b.id FROM is_hierarchy as a JOIN is_users as b ON(a.userid=b.id) WHERE a.processid='$row[0]'",$connection);
			while($name = mysql_fetch_row($hierarchyQuery))
			{
				$names .= $name[0]." ".$name[1].",";
				$userid .= $name[2].",";
			}
			echo "<tr>
					<td class='stylefourtext1'>$row[1]</td>
					<td class='stylefourtext1'>$names</td>
					<td class='stylefourtext1'>
						<form name='frmhierarchy' method='post' action='home.php?".$_SERVER['QUERY_STRING']."' style='margin:0px;padding:0px;'>
						<input type='hidden' name='userid' value='$userid'>
						<input type='hidden' name='processid' value='$row[0]'>
						<input type='image' src='images/edit.png' style='border:0px;' />
						</form>
					</td>
				 </tr>";
			$names='';
		}
		$i++;
	}
	echo "</table>";
}	
else 
{
	
}
?>