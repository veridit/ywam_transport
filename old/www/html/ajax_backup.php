<?Php
		include('inc_connection.php');
		include('inc_function.php');
		
		$sACTION	=	"";
		$sACTION 			= 	$_REQUEST["action"];
		
			
if ($sACTION == "backup"){

	// Generate the filename for the backup file
			$filess = 'backup/dbbackup_' . date("m_d_Y_H_i_s").'.sql';
			$return = "";

			// Get all of the tables
			$arrTables = array();
			$result = mysql_query('SHOW TABLES') or die(mysql_error());

			while($row = mysql_fetch_row($result)) {$arrTables[] = $row[0];	}
			 
			// Cycle through each provided table
			foreach($arrTables as $Each_Table) {
			
				$result = mysql_query('SELECT * FROM '.$Each_Table) or die(mysql_error());
				$num_fields = mysql_num_fields($result);
			
				// First part of the output - remove the table
				$return .= 'DROP TABLE ' . $Each_Table . ';<|||||||>';
				//$return .= 'DROP TABLE ' . $Each_Table . ';';
	
				// Second part of the output - create table
				$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$Each_Table));
				$return .= "\n\n" . $row2[1] . ";<|||||||>\n\n";
				//$return .= "\n\n" . $row2[1] . ";\n\n";
	
				// Third part of the output - insert values into new table
				for ($i = 0; $i < $num_fields; $i++) {
					while($row = mysql_fetch_row($result)) {
						$return.= 'INSERT INTO '.$Each_Table.' VALUES(';
						for($j=0; $j<$num_fields; $j++) {
							$row[$j] = addslashes($row[$j]);
							//$row[$j] = ereg_replace("\n","\\n",$row[$j]);
							$row[$j] = preg_replace("/\n/","\\n",$row[$j]);
							if (isset($row[$j])) { 
								$return .= '"' . $row[$j] . '"'; 
							} else { 
								$return .= '""'; 
							}
							if ($j<($num_fields-1)) { 
								$return.= ','; 
							}
						}
						$return.= ");<|||||||>\n";
						//$return.= ");\n";
					}
				}
				$return.="\n\n\n";
			}

	
			// Save the sql file
			$handle = fopen($filess,'w+');
			fwrite($handle,$return);
			fclose($handle);

			// Print the message
			$sMessage	=	"The backup has been created successfully. <br />You can get <b>MySQL dump file</b> <a href='download.php?f=$filess&Dir=./backup/'>download here</a>.<br>";
			echo $sMessage;
}



?>