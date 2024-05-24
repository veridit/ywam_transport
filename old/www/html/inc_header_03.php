	<tr>
    <td>
     <table border="0" cellspacing="0" cellpadding="0" width="980">
      <tr valign="top" align="left">
       <td height="85" width="980">
        <table cellpadding="0" cellspacing="0" border="0" width="980">
         <tr valign="top" align="left">
          <td>
           <table border="0" cellspacing="0" cellpadding="0" width="85">
            <!--<tr valign="top" align="left">
             <td height="25"></td>
            </tr>-->
            <tr>
             <td height="85" width="84"><img id="Picture5" width="84" height="84" src="../assets/images/seal4.gif" border="0"></td>
            </tr>
           </table>
          </td>
          <td>
           <table border="0" cellspacing="0" cellpadding="0" width="895">
            <tr valign="top" align="left">
             <td height="10"></td><!-- need to remove this line-->
             <td colspan="3" width="885">
              <table id="NavigationBar1" border="0" cellspacing="0" cellpadding="0" width="885">
               <tr valign="top" align="left">
			   
                <script type="text/javascript">
							function fn_OPEN_CHART(){
								var url="pending_trips_chart.php";
								//var myWindow	=	window.open(url,"_blank","height=768, width=1024, resizable=no, scrollbars=yes");
								var myWindow	=	window.open(url);
							}
							
						</script>
				<td>
					<ul id="menu">
					        <li><a href="../index.php">HOME</a> 
                              <div class="menu-container-1"> 
                                <!--Home Start -->
                                <div class="column-1"> 
                                  <h3>Home Menus</h3>
                                  <ul>
                                    <li><a href="info_page.php?id=1">New Driver FAQ</a></li>
									<li><a href="info_page.php?id=9">User Application Step-1</a></li>
                                    <li><a href="adduser.php">User Registration Step-2</a></li>
                                    <li><a href="info_page.php?id=3">Driver Agreement</a></li>
									<?	if(isset($_SESSION["User_Group"]) && ($_SESSION["User_Group"]!=$iGROUP_DRIVER && $_SESSION["User_Group"]!=$iGROUP_COORDINATOR_STAFF)){?>
									<li><a href="info_page.php?id=22">Year End Notice - View</a></li>
									<?	}?>
                                    <li><a href="info_page.php?id=8">Parking Permits</a></li>
                                    <li><a href="info_page.php?id=11">Coordinator Info</a></li>
									<?	//if(isset($_SESSION["User_Group"]) && ($_SESSION["User_Group"]==$iGROUP_DRIVER || $_SESSION["User_Group"]==$iGROUP_COORDINATOR_STAFF)){?>
									
									<?	//}?>
                                  </ul>
                                </div>
                              </div>
                              <!-- End Home -->
                            </li>
							<?	if(isset($_SESSION["User_ID"]) && $_SESSION["User_Group"]==$iGROUP_TC){?>
					        <li><a href="#">MGMT</a> 
                              <div class="menu-container-4"> 
                                <!-- Start tutorial menu section ( 2nd menu ) -->
                                <div class="column-1"> 
                                  <h3>DAILY</h3>
                                  <ul>
                                    <li><a href="tm_start.php">TM Start Report</a></li>
									<li><a href="add_keycard_trip.php">Make Trip Slip</a></li>
								 	<li><a href="add_tripdetails.php">Close Trips Slip</a></li>
									<li><a href="list_reports.php">List Trips</a></li>
									<li><a href="list_resv.php">Find Resrv by No</a></li>
                                   
                                    <li><a href="list_pending_trips.php">Change or Delete Open Trips</a></li>
                                    <!--<li><a href="list_trips.php">List Closed Trips</a></li>-->
									
									 <li><a href="list_vehicles.php">List Vehicles</a></li>
                                  </ul>
                                </div>
                                <div class="column-1"> 
                                  <h3>OTHERS</h3>
                                  <ul>
								  <!--<li><a href="list_drivers.php">Drivers List</a></li>
								  	<li><a href="list_users.php">Activate or Renew Permits</a></li>-->
									<li><a href="list_users.php">Driver - User List</a></li>
									
									<li><a href="driver_notes_email.php">Driver Notes &amp; Email ch</a></li>
                                    <li><a href="edit_trip.php">Modify Mileage & Gas</a></li>
                                   
                                    <li><a href="chng_charge_dept.php">Change Charge Dept of Closed Trips</a></li>
                                    
                                    <li><a href="vehicle_limit.php">Override 3 Vehicle Limit</a></li>
									<li><a href="list_vhcl_limit.php">Restore 3 Vehicle Limit</a></li>
									<li><a href="info_page.php?id=27">Permit Renewal Steps</a></li>
                                  </ul>
                                </div>
								 <div class="column-1"> 
                                  <h3>REPORTS</h3>
                                   <ul>
                                    <!--<li><a href="list_deleted.php">List Deleted Trips</a></li>-->
                                    <!--<li><a href="driver_deleted_trips.php">Driver Cancelled Trips</a></li>-->
                                    <!--<li><a href="list_abandon.php">List Abandoned Trips</a></li>-->
                                    <li><a href="list_dept.php">List Departments</a></li>
									<li><a href="list_school_cost.php">Trips by School</a></li>
									
									
                                  </ul>
                                </div>
                                
                              </div>
                              <!-- END tutorial menu section ( 2nd menu ) -->
                            </li>
							<li><a href="#">ADMIN</a> 
                              <div class="menu-container-3"> 
							  <div class="column-1"> 
                                  <h3>Users</h3>
                                  <ul>
								  	<li><a href="list_users.php">Driver - User List</a></li>
                                    <!--<li><a href="list_users.php">Activate or Renew Permits</a></li>-->
									<li><a href="list_driver_delete.php">Delete Drivers</a></li>
									<!--<li><a href="actdeact_users.php">Deactivate-Activate</a></li>-->
                                    <li><a href="list_tm_notes.php">List TM Notes about Drivers</a></li>
									<li><a href="list_log.php">List User Logins</a></li>
									<li><a href="driver_notes_email.php">Driver Notes &amp; Email ch</a></li>                                    
                                  </ul>
                                </div>
                                <div class="column-1"> 
                                  <h3>Vehicles</h3>
                                  <ul>
                                    <li><a href="list_vehicles.php">List Vehicles</a></li>
                                    <li><a href="addvehicle.php">Add Vehicle</a></li>
                                    <li><a href="edit_vehicle.php">Edit / View Vehicles</a></li>
									<li><a href="list_vehicle_mileage.php">List Vehicle Mileage</a></li>
                                    <li><a href="list_vehicle_issues.php">Vehicle Issues & Notes</a></li>
									<li><a href="add_shoptask.php">Add Shop Tasks</a></li>
									<li><a href="list_vehicle_tasks.php">List Shop Tasks</a></li>
									<li><a href="sell_vehicle.php">Sell-Dispose of Vehicle</a></li>
                                    <li><a href="list_vehicle_cost.php">Repair Cost Summary</a></li>
                                    <li><a href="list_inspect.php">Inspect-Registrations Due</a></li>
									<li><a href="list_unavailables.php">List Unavailable Vehicles</a></li>
                                    
                                  </ul>
                                </div>
                                
                                <div class="column-1"> 
                                  <h3>Departments</h3>
                                  <ul>
                                    <li><a href="add_dept.php">Add Department</a></li>
                                    <li><a href="list_dept.php">Departments: List or Edit</a></li>
                                  </ul>
                                </div>
                              </div>
                              <!-- END tutorial menu section ( 2nd menu ) -->
                            </li>
							<li><a href="#">RESV</a> 
                              <div class="menu-container-1"> 
                                <div class="column-1"> 
                                  <h3>Reservations</h3>
                                  <ul>
                                    <li><a href="reservations.php">Make Reservations</a></li>
                                    <li><a href="srvc_resv.php">Pull Vehicle for Service</a></li>
                                    <li><a href="srvc_resv_cncl.php">Restore Pulled Vehicle</a></li>
                                  </ul>
                                </div>
                              </div>
                            </li>
							
							<li><a href="javascript:void(0);" onclick='fn_OPEN_CHART();'>GRAPH</a></li>
							
					        <li><a href="#">FINANCE</a> 
                              <div class="menu-container-1"> 
                                <!-- Latest Tuts start -->
                                <div class="column-1"> 
                                  <h3>FINANCE MENUS</h3>
                                  <ul>
									<li><a href="list_restricted_charges.php">List Flat Rate Charges</a></li>
									<li><a href="add_restricted_charges.php">Charge Restr or Pulled Veh.</a></li>
                                    <li><a href="list_dept_cost.php">Dept Cost Summary</a></li>
									<li><a href="list_school_cost.php">Trips by School</a></li>
                                    <li><a href="list_rest_read.php">List Restricted Veh. Mileage Charges</a></li>
                                    <li><a href="list_vehicle_cost.php">Repair Cost Summary</a></li>
                                    <li><a href="chng_charge_dept.php">Change Charge Dept of Closed Trips</a></li>
                                  </ul>
                                </div>
                              </div>
                              <!-- Latest Tuts END -->
                            </li>
							
							
							
							
							 <li><a href="#">MSGS</a> 
                              <div class="menu-container-1"> 
                                <!-- Latest Tuts start -->
                                <div class="column-1"> 
                                  <h3>MESSAGES</h3>
									 <ul>
										<li><a href="deactivate_drivers.php">Send Year End Notice</a></li>
										<li><a href="list_active_drivers.php">Driver Emails: Make & Send</a></li>
										<li><a href="leader_msg.php">Leaders Message</a></li>
										<li><a href="info_page.php?id=22">Year End Notice - View</a></li>
										<li><a href="add_link.php">Add Notice to Home Page</a></li>
										<li><a href="list_links.php">Outgoing Messages Edit</a></li>
										<li><a href="add_notice.php">Edit Login Message</a></li>							
										<li><a href="airport_msg.php">Send Airport Warning</a></li>
										
									  </ul>
                                </div>
                              </div>
                              <!-- Latest Tuts END -->
                            </li>
							<li><a href="#">SYS</a> 
                              <div class="menu-container-1"> 
                                <!-- Latest Tuts start -->
                                <div class="column-1"> 
                                  <h3>System</h3>
                                  <ul>
                                    <li><a href="backup.php">Take Extra Database Backup</a></li>
                                    <li><a href="list_backups.php">Restore System Backup</a></li>
									<li><a href="list_log.php">List User Logins</a></li>
									<li><a href="change_password.php">Change Password</a></li>
                                  </ul>
                                </div>
                              </div>
                              <!-- Latest Tuts END -->
                            </li>
							
							<?	}elseif(isset($_SESSION["User_Group"]) && $_SESSION["User_Group"]==$iGROUP_TM){?>
								
								 
								<li><a href="#">MGMT</a> 
                              <div class="menu-container-4"> 
                                <!-- Start tutorial menu section ( 2nd menu ) -->
                                <div class="column-1"> 
                                  <h3>DAILY</h3>
                                  <ul>
                                    <li><a href="tm_start.php">TM Start Report</a></li>
									<li><a href="add_keycard_trip.php">Make Trip Slip</a></li>
									<li><a href="add_tripdetails.php">Close Trips Slip</a></li>
									<li><a href="list_reports.php">List Trips</a></li>
									<li><a href="list_resv.php">Find Resrv by No</a></li>
                                    
                                    <li><a href="list_pending_trips.php">Change or Delete Open Trips</a></li>
                                    <!--<li><a href="list_trips.php">List Closed Trips</a></li>-->
									 
									 <li><a href="list_vehicles.php">List Vehicles</a></li>
                                  </ul>
                                </div>
                                <div class="column-1"> 
                                  <h3>OTHERS</h3>
                                  <ul>
								  <!--	<li><a href="list_drivers.php">Drivers List</a></li>
                                    <li><a href="list_users.php">Activate or Renew Permits</a></li>-->
									<li><a href="list_users.php">Drivers - User List</a></li>
									<li><a href="driver_notes_email.php">Driver Notes &amp; Email ch</a></li>
                                    <li><a href="edit_trip.php">Modify Mileage & Gas</a></li>
                                   
                                    <li><a href="chng_charge_dept.php">Change Charge Dept of Closed Trips</a></li>
                                    
                                    <li><a href="vehicle_limit.php">Override 3 Vehicle Limit</a></li>
									<li><a href="list_vhcl_limit.php">Restore 3 Vehicle Limit</a></li>
									<li><a href="info_page.php?id=27">Permit Renewal Steps</a></li>
									
                                  </ul>
                                </div>
								 <div class="column-1"> 
                                  <h3>REPORTS</h3>
                                   <ul>
                                    <!--<li><a href="list_deleted.php">List Deleted Trips</a></li>
                                    <li><a href="driver_deleted_trips.php">Driver Cancelled Trips</a></li>
                                    <li><a href="list_abandon.php">List Abandoned Trips</a></li>-->
                                    <li><a href="list_dept.php">List Departments</a></li>
									<li><a href="list_school_cost.php">Trips by School</a></li>
									<!--<li><a href="list_users.php">List or Activate Users</a></li>-->
									<li><a href="list_unavailables.php">List Unavailable Vehicles</a></li>
                                  </ul>
                                </div>
                                
                              </div>
                              <!-- END tutorial menu section ( 2nd menu ) -->
                            </li>
							
							
							<li><a href="#">RESV</a> 
								  <div class="menu-container-1"> 
									<div class="column-1"> 
									  <h3>Reservations</h3>
									  <ul>
										<li><a href="reservations.php">Make Reservations</a></li>
										<li><a href="srvc_resv.php">Pull Vehicle for Service</a></li>
										<li><a href="srvc_resv_cncl.php">Restore Pulled Vehicle</a></li>
									  </ul>
									</div>
								  </div>
								</li>
							
							<li><a href="javascript:void(0);" onclick='fn_OPEN_CHART();'>GRAPH</a></li>
							
							 <li><a href="#">MSGS</a> 
                              <div class="menu-container-1"> 
                                <!-- Latest Tuts start -->
                                <div class="column-1"> 
                                  <h3>MESSAGES</h3>
									 <ul>
									 	<li><a href="deactivate_drivers.php">Send Year End Notice</a></li>
										<li><a href="list_active_drivers.php">Driver Emails: Make & Send</a></li>
										<li><a href="leader_msg.php">Leaders Message</a></li>
										<li><a href="info_page.php?id=22">Year End Notice - View</a></li>
										<li><a href="add_link.php">Add Notice to Home Page</a></li>
										<li><a href="list_links.php">Outgoing Messages Edit</a></li>
										<li><a href="add_notice.php">Edit Login Message</a></li>							
										<li><a href="airport_msg.php">Send Airport Warning</a></li>
										
									  </ul>
                                </div>
                              </div>
                              <!-- Latest Tuts END -->
                            </li>
							
							
							<li><a href="#">VEHICLES</a> 
                              <div class="menu-container-1"> 
                                <!-- Latest Tuts start -->
                                <div class="column-1"> 
                                  <h3>MESSAGES</h3>
									 <ul>
                                    <li><a href="list_vehicles.php">List Vehicles</a></li>
                                    <li><a href="addvehicle.php">Add Vehicle</a></li>
                                    <li><a href="edit_vehicle.php">Edit / View Vehicles</a></li>
									<li><a href="list_vehicle_mileage.php">List Vehicle Mileage</a></li>
                                    <li><a href="list_vehicle_issues.php">Vehicle Issues & Notes</a></li>
									<li><a href="list_vehicle_tasks.php">List Shop Tasks</a></li>
									<li><a href="sell_vehicle.php">Sell-Dispose of Vehicle</a></li>
                                    <li><a href="list_vehicle_cost.php">Repair Cost Summary</a></li>
                                    <li><a href="list_inspect.php">Inspect-Registrations Due</a></li>
                                    
                                  </ul>
                                </div>
                              </div>
                              <!-- Latest Tuts END -->
                            </li>
							<li><a href="change_password.php">CHNG PWD</a></li>
											
							<?	}elseif(isset($_SESSION["User_Group"]) && ($_SESSION["User_Group"]==$iGROUP_DRIVER || $_SESSION["User_Group"]==$iGROUP_COORDINATOR_STAFF)){?>
							<li><a href="#">RESV</a> 
                              <div class="menu-container-1"> 
                                <div class="column-1"> 
                                  <h3>Reservations</h3>
                                  <ul>
                                    <li><a href="reservations.php">Make Reservations</a></li>
									<li><a href="cancel_trip.php">Cancel Reservation</a></li>
									<li><a href="list_vehicles.php">List Vehicles</a></li>
									<li><a href="javascript:void(0);" onclick='fn_OPEN_CHART();'>Show Available Vans</a></li>
									<?Php
										$sSQL			=	"SELECT driver_permission FROM tbl_user WHERE user_id = ".$_SESSION["User_ID"];
										$bPERMISSION	=	mysql_result(mysql_query($sSQL),0);
										if($bPERMISSION==1){
									?>
											<li><a href="list_users.php">Drivers List</a></li>
											<li><a href="list_school_cost.php">Trips Taken</a></li>
											
									<?	}	?>		
                                  </ul>
                                </div>
                              </div>
                            </li>
							<li><a href="#">RENEW PERMIT</a>
								<div class="menu-container-1"> 
									<div class="column-1"> 
									  <h3>Renew Permit</h3>
									  <ul>
										<li><a href="info_page.php?id=22">Year end Driver Permit Renewal Info</a></li>
										<li><a href="edit_user.php">Update Registration</a></li>
										<li><a href="info_page.php?id=20">Driver Renewal Request Form</a></li>
										<li><a href="info_page.php?id=5">Returning Driver Info</a></li>
									  </ul>
									</div>
								  </div>
							</li>
							<li><a href="change_password.php">CHNG PWD</a></li>
							<?	}
							
								if(!isset($_SESSION["User_ID"])){?>
							<li><a href="login.php">LOGIN</a></li>
							<?	}else{?>
							<li><a href="logout.php">EXIT</a></li>
							<?	}?>
					        
					</ul>
				</td>
               </tr>
              </table>
             </td>
            </tr>
            <tr valign="top" align="left">
             <td width="47" height="22"><img src="../assets/images/autogen/clearpixel.gif" width="47" height="1" border="0" alt=""></td>
             <td width="466"><img src="../assets/images/autogen/clearpixel.gif" width="466" height="1" border="0" alt=""></td>
             <td width="178"><img src="../assets/images/autogen/clearpixel.gif" width="178" height="1" border="0" alt=""></td>
             <td width="9"><img src="../assets/images/autogen/clearpixel.gif" width="9" height="1" border="0" alt=""></td>
            </tr>
            <tr valign="top" align="left">
             <td colspan="2"></td>
             <td width="178" class="TextObject">
              <p style="text-align: center; margin-bottom: 0px;"><b><span style="font-size: 12pt; color: rgb(255,204,0);">&nbsp;</span></b></p>
             </td>
             <td></td>
            </tr>
           </table>
          </td>
         </tr>
        </table>
       </td>
      </tr>
	  
	   <tr valign="top" align="left">
       <td width="980">
        
		<table border="0" cellspacing="0" cellpadding="0" width="980" style="background-image: url('../assets/images/topheader.gif'); background-repeat:no-repeat; background-color:#fff;">
         <tr align="left" valign="top">
          <td>
           
           <table border="0" cellspacing="0" cellpadding="0" width="964">
            <tr valign="top" align="left">
             <td width="16" height="4"><img src="../assets/images/autogen/clearpixel.gif" width="16" height="1" border="0" alt=""></td>
             <td></td>
            </tr>
            <tr valign="top" align="left">
             <td height="167"></td>
             <td width="948"><img id="Picture8" height="167" width="948" src="../assets/images/mpic.jpg" border="0"></td>
            </tr>
           </table>
           <table cellpadding="0" cellspacing="0" border="0" width="960">
            <tr valign="top" align="left">
             
             