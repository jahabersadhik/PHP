<?php 

include("../connection.php");	

$today = date('Y-m-d');
$extensionResult = mysql_query("SELECT extension FROM loginLog WHERE empId='".$_SESSION['userName']."' AND logoutDate='0000-00-00'");
$extensionArr = mysql_fetch_row($extensionResult);
$extension = $extensionArr[0];
$callbackData = mysql_query("select *from predictiveDialing where dialedComment='Call Back' AND calledExtension='$extension' AND UNIX_TIMESTAMP(callBackDate) = UNIX_TIMESTAMP('$today')");

$customerData = mysql_query("select b.*,a.* from current as a JOIN master as b ON(b.phone=a.phone AND b.status!='Block' AND b.status!='DNC') where a.dialedextension='$extension' AND dialertype=2 AND calltype=0");

$statusQuery = mysql_query("SELECT status,totalbreak FROM loginLog WHERE empId='".$_SESSION['userName']."' AND extension='$extension' AND logoutDate='0000-00-00'");
$statusResult = mysql_fetch_row($statusQuery);
$currentStatus = $statusResult[0];
$totalbreak = $statusResult[1];?>
<?php
	$popup = mysql_query("select *from current where dialedextension='$extension' AND dialertype='2'");
	//$popup = mysql_query("select * from current where dialedextension='3210' AND dialertype='2'");
	if(mysql_num_rows($popup)>0)
	{
		$_SESSION['popup'] = '1';
		$popupResult = mysql_fetch_array($popup);
		echo "
				<script language='javascript'>
				window.open('./predictivedialeragent/agentPredictiveCallAccountInformation.php?id=".$popupResult['id']."','pop1','scrollbars = yes,resizable=yes,width=1000,height=800');	
				</script>
			 ";
	}
?>
<meta http-equiv="refresh" content="10;">
<table width=100% class=normalfont cellpadding=0 border=0 align=center>	
<tr align=center valign="top">
	<td align="center" class="NormalFont">
		<table width="100%" cellpadding="2" cellspacing="2" align="center">
		<tr>
			<td colspan="4">
			<?php
				echo "Total Break Time : $totalbreak&nbsp;Sec&nbsp;&nbsp;";
				if($currentStatus == 2)
					echo "<input type='button' value='Break Over' onclick=\"location.href='predictivedialeragent/changeBreak.php?val=bo&extn=$extension&totbreak=$totalbreak';\" /> ";
				else 	
					echo "<input type='button' value='Taking Break' onclick=\"location.href='predictivedialeragent/changeBreak.php?val=tb&extn=$extension&totbreak=$totalbreak';\" /> ";
			?> 
			</td>
		</tr>
		<tr>
			<td style="background:#669900;color:#fff;" width="30%">Current Call Details</td>
			<td colspan=3></td>
		</tr>
		<tr  bgcolor="#FF9900" class="headings">
			<th align='left' width="25%">Customer Name</th>
			<th align='left' width="20%">Phone No</th>
			<th align='left' width="45%">Address</th>
			<th align='left' width="10%">View</th>
		</tr>
		<?php
				if(@mysql_num_rows($customerData)>0)
				while($crm = mysql_fetch_array($customerData))
				{ 
				echo "<tr bgcolor='#f6f6f6'>
						<td align='left' class='NormalFont'>".$crm['firstname']." ".$crm['lastname']."</td>
						<td align='left' class='NormalFont'>".$crm['phone']."</td>
						<td align='left' class='NormalFont'>".$crm['address']."</td>
						<td align='left'></td>
					  </tr>	
					 ";
				}
				else
				{
					echo "<tr><td colspan=4 class=NormalFont>No Current CallsFound.Please Click on Next Record to get another Customer details</td></tr>";
				}
		
		?>
		<tr>
			<td colspan="4" height="20"></td>
		</tr>
		</table>
	</td>
</tr>
		

<tr>
	<td align=center class=NormalFont>
		<table width="100%" cellpadding="2" cellspacing="2" align="center">
			
		<tr>
			<td style="background:#669900;color:#fff;" width="30%">CallBack Calls Details</td>
			<td colspan=3></td>
		</tr>
		<tr  bgcolor="#ff9900" class="headings">
			<th align='left' width="30%">Customer Name</th>
			<th align='left' width="30%">Phone No</th>
			<th align='left' width="30%">Address</th>
			<th align='left' width="10%">View</th>
		</tr>
		<?php
			if(@mysql_num_rows($callbackData)>0)
			while($callback = @mysql_fetch_array($callbackData))
			{				
				echo "<tr bgcolor='#f6f6f6'>
						<td align='left' class='NormalFont'>".$callback['firstname']." ".$callback['lastname']."</td>
						<td align='left' class='NormalFont'>".$callback['phone']."</td>
						<td align='left' class='NormalFont'>".$callback['address']."</td>
						<td align='left'><a href='javascript:void(0)' onclick=\"window.open('predictivedialeragent/agentPredictiveCallAccountInformation.php?id=".$callback['dialingId']."','','scrollbars = yes,resizable=yes,width=1000,height=800');\" class=NormalFont>View&Call</a></td>
					  </tr>	
					 ";
			}
			else
			{
				echo "<tr><td colspan=4 class=NormalFont>No CallBack Calls.</td></tr>";
			}
		?>
		<tr>
			<td colspan="4" height="20"></td>
		</tr>
		</table>
	</td>
</tr>
</table>

