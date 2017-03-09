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
if($_POST['id']  && $_POST['processname'])
{
	$queuename = $_POST['queuename'];
	$processname = $_POST['processname'];
	$previousvalue = $_POST['previousvalue'];
	
	mysql_query("DELETE FROM is_process WHERE id='".$_POST['id']."'",$connection);
	$timestamp = date("Y-m-d G:i:s");
	$insertTracking = mysql_query("INSERT INTO is_tracking VALUES(NULL,'".$_SESSION['userid']."','Inbound process Delete','$previousvalue','','$timestamp')",$connection);
	echo "<script language='javascript'>
			alert('Process Updated Successfully');
			location.href = 'home.php?".$_SERVER['QUERY_STRING']."'
		  </script>
		 ";
}
else 
{
	$processQuery = mysql_query("SELECT *FROM is_process WHERE processtype=0",$connection);
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
				<td colspan="3" align="center"><span class="heading">Delete In-Bound Process</span></td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td class="stylefour">Process Name</td>
				<td class="stylefour">Assigned Queue</td>
				<td class="stylefour">Delete</td>
			</tr>
				
<?php
		}
?>
		<tr>
			<td class="<?=$classname?>"><?=$processname?></td>
			<td class="<?=$classname?>"><?=$queuename?></td>
			<td class="<?=$classname?>">
				<form method="POST" action="home.php?<?=$_SERVER['QUERY_STRING']?>">
					<input type="hidden" name="id" value="<?=$row1[0]?>" />
					<input type="hidden" name="processname" value="<?=$row1[2]?>" />
					<input type="hidden" name="previousvalue" value="<?php echo $row1[2]."<br>".$row1[3]."<br>";?>" />
					<input type="image" src="images/delete.png" style="border:0px;" />
				</form>
			</td>
		</tr>
<?php
		$i++;
	}
?>
	</table>

<?php
}
?>