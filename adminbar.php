<?php 
if(checkRoles($_GET['u']) == 1)
{
	echo "<select id='USERS' onchange='changeUser()' name='USERS' style='border: solid 1px #e0e0e0; border-radius:5px; display:inline;outline:0;padding:.75em;width:50%;margin-left:10%;'>";
		$people = mysql_query('SELECT * FROM rcr_db.rcr_person ORDER BY l_name', $conn) or die(mysql_error());
		if(mysql_num_rows($people)==0)
		{
			echo "<option>ERROR</option>";
		}
		else
		{
			while($row = mysql_fetch_assoc($people))
			{
				if($row['net_id'] == $netid)
				{
					echo "<option value='".$row['net_id']."' selected>".$row['l_name'].", ".$row['f_name']." (".$row['net_id'].") </option>";
				}
				else{
					echo "<option value='".$row['net_id']."'>".$row['l_name'].", ".$row['f_name']." (".$row['net_id'].") </option>";
				}
			}
		}
	echo "</select>";
	echo "<input type ='text' id = 'USERSEARCH' onkeypress='searchKeyPress(event);' style='margin-left: 1%; border: solid 1px #e0e0e0; display:inline; border-radius:5px; outline:0; width:20%;' placeholder = 'Enter a NetID to search'>";
	echo "<div class='button' id = 'btnSearch' style='margin-left: 1%; padding: 15px; width:10%; text-align: center; font-size:100%; line-height:20px;' onclick='searchUser();'>Search User</div>";
}		


?>
