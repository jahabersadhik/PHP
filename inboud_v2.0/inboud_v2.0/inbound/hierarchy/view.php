<?php
$processQuery = mysql_query("SELECT *FROM is_process",$connection);
	$i=1;
	while($row1 = mysql_fetch_row($processQuery))
	{
		$queuename = $row1[3];
		$processname = $row1[2];
		if($i%2==0 && $i!=0)
			$classname = "stylefourtext";
		else 	
			$classname = "stylefourtext1";
?>
<?php
		if($i==1)
		{
?>
			<table width="100%" cellpadding="0" cellspacing="1">
			<tr>
				<td colspan="3" align="center"><span class="heading">View In-Bound Process</span></td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td class="stylefour">Process Name</td>
				<td class="stylefour">Assigned Queue</td>
			</tr>
				
<?php
		}
?>
		<tr>
			<td class="<?=$classname?>"><?=$processname?></td>
			<td class="<?=$classname?>"><?=$queuename?></td>
			
		</tr>
<?php
		$i++;
	}
?>
	</table>
