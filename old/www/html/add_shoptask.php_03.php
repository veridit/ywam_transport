<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	
	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$sMessage		=	"";
	if(isset($_POST["action"])	&& $_POST["action"]=="addshop"){
	
	$bCOMPLETED_TASK	=	0;		$bTEST_DRIVE_DONE	=	0;
						
		$sWORKSTART_DATE		=	substr($_POST["txtworkdate"],6, 4);
		$sWORKSTART_DATE		.=	"-".substr($_POST["txtworkdate"],0, 2);
		$sWORKSTART_DATE		.=	"-".substr($_POST["txtworkdate"],3, 2);
		
		$sNEXTOIL_DATE		=	substr($_POST["txtnextoil"],6, 4);
		$sNEXTOIL_DATE		.=	"-".substr($_POST["txtnextoil"],0, 2);
		$sNEXTOIL_DATE		.=	"-".substr($_POST["txtnextoil"],3, 2);
		
				
					
			if(isset($_POST["chktestdrive"]) && $_POST["chktestdrive"]!="")				$bTEST_DRIVE_DONE	=	"1";	else	$bTEST_DRIVE_DONE	=	"0";
			if(isset($_POST["chktaskcompleted"]) && $_POST["chktaskcompleted"]!="")		$bCOMPLETED_TASK	=	"1";	else	$bCOMPLETED_TASK	=	"0";
				
			$sSQL="INSERT INTO  tbl_shop_tasks(user_id, vehicle_id, miles_reading_tech, last_mileage, ".
			"work_type_id, work_start_date, next_oil, total_cost, parts_source, ".
			"drive_test_done, task_complete, tech_comments, invoice_no, vendor_name) ".
			"VALUES(".$_SESSION["User_ID"].", '".$_POST["drpvehicle"]."', '', '".$_POST["txtlastmileage"]."', ".$_POST["drpworktype"].", ".
			"'".$sWORKSTART_DATE."', '".$sNEXTOIL_DATE."', ".$_POST["txtcost"].", '".$_POST["txtparts"]."', ".
			"".$bTEST_DRIVE_DONE.", ".$bCOMPLETED_TASK.", '".addslashes($_POST["txtcomments"])."', '', '".$_POST["txtvendor"]."')";
			//print($sSQL);
			$rsSHOPTASK=mysql_query($sSQL) or die(mysql_error());
			
			$sMessage		=	fn_Print_MSG_BOX("shop task added successfully", "C_SUCCESS");
					
				
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>
<head>
<title>Shop Work Order</title>
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
function fn_PRINT(){
		var url="printshoppage.php";
		var myWindow	=	window.open(url,"_blank","height=600, width=800, resizable=no, scrollbars=yes");
}
function valid_shoptask(frm){

	var sErrMessage='';
	var iErrCounter=0;
	
	if (frm.drpvehicle.value==""){
		sErrMessage='<li>please select vehicle';
		iErrCounter++;
	}
	
	
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

									
function ajax_data(vid){
var xmlhttp;
var sURL = "ajax_data.php?";
var sData	=	"";
try
{
		// Firefox, Opera 8.0+, Safari
		xmlhttp=new XMLHttpRequest();
}
catch (e){
	 try {
	  xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	 } catch (e) {
		  try {
			   xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		  } catch (E) {
			   xmlhttp = false;
			}
	 }
}

if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
	try {
		xmlhttp = new XMLHttpRequest();
	} catch (e) {
		xmlhttp=false;
	}
}
if (!xmlhttp && window.createRequest) {
	try {
		xmlhttp = window.createRequest();
	} catch (e) {
		xmlhttp=false;
	}
}
 xmlhttp.open("GET", sURL+"action=shoptask&vid="+vid,true);
 xmlhttp.onreadystatechange=function() {
	if(xmlhttp.readyState == 1)
		{
			if (document.getElementById && document.getElementById('loadingimage'))
				document.getElementById('loadingimage').innerHTML = "<img src=../assets/images/loading_busy.gif border='0'>";
			
		}


	if (xmlhttp.readyState==4) {
   
		//document.frm1.txtlastmileage.value	=	xmlhttp.responseText;
		sData	=	xmlhttp.responseText;
		document.frm1.txtlastmileage.value	=	sData.substring(0, sData.indexOf('t='));
		document.frm1.txttype.value			=	sData.substring(sData.indexOf('t=')+2,sData.indexOf('m='));
		document.frm1.txtmake.value			=	sData.substring(sData.indexOf('m=')+2,sData.indexOf('f='));
		document.frm1.txtoilfilter.value	=	sData.substring(sData.indexOf('f=')+2,sData.length);
		
		document.getElementById('loadingimage').innerHTML = '';
		document.getElementById('loadingimage').style.display = 'none';
	
	}
 }
 xmlhttp.send(null)
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
								   				<h1 style="margin-bottom: 0px;">SHOP WORK</h1>
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
						<form name="frm1" action="add_shoptask.php" method="post">
							<input type="hidden" name="action" value="addshop"	/>
							<table cellpadding="0" cellspacing="5" border="0" width="600" align="center" class="box">
								
								<tr><td colspan="2" id="Message" width="100%"><?=$sMessage?></td></tr>
								
								<tr>
									<td width="200" class="label">Vehicle No:</td>
									<td width="400"><?	fn_VEHICLE('drpvehicle', '', "150", "1", "Select Vehicle", "ajax_data(this.value);");?></td>
								</tr>
								<tr>
									<td class="label">Make:</td>
									<td><input readonly="" type="text" id="txtmake" name="txtmake" value=""  style="width:150px;"  /><span id="loadingimage"></span></td>
								</tr>
								<tr>
									<td class="label">Type:</td>
									<td><input readonly="" type="text" id="txttype" name="txttype" value=""  style="width:150px;"  /></td>
								</tr>
								<tr>
									<td class="label">Oil Filter:</td>
									<td><div style="float:left;"><input readonly="" type="text" id="txtoilfilter" name="txtoilfilter" value=""  style="width:150px;"  /></div><span class="Highlight" style="font-weight:bold;">No. comes from Add Veh or List Veh. page</span></td>
								</tr>
								<tr>
									<td class="label">Last Mileage:</td>
									<td><input readonly="" type="text" id="txtlastmileage" name="txtlastmileage" value="" maxlength="7" style="width:150px; text-align:right;"  /><span class="Highlight" style="font-weight:bold;">(vehicle's last trip mileage)</span></td>
								</tr>
								<!--<tr>
									<td class="label">Miles Read Technician:</td>
									<td><input type="text" name="txtmilestech" value="" maxlength="7" style="width:150px; text-align:right;"  /></td>
								</tr>-->
								<tr>
									<td class="label">Work Type Requested:</td>
									<td><?	fn_WORK_TYPE('drpworktype', '', "150", "1", "Select Work Type");?></td>
								</tr>
								<tr>
									<td class="label">Date Work Done:</td>
									<td><input type="text" readonly="" name="txtworkdate" id="txtworkdate" value="" maxlength="10" style="width:100px;" class="date-pick dp-applied" /></td>
								</tr>
								<tr>
									<td class="label">Next Oil Due:</td>
									<td><input type="text" readonly="" name="txtnextoil" id="txtnextoil" value="" maxlength="10" style="width:100px;" class="date-pick dp-applied" /><span class="Highlight" style="font-weight:bold;">Add 4 months to last oil date</span></td>
								</tr>
								<tr>
									<td class="label">Total Cost:</td>
									<td><input type="text" name="txtcost" value="0.00" maxlength="10" style="width:100px; text-align:right;" /></td>
								</tr>
								<tr>
									<td class="label">Parts Supplier:</td>
									<td><input type="text" name="txtparts" value="" maxlength="255" style="width:150px;" /></td>
								</tr>
								<!--<tr>
									<td class="label">Invoice No:</td>
									<td><input type="text" name="txtinvoice" value="" maxlength="50" style="width:150px;" /></td>
								</tr>-->
								<tr>
									<td class="label">Outside Mechanic:</td>
									<td><input type="text" name="txtvendor" value="" maxlength="50" style="width:150px;" /></td>
								</tr>
								<tr>
									<td class="label">Test Drive Done:</td>
									<td><input type="checkbox" name="chktestdrive" value="1" /></td>
								</tr>
								<tr>
									<td class="label">Task Completed:</td>
									<td><input type="checkbox" name="chktaskcompleted" value="1" /></td>
								</tr>
								<tr>
									<td class="label" valign="top">Describe Work:</td>
									<td>
									<textarea name="txtcomments" id="txtcomments" cols="30" rows="5" style="width:250px;" onkeydown="fn_char_Counter(this.form.txtcomments,this.form.txtLength,500);" onkeyup="fn_char_Counter(this.form.txtcomments,this.form.txtLength,500);"></textarea>
									&nbsp;<input readonly type="text" name="txtLength" value="500" style="width:20px;">
									</td>
								</tr>
								
								<tr><td colspan="2">&nbsp;</td></tr>
								<tr><td></td><td><input type="button" name="btnSUBMIT" value="ADD SHOP TASK" class="Button" onClick="valid_shoptask(this.form);" style="width:150px;" />&nbsp;&nbsp;&nbsp;<input type="button" name="btnPrint" class="Button" value="PRINT THIS PAGE" onClick="fn_PRINT();" /></td></tr>
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