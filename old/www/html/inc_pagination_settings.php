								<?	$max = 50;
									$cur_rows = 0;
									$max_rows = $max;
									$pg	=	0;
									if(isset($_POST['pg']))	$pg = $_POST['pg'];
									if ( $pg == 0 )	$pg	=	1;
									
									if($pg!=1)
									{
										for($i=1;$i<$pg;$i++)
										{
											$cur_rows=$cur_rows+$max;
											$max_rows=$max_rows+$max;
										}
									}
								
								?>
