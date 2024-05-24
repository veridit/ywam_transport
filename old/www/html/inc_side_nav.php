<td>
     		<table border="0" cellspacing="0" cellpadding="0" width="260">
               <tr valign="top" align="left">
                <td width="15" height="14"><img src="../assets/images/autogen/clearpixel.gif" width="15" height="1" border="0" alt=""></td>
                <td></td>
               </tr>
               <tr valign="top" align="left">
                <td>&nbsp;</td>
                <td width="245">
				
                 <table border="0" cellspacing="0" cellpadding="0" width="245" style="background-image: url('../assets/images/leftbg.gif');">
                  <tr align="left" valign="top">
                   <td>
                    <table border="0" cellspacing="0" cellpadding="0" width="245">
                     <tr valign="top" align="left">
                      <td height="39" width="245">
                      	<?		
							$sCurrent_Page	=	"";
							$sCurrent_Page	=	substr(basename($_SERVER['PHP_SELF']),0,strpos(basename($_SERVER['PHP_SELF']),"."));
							
							$sLAST_PAGE	=	"";
							if(isset($_SERVER['HTTP_REFERER']))		$sLAST_PAGE	=	substr(basename($_SERVER['HTTP_REFERER']),0,strpos(basename($_SERVER['HTTP_REFERER']),"."));
							
							
							if($sCurrent_Page=="list_users"){
						?>
					   <table border="0" cellspacing="0" cellpadding="0" width="245" style="background-image: url('../assets/images/leftheader.png');"  height="39">
                        <tr align="left" valign="top">
                         <td valign="middle" align="center" class="left_nav_label">Notices</td>
                        </tr>
                       </table>
					   <?	}else{?>
					   <table border="0" cellspacing="0" cellpadding="0" width="245" style="background-image: url('../assets/images/leftheader.gif');"  height="39">
                        <tr align="left" valign="top">
                         <td>&nbsp;</td>
                        </tr>
                       </table>
					   <?	}?>
                      </td>
                     </tr>
                    </table>
                    <table border="0" cellspacing="0" cellpadding="0" width="229">
                     <tr valign="top" align="left">
                      <td width="15" height="15"><img src="../assets/images/autogen/clearpixel.gif" width="15" height="1" border="0" alt=""></td>
                      <td width="214"><img src="../assets/images/autogen/clearpixel.gif" width="214" height="1" border="0" alt=""></td>
                     </tr>
                     <tr valign="top" align="left">
                      <td></td>
                      <td width="214" class="TextObject">
                       <p style="margin-bottom: 0px;">
					   <table cellpadding="0" cellspacing="3" border="0">
					   <script type="text/javascript">
							function fn_OPEN_CHART(){
								var url="pending_trips_chart.php";
								//var myWindow	=	window.open(url,"_blank","height=768, width=1024, resizable=no, scrollbars=yes");
								var myWindow	=	window.open(url);
							}
							
						</script>
					   <?  	if(isset($_SESSION["User_ID"]) && $_SESSION["User_ID"]!=""){
								//print("afasfasfd".$sCurrent_Page);
								
								
								if($sCurrent_Page	==	"admin"){
								
									//first delete all previous menus
									fn_DELETE_SIDE_MENU(session_id());
									//INSDRT NEW ONES
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_LINK_ADD))				fn_INSERT_SIDE_MENU("add_link.php", "1) Add Notice to Home Page", 1);
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_LINK_SEARCH))			fn_INSERT_SIDE_MENU("list_links.php", "2) Outgoing Messages-Edit", 2);
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_NOTICE_ADD))			fn_INSERT_SIDE_MENU("add_notice.php", "3) Edit Login Message", 3);
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_DEPARTMENT_ADD))		fn_INSERT_SIDE_MENU("add_dept.php", "4) Add Department", 4);
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_DEPARTMENT_SEARCH))	fn_INSERT_SIDE_MENU("list_dept.php", "5) Departments:List-Deactivate-Edit", 5);
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_VEHICLES_ADD))			fn_INSERT_SIDE_MENU("addvehicle.php", "6) Add Vehicle", 6);
									
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_BACKUP))				fn_INSERT_SIDE_MENU("backup.php", "7) Take Extra Database Backup", 7);
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_BACKUP_SEARCH))		fn_INSERT_SIDE_MENU("list_backups.php", "8) Restore System Backup", 8);
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_LOG_SEARCH))			fn_INSERT_SIDE_MENU("list_log.php", "9) List User Logins", 9);
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_DELETE_OLD_TRIPS))		fn_INSERT_SIDE_MENU("delete_old_trips.php", "10) Delete Trips Explanation", 10);
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_ACTIVE_DRIVERS))		fn_INSERT_SIDE_MENU("list_active_drivers.php", "11) Driver Emails: Make &amp; Send", 11);
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_GRAPH_PENDING_TRIPS))	fn_INSERT_SIDE_MENU("pending_trips_chart.php", "12) Graph Pending Trips", 12);
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_GRAPH_PENDING_TRIPS))	fn_INSERT_SIDE_MENU("restore_delete_trips.php", "13) Restore Deleted Trips", 13);
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_VEHICLES_ISSUES))		fn_INSERT_SIDE_MENU("list_vehicle_issues.php", "14) Driver Notes about Vehicles", 14);
									
									
									//GET THE MENUS FROM DB
									$rsSIDE_MENUS	=	fn_GET_SIDE_MENU();
									while($rowSIDE_MENU	=	mysql_fetch_array($rsSIDE_MENUS)){
										echo "<tr><td class='".$rowSIDE_MENU['menu_class']."'><a href='".$rowSIDE_MENU['menu_link']."'>".$rowSIDE_MENU['menu_name']."</a></td></tr>";
									}
								
								}elseif($sCurrent_Page	==	"management"){
								
									//first delete all previous menus
									fn_DELETE_SIDE_MENU(session_id());
									//INSDRT NEW ONES
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_RES_SEARCH))			fn_INSERT_SIDE_MENU("list_reservations.php", "List Trips", 1);
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_EDIT_RESERVATION))		fn_INSERT_SIDE_MENU("add_keycard_trip.php", "Make Trip Slip", 2);
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_PENDING_TRIP))			fn_INSERT_SIDE_MENU("add_tripdetails.php", "Close Trips Slip", 3);
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_PENDING_TRIP))			fn_INSERT_SIDE_MENU("list_pending_trips.php", "Close-Change-Delete Pending Trips", 4);
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_TRIP_SEARCH))			fn_INSERT_SIDE_MENU("list_trips.php", "List Closed Trips", 5);
									if($_SESSION["User_Group"]==$iGROUP_TM) if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_NOTICE_ADD))			fn_INSERT_SIDE_MENU("add_notice.php", "Add Special Notice", 6);
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_TRIP_EDIT))			fn_INSERT_SIDE_MENU("edit_trip.php", "Modify Mileage &amp; Gas", 7);
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_ABANDON_TRIP_ADD))		fn_INSERT_SIDE_MENU("add_abandon.php", "Mark Trip Abandoned", 8);
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_ACTIVE_DRIVERS))		fn_INSERT_SIDE_MENU("list_active_drivers.php", "Driver Emails: Make &amp; Send", 9);
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_CHANGE_CHARGE_DEPT))	fn_INSERT_SIDE_MENU("chng_charge_dept.php", "Change Charge Dept of Closed Trip", 10);
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_SCHOOL_COST))			fn_INSERT_SIDE_MENU("list_school_cost.php", "Trips by School", 11);
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_TM_START_REPORT))		fn_INSERT_SIDE_MENU("tm_start.php", "TM Start Report", 12);
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_VEHICLE_LIMIT_OVERRIDE))		fn_INSERT_SIDE_MENU("vehicle_limit.php", "Override 3 Vehicle Limit", 13);
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_VEHICLE_LIMIT_OVERRIDE_SEARCH))fn_INSERT_SIDE_MENU("list_vhcl_limit.php", "Restore 3 Vehicle Limit", 14);
										
									//GET THE MENUS FROM DB
									$rsSIDE_MENUS	=	fn_GET_SIDE_MENU();
									while($rowSIDE_MENU	=	mysql_fetch_array($rsSIDE_MENUS)){
										echo "<tr><td class='".$rowSIDE_MENU['menu_class']."'><a href='".$rowSIDE_MENU['menu_link']."'>".$rowSIDE_MENU['menu_name']."</a></td></tr>";
									}
									
								}elseif($sCurrent_Page	==	"finance"){
								
									
									fn_DELETE_SIDE_MENU(session_id());
									
									
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_DEPARTMENT_COST))						fn_INSERT_SIDE_MENU("list_dept_cost.php", "1) Dept. Cost Summary", 1);
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_SCHOOL_COST))							fn_INSERT_SIDE_MENU("list_school_cost.php", "2) Trips by School", 2);
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_VEHICLE_COST))							fn_INSERT_SIDE_MENU("list_vehicle_cost.php", "3) Repair Cost Summary", 3);
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_RESTRICTED_VEHICLE_CHARGES))			fn_INSERT_SIDE_MENU("add_restricted_charges.php", "4) Charge Restricted Veh", 4);
									
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_RESTRICTED_VEHICLE_CHARGES_SEARCH)){
										fn_INSERT_SIDE_MENU("list_restricted_charges.php", "5) List Flat Rate Charges", 5);
										fn_INSERT_SIDE_MENU("list_rest_read.php", "6) List Restricted Veh. Mileage Charges", 6);
									}
									
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_CHANGE_CHARGE_DEPT))	fn_INSERT_SIDE_MENU("chng_charge_dept.php", "7) Change Charge Dept of Closed Trip", 7);
									
									$rsSIDE_MENUS	=	fn_GET_SIDE_MENU();
									while($rowSIDE_MENU	=	mysql_fetch_array($rsSIDE_MENUS)){
										echo "<tr><td class='".$rowSIDE_MENU['menu_class']."'><a href='".$rowSIDE_MENU['menu_link']."'>".$rowSIDE_MENU['menu_name']."</a></td></tr>";
									}
								
								}elseif($sCurrent_Page	==	"shopwork"){
								
									//first delete all previous menus
									fn_DELETE_SIDE_MENU(session_id());
									//INSDRT NEW ONES
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_SHOP_ADD))			fn_INSERT_SIDE_MENU("add_shoptask.php", "Shop Work", 1);
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_VEHICLES_ADD))		fn_INSERT_SIDE_MENU("addvehicle.php", "Add Vehicle", 2);
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_VEHICLES_MODIFY))	fn_INSERT_SIDE_MENU("edit_vehicle.php", "Edit / View Vehicle", 3);
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_VEHICLES_SEARCH))	fn_INSERT_SIDE_MENU("list_vehicles.php", "List Vehicles", 4);
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_VEHICLE_R_M_TASK))	fn_INSERT_SIDE_MENU("list_vehicle_tasks.php", "List Shop Tasks", 5);
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_VEHICLE_MILEAGE))	fn_INSERT_SIDE_MENU("list_vehicle_mileage.php", "List Vehicle Mileage", 6);
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_INSPECT_DUE))		fn_INSERT_SIDE_MENU("list_inspect.php", "Inspect-Registrations Due", 7);
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_VEHICLES_ISSUES))	fn_INSERT_SIDE_MENU("list_vehicle_issues.php", "Vehicle Issues & Notes", 8);
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_SELL_VEHICLE))		fn_INSERT_SIDE_MENU("sell_vehicle.php", "Sell-dispose of Vehicle", 9);
									//GET THE MENUS FROM DB
									$rsSIDE_MENUS	=	fn_GET_SIDE_MENU();
									while($rowSIDE_MENU	=	mysql_fetch_array($rsSIDE_MENUS)){
										echo "<tr><td class='".$rowSIDE_MENU['menu_class']."'><a href='".$rowSIDE_MENU['menu_link']."'>".$rowSIDE_MENU['menu_name']."</a></td></tr>";
									}
								
								}elseif($sCurrent_Page	==	"reports"){
									//first delete all previous menus
									fn_DELETE_SIDE_MENU(session_id());
									//INSDRT NEW ONES
									
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_GRAPH_PENDING_TRIPS))				fn_INSERT_SIDE_MENU("pending_trips_chart.php", "Graph Pending Trips", 1);
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_DELETED_TRIPS))					fn_INSERT_SIDE_MENU("list_deleted.php", "List Deleted Trips", 2);
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_DRIVER_DELETED_RESERVATION))		fn_INSERT_SIDE_MENU("driver_deleted_trips.php", "Driver Cancelled Trips", 3);
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_ABANDON_TRIP_SEARCH))				fn_INSERT_SIDE_MENU("list_abandon.php", "List Abandoned Trips", 4);
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_DEPARTMENT_SEARCH))				if($_SESSION["User_Group"]==$iGROUP_TC)	fn_INSERT_SIDE_MENU("list_dept.php", "Departments: List or Edit", 5); else fn_INSERT_SIDE_MENU("list_dept.php", "List Departments", 5);
									
									
									//GET THE MENUS FROM DB
									$rsSIDE_MENUS	=	fn_GET_SIDE_MENU();
									while($rowSIDE_MENU	=	mysql_fetch_array($rsSIDE_MENUS)){
										if($rowSIDE_MENU['menu_link']=="pending_trips_chart.php"){
											echo "<tr><td class='".$rowSIDE_MENU['menu_class']."'><a href='javascript:void(0)' onclick='fn_OPEN_CHART();'>".$rowSIDE_MENU['menu_name']."</a></td></tr>";
										}else{
											echo "<tr><td class='".$rowSIDE_MENU['menu_class']."'><a href='".$rowSIDE_MENU['menu_link']."'>".$rowSIDE_MENU['menu_name']."</a></td></tr>";
										}
									}
									
					
								}elseif($sCurrent_Page	==	"reservations"){
								
									//first delete all previous menus
									fn_DELETE_SIDE_MENU(session_id());
									//INSDRT NEW ONES
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_CHECK_RESERVATIONS))fn_INSERT_SIDE_MENU("reservations.php", "Make Reservations", 1);
									if($_SESSION["User_Group"]==$iGROUP_DRIVER || $_SESSION["User_Group"]==$iGROUP_COORDINATOR_STAFF){
										if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_GRAPH_PENDING_TRIPS))	fn_INSERT_SIDE_MENU("pending_trips_chart.php", "Show Available Vans", 2);
										
										$sSQL			=	"SELECT driver_permission FROM tbl_user WHERE user_id = ".$_SESSION["User_ID"];
										$bPERMISSION	=	mysql_result(mysql_query($sSQL),0);
										if($bPERMISSION==1){
											fn_INSERT_SIDE_MENU("list_users.php", "List Users", 3);
											fn_INSERT_SIDE_MENU("list_school_cost.php", "Trips Taken", 4);
										}
										
									}
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_VEHICLES_SEARCH))	fn_INSERT_SIDE_MENU("list_vehicles.php", "List Vehicles", 5);
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_RES_CANCELLATION))	fn_INSERT_SIDE_MENU("cancel_trip.php", "Cancel Reservation", 6);
								
									
									//GET THE MENUS FROM DB
									$rsSIDE_MENUS	=	fn_GET_SIDE_MENU();
									while($rowSIDE_MENU	=	mysql_fetch_array($rsSIDE_MENUS)){
										if($rowSIDE_MENU['menu_link']=="pending_trips_chart.php"){
											echo "<tr><td class='".$rowSIDE_MENU['menu_class']."'><a href='javascript:void(0)' onclick='fn_OPEN_CHART();'>".$rowSIDE_MENU['menu_name']."</a></td></tr>";
										}else{
											echo "<tr><td class='".$rowSIDE_MENU['menu_class']."'><a href='".$rowSIDE_MENU['menu_link']."'>".$rowSIDE_MENU['menu_name']."</a></td></tr>";
										}
									}
						
								}elseif($sCurrent_Page	==	"list_users" && $_SESSION["User_Group"]!=$iGROUP_DRIVER && $_SESSION["User_Group"]!=$iGROUP_COORDINATOR_STAFF){
								
								
									//first delete all previous menus
									fn_DELETE_SIDE_MENU(session_id());
									
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_USER_SEARCH))						fn_INSERT_SIDE_MENU("list_users.php", "List-Activate-Renew Users", 1, "left_side_menu bottom_border");
									//if($_SESSION["User_Group"]==$iGROUP_TM || $_SESSION["User_Group"]==$iGROUP_TC)				fn_INSERT_SIDE_MENU("edit_user.php", "OK Permit Renewal", 2, "left_side_menu bottom_border");
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_DEACTIVATE_DRIVERS))				fn_INSERT_SIDE_MENU("deactivate_drivers.php", "Drivers: Year End Notice", 2, "left_side_menu bottom_border");
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_DELETE_DRIVERS))					fn_INSERT_SIDE_MENU("list_driver_delete.php", "Delete Drivers", 3, "left_side_menu bottom_border");
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_USER_COMMENT_SEARCH))				fn_INSERT_SIDE_MENU("list_tm_notes.php", "List TM Notes about Drivers", 4, "left_side_menu bottom_border");
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_DRIVER_MILEAGE))					fn_INSERT_SIDE_MENU("list_driver_mileage.php", "Driver Mileage", 5, "left_side_menu bottom_border");
									//if($_SESSION["User_Group"]==$iGROUP_TM || $_SESSION["User_Group"]==$iGROUP_TC)				fn_INSERT_SIDE_MENU("info_page.php?id=11", "Coordinator-Staff Notes", 6);
									if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_AIR_PORT_PARKING_LINK))			fn_INSERT_SIDE_MENU("airport_msg.php", "Send Airport Warning", 7, "left_side_menu bottom_border");
												
									
									if($_SESSION["User_Group"]==$iGROUP_DRIVER || $_SESSION["User_Group"]==$iGROUP_COORDINATOR_STAFF){
										$sSQL	=	"SELECT dl.link_id, link_title, dl.link_order FROM tbl_info_links il RIGHT OUTER JOIN tbl_driver_links_order dl ON il.link_id = dl.link_id WHERE driver_login = 1 ORDER BY dl.link_order ASC";
										$rsSIDE_LINKS		=mysql_query($sSQL) or die(mysql_error());
										if(mysql_num_rows($rsSIDE_LINKS)>0){
											while($rowSIDE_LINK	=	mysql_fetch_array($rsSIDE_LINKS)){
											
												if($rowSIDE_LINK['link_id']==0){
													fn_INSERT_SIDE_MENU("adduser.php", "Driver Registration-Step 2", $rowSIDE_LINK['link_order']);
												}elseif($rowSIDE_LINK['link_id']==100){
													fn_INSERT_SIDE_MENU("edit_user.php", "Request Permit Rnewal", $rowSIDE_LINK['link_order']);
												}else{
													fn_INSERT_SIDE_MENU("info_page.php?id=".$rowSIDE_LINK['link_id'], stripslashes($rowSIDE_LINK['link_title']), $rowSIDE_LINK['link_order']);
												}
												
											}
										}
								
									}
									
									$rsSIDE_MENUS	=	fn_GET_SIDE_MENU();
									if($rsSIDE_MENUS!=false){
																			
										while($rowSIDE_MENU	=	mysql_fetch_array($rsSIDE_MENUS)){
											echo "<tr><td class='".$rowSIDE_MENU['menu_class']."'><a href='".$rowSIDE_MENU['menu_link']."'>".$rowSIDE_MENU['menu_name']."</a></td></tr>";
										}
	
									}
									
								}elseif($sCurrent_Page	==	"info_page" && ($sLAST_PAGE == "info_page" || $sLAST_PAGE == "index")){
									//first delete all previous menus
									fn_DELETE_SIDE_MENU(session_id());
									
									
									if($_SESSION["User_Group"]==$iGROUP_DRIVER || $_SESSION["User_Group"]==$iGROUP_COORDINATOR_STAFF){
										$sSQL	=	"SELECT dl.link_id, link_title, dl.link_order FROM tbl_info_links il RIGHT OUTER JOIN tbl_driver_links_order dl ON il.link_id = dl.link_id WHERE driver_login = 1 ORDER BY dl.link_order ASC";
																		
									}else{
										//fn_DISPLAY_INFO_LINKS('.', 'home');
										$sSQL	=	"SELECT dl.link_id, link_title, dl.link_order FROM tbl_info_links il RIGHT OUTER JOIN tbl_driver_links_order dl ON il.link_id = dl.link_id WHERE driver_login = 0 ORDER BY dl.link_order ASC";
									}
									
									
									$rsSIDE_LINKS		=mysql_query($sSQL) or die(mysql_error());
									if(mysql_num_rows($rsSIDE_LINKS)>0){
										while($rowSIDE_LINK	=	mysql_fetch_array($rsSIDE_LINKS)){
										
											if($rowSIDE_LINK['link_id']==0){
												fn_INSERT_SIDE_MENU("adduser.php", "Driver Registration-Step 2", $rowSIDE_LINK['link_order']);
											}elseif($rowSIDE_LINK['link_id']==100){
												fn_INSERT_SIDE_MENU("edit_user.php", "Request Permit Rnewal", $rowSIDE_LINK['link_order']);
											}else{
												fn_INSERT_SIDE_MENU("info_page.php?id=".$rowSIDE_LINK['link_id'], stripslashes($rowSIDE_LINK['link_title']), $rowSIDE_LINK['link_order']);
											}
											
										}
									}
									
									$rsSIDE_MENUS	=	fn_GET_SIDE_MENU();
									if($rsSIDE_MENUS!=false){
										while($rowSIDE_MENU	=	mysql_fetch_array($rsSIDE_MENUS)){
											echo "<tr><td class='".$rowSIDE_MENU['menu_class']."'><a href='".$rowSIDE_MENU['menu_link']."'>".$rowSIDE_MENU['menu_name']."</a></td></tr>";
										}
									}
									
								}else{
								
									$rsSIDE_MENUS	=	fn_GET_SIDE_MENU();
									while($rowSIDE_MENU	=	mysql_fetch_array($rsSIDE_MENUS)){
										echo "<tr><td class='".$rowSIDE_MENU['menu_class']."'><a href='".$rowSIDE_MENU['menu_link']."'>".$rowSIDE_MENU['menu_name']."</a></td></tr>";
									}
								}
								
								echo "<tr><td>&nbsp;</td></tr>";
								echo "<tr><td>&nbsp;</td></tr>";
								echo "<tr><td class='left_side_menu'><a href='change_password.php'>Change Password</a></td></tr>";
								echo "<tr><td class='left_side_menu'><a href='logout.php'>Logout</a></td></tr>";
								
							}
							
							
							if(!isset($_SESSION["User_ID"])){
								
								$rsSIDE_MENUS	=	fn_GET_SIDE_MENU();
								if($rsSIDE_MENUS!=false){
									while($rowSIDE_MENU	=	mysql_fetch_array($rsSIDE_MENUS)){
										echo "<tr><td class='".$rowSIDE_MENU['menu_class']."'><a href='".$rowSIDE_MENU['menu_link']."'>".$rowSIDE_MENU['menu_name']."</a></td></tr>";
									}
								}
								
							}
						
								
						?>
						</table>
					   </p>
                      </td>
                     </tr>
                    </table>
                    
                    <table border="0" cellspacing="0" cellpadding="0" width="245">
                     <!--<tr valign="top" align="left">
                      <td height="23"></td>
                     </tr>-->
                     <tr valign="top" align="left">
                      <td height="8" width="245"><img id="Picture12" height="8" width="245" src="../assets/images/leftfooter.gif" border="0"></td>
                     </tr>
                    </table>
                   </td>
                  </tr>
                 </table>
                </td>
               </tr>
              </table>
             </td>