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
