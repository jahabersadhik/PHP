<?php
	$extensionsQuery = mysql_query("SELECT * FROM is_queue",$connection);
	$i=1;
	while($row1 = mysql_fetch_row($extensionsQuery))
	{
		$queuename = $row1[1];
		$agents = $row1[2];
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
				<td colspan="2" align="center"><span class="heading">View Queues</span></td>
			</tr>
			<tr>
				<td colspan="6">&nbsp;</td>
			</tr>
			<tr>
				<td class="stylefour">Queue No.</td>
				<td class="stylefour">Agents</td>
			</tr>
				
<?php
		}
?>
		<tr>
			<td class="<?=$classname?>"><?=$queuename?></td>
			<td class="<?=$classname?>"><?=$agents?></td>
		</tr>
<?php
		$i++;
	}
?>
	</table>
