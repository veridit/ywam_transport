<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	
	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$sMessage		=	"";
	$iSHOP_TASK_ID	=	0;
	
	if(isset($_REQUEST["stid"]) && $_REQUEST["stid"]!=""){$iSHOP_TASK_ID	=	$_REQUEST["stid"];}
	
	if(isset($_POST["action"])	&& $_POST["action"]=="editshop"){
	
			$bCOMPLETED_TASK	=	0;
						
			$sWORKSTART_DATE		=	substr($_POST["txtworkdate"],6, 4);
			$sWORKSTART_DATE		.=	"-".substr($_POST["txtworkdate"],0, 2);
			$sWORKSTART_DATE		.=	"-".substr($_POST["txtworkdate"],3, 2);
		
			$sNEXTOIL_DATE		=	substr($_POST["txtnextoil"],6, 4);
			$sNEXTOIL_DATE		.=	"-".substr($_POST["txtnextoil"],0, 2);
			$sNEXTOIL_DATE		.=	"-".substr($_POST["txtnextoil"],3, 2);
				
			
			if(isset($_POST["chktaskcompleted"]) && $_POST["chktaskcompleted"]!="")		$bCOMPLETED_TASK	=	1;	else	$bCOMPLETED_TASK	=	0;
			
			$sSQL="UPDATE  tbl_shop_tasks SET miles_reading_tech='', last_mileage='".$_POST["txtlastmileage"]."', ".
			"work_type_id=".$_POST["drpworktype"].", work_start_date='".$sWORKSTART_DATE."', next_oil = '".$sNEXTOIL_DATE."', total_cost=".$_POST["txtcost"].", parts_source='".$_POST["txtparts"]."', ".
			"task_complete = ".$bCOMPLETED_TASK.", tech_comments='".addslashes($_POST["txtcomments"])."', ".
			"invoice_no = '', vendor_name = '".$_POST["txtvendor"]."' ".
			"WHERE task_id = ".$iSHOP_TASK_ID;
			
			//print($sSQL);
			$rsSHOPTASK=mysql_query($sSQL) or die(mysql_error());
			
			$sMessage		=	fn_Print_MSG_BOX("shop task modified successfully", "C_SUCCESS");
					
				
	}
$sSQL	=		"SELECT tbl_shop_tasks.*, tbl_vehicles.vehicle_no, tbl_vehicles.oil_filter, tbl_vehicle_brand.brand_name, tbl_vehicle_type.v_type FROM tbl_shop_tasks ".
"INNER JOIN tbl_vehicles ON tbl_shop_tasks.vehicle_id = tbl_vehicles.vehicle_id ".
"INNER JOIN tbl_vehicle_brand ON tbl_vehicles.make_id = tbl_vehicle_brand.brand_id ".
"INNER JOIN tbl_vehicle_type ON tbl_vehicles.model = tbl_vehicle_type.v_type_id ".
"WHERE task_id = ".$iSHOP_TASK_ID;
$rsSHOP_TASK	=		mysql_query($sSQL) or die(mysql_error());
$iRECORD_COUNT	=mysql_num_rows($rsSHOP_TASK);	
if($iRECORD_COUNT>0){
	$rowTASK	=	mysql_fetch_array($rsSHOP_TASK);
}else{

	$sMessage		=	fn_Print_MSG_BOX("no user found!", "C_ERROR");
	
}mysql_free_result($rsSHOP_TASK);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>
<head>
<title>Modify Shop Task</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">

<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">
<!-- firebug lite -->
		<script type="text/javascript" src="./js/firebug.js"></script>

        <!-- jQuery -->
		<script type="text/javascript" src="./js/jquery.min.js"></script>
        
        <!-- required plugins -->
		<script type="text/javascript" src="./js/date.js"></script>
		<!--[if lt IE 7]><script type="text/javascript" src="scripts/jquery.bgiframe.min.js"></script><![endif]-->
        
        <!-- jquery.datePicker.js -->
		<script type="text/javascript" src="./js/jquery.datePicker.js"></script>
        
        <!-- datePicker required styles -->
		<link rel="stylesheet" type="text/css" media="screen" href="./css/datePicker.css">
		
        <!-- page specific styles -->
		<link rel="stylesheet" type="text/css" media="screen" href="./css/demo.css">
        
        <!-- page specific scripts -->
		<script type="text/javascript" charset="utf-8">
			Date.format = 'mm/dd/yyyy';
            $(function()
            {
				$('.date-pick').datePicker({startDate: '01/01/1970', autoFocusNextInput: true});
            });
		</script>

<script type="text/javascript" src="./js/common_scripts.js"></script>
<script type="text/javascript">
function valid_shoptask(frm){

	var sErrMessage='';
	var iErrCounter=0;
	
	/*if (frm.drpvehicle.value==""){
		sErrMessage='<li>please select vehicle';
		iErrCounter++;
	}*/
	
	
	/*if (frm.txtmilestech.value == ""){
		sErrMessage=sErrMessage+'<li>please enter miles reading by technician';
		iErrCounter++;
	}else{
		regExp = /[ 0-9\.{9}'-]/i;
		if (!validate_field(frm.txtmilestech, regExp)){
			sErrMessage=sErrMessage+'<li>please enter valid miles reading by technician';
			iErrCounter++;
		}
	}*/
	if (frm.drpworktype.value==""){
		sErrMessage=sErrMessage+'<li>please select work type done for the vehicle';
		iErrCounter++;
	}
		
	
	if (frm.txtworkdate.value == ""){
		sErrMessage=sErrMessage+'<li>please select work done date';
		iErrCounter++;
	}
	

	if (frm.txtcost.value == ""){
		sErrMessage=sErrMessage+'<li>please enter cost of work done on vehicle';
		iErrCounter++;
	}else{
		regExp = /[ 0-9\.{9}'-]/i;
		if (!validate_field(frm.txtcost, regExp)){
			sErrMessage=sErrMessage+'<li>please enter valid cost of work done on vehicle';
			iErrCounter++;
		}
	}
	
		
	if (iErrCounter >0){
		
		fn_draw_ErrMsg(sErrMessage);
	}
	else
		frm.submit();
	
}
</script>

</head>
<body style="margin: 0px;">
<div align="center">
	<table border="0" cellspacing="0" cellpadding="0">
  		<!--start header	-->
		<? include('inc_header.php');	?>
		
   		<!-- start side nav	-->
		
		
		<!-- actual page	-->
       <td>
       <table border="0" cellspacing="0" cellpadding="0" width="980">
            	<tr valign="top" align="left">
                	<td width="15" height="16"><img src="../assets/images/autogen/clearpixel.gif" width="15" height="1" border="0" alt=""></td>
                	<td width="1"><img src="../assets/images/autogen/clearpixel.gif" width="1" height="1" border="0" alt=""></td>
                	<td width="949"><img src="../assets/images/autogen/clearpixel.gif" width="683" height="1" border="0" alt=""></td>
                	<td width="15"><img src="../assets/images/autogen/clearpixel.gif" width="1" height="1" border="0" alt=""></td>
               	</tr>
               	<tr valign="top" align="left">
                	<td height="40"></td>
                	<td colspan="2" width="949">
                 		<table border="0" cellspacing="0" cellpadding="0" width="949" style="background-image: url('../assets/images/banner.png'); height: 40px;">
                  			<tr align="left" valign="top">
                   				<td width="100%">
									<table border="0" cellspacing="0" cellpadding="0" width="100%" height="100%">
								 		<tr>			
								  			<td class="TextObject" align="center">
								   				<h1 style="margin-bottom: 0px;">MODIFY SHOP WORK</h1>
								  			</td>
								 		</tr>
									</table>
                   				</td>
                  			</tr>
                 		</table>
                	</td>
               	</tr>
				
               	<tr valign="top" align="left"><td colspan="4">&nbsp;</td></tr>
				<tr valign="top" align="left"><td colspan="4">&nbsp;</td></tr>
				
               	<tr valign="top" align="left">
                	<td colspan="2"></td>
                	<td width="949" class="TextObject" align="center">
						<form name="frm1" action="edit_shoptask.php" method="post">
							<input type="hidden" name="action" value="editshop"	/>
							<input type="hidden" name="stid" value="<? echo $iSHOP_TASK_ID;?>" />
							<table cellpadding="0" cellspacing="5" border="0" width="600" align="center" class="box">
								
								<tr><td colspan="2" id="Message" width="100%"><?=$sMessage?></td></tr>
								
								<tr>
									<td width="200" class="label">Work Order No:</td>
									<td width="400">
										<input type="text" readonly="" name="txtorderno" value="<? echo $rowTASK['task_id'];?>" style="width:150px;" />
									</td>
								</tr>
								<tr>
									<td width="150" class="label">Vehicle No:</td>
									<td width="300">
										<input type="text" readonly="" name="txtvno" value="<? echo $rowTASK['vehicle_no'];?>" style="width:150px;" />
									</td>
								</tr>
								<tr>
									<td class="label">Make:</td>
									<td><input readonly="" type="text" id="txtmake" name="txtmake" value="<? echo $rowTASK['brand_name'];?>"  style="width:150px;"  /><span id="loadingimage"></span></td>
								</tr>
								<tr>
									<td class="label">Type:</td>
									<td><input readonly="" type="text" id="txttype" name="txttype" value="<? echo $rowTASK['v_type'];?>"  style="width:150px;"  /></td>
								</tr>
								<tr>
									<td class="label">Oil Filter:</td>
									<td><input readonly="" type="text" id="txtoilfilter" name="txtoilfilter" value="<? echo $rowTASK['oil_filter'];?>"  style="width:150px;"  /></td>
								</tr>
								<tr>
									<td class="label">Last Mileage:</td>
									<td><input readonly="" type="text" id="txtlastmileage" name="txtlastmileage" value="<? echo fn_VEHICLE_LAST_MILEAGE($rowTASK['vehicle_id']);?>" maxlength="7" style="width:150px; text-align:right;"  /><span id="loadingimage"></span><span style="color:#CA0000;">(vehicle's last trip mileage)</span></td>
								</tr>
								<tr>
									<td class="label">Miles Read Technician:</td>
									<td><input type="text" name="txtmilestech" value="<? echo $rowTASK['miles_reading_tech']?>" maxlength="7" style="width:150px; text-align:right;"  /></td>
								</tr>
								<tr>
									<td class="label">Work Type Requested:</td>
									<td><?	fn_WORK_TYPE('drpworktype', $rowTASK['work_type_id'], "150", "1", "Select Work Type");?></td>
								</tr>
								<tr>
									<td class="label">Date Work Done</td>
									<td><input type="text" readonly="" name="txtworkdate" id="txtworkdate" value="<? echo fn_cDateMySql($rowTASK['work_start_date'], 1); ?>" maxlength="10" style="width:100px;" class="date-pick dp-applied" /></td>
								</tr>
								<tr>
									<td class="label">Next Oil Due:</td>
									<td><input type="text" readonly="" name="txtnextoil" id="txtnextoil" value="<? echo fn_cDateMySql($rowTASK['next_oil'],1);?>" maxlength="10" style="width:100px;" class="date-pick dp-applied" /></td>
								</tr>
								<tr>
									<td class="label">Total Cost:</td>
									<td><input type="text" name="txtcost" value="<? echo $rowTASK['total_cost']?>" maxlength="10" style="width:100px; text-align:right;" /></td>
								</tr>
								<tr>
									<td class="label">Parts Supplier:</td>
									<td><input type="text" name="txtparts" value="<? echo $rowTASK['parts_source']?>" maxlength="255" style="width:150px;" /></td>
								</tr>
								<!--<tr>
									<td class="label">Invoice No:</td>
									<td><input type="text" name="txtinvoice" value="<? //echo $rowTASK['invoice_no']?>" maxlength="50" style="width:150px;" /></td>
								</tr>-->
								<tr>
									<td class="label">Outside Mechanic:</td>
									<td><input type="text" name="txtvendor" value="<? echo $rowTASK['vendor_name']?>" maxlength="50" style="width:150px;" /></td>
								</tr>
								<tr>
									<td class="label">Task Completed:</td>
									<td><input type="checkbox" name="chktaskcompleted" value="1" <? if($rowTASK['task_complete']==1) echo "checked";?> /></td>
								</tr>
								
								<tr>
									<td class="label" valign="top">Describe Work:</td>
									<td>
									<textarea name="txtcomments" id="txtcomments" cols="30" rows="5" style="width:250px;" onkeydown="fn_char_Counter(this.form.txtcomments,this.form.txtLength,500);" onkeyup="fn_char_Counter(this.form.txtcomments,this.form.txtLength,500);"><? echo stripslashes($rowTASK['tech_comments']);?></textarea>
									&nbsp;<input readonly type="text" name="txtLength" value="500" style="width:20px;">
									</td>
								</tr>
								
								<tr><td colspan="2">&nbsp;</td></tr>
								<tr><td></td><td><input type="button" name="btnSUBMIT" value="MODIFY SHOP TASK" class="Button" onClick="valid_shoptask(this.form);" style="width:150px;" /></td></tr>
							</table>
						</form>
                	</td>
                	<td></td>
               	</tr>
			</table>
		</td>
		
		
		<!-- end actual page	-->
           
      <!-- footer	-->
	  <? include('inc_footer.php');	?>
     </table>
    </td>
   </tr>
  </table>
 </div>
</body>
</html>