<?php 
if(checkRoles($_GET['u']) == 1)
{
	echo "<form name='courseBar' id='courseBar' method='post'>";
	echo "<select id='COURSES' name='USERS' style='border: solid 1px #e0e0e0; border-radius:5px; display:inline;outline:0;padding:.75em;width:80%;margin-left:10%;'>";
		$courselist = mysql_query('SELECT * FROM rcr_db.rcr_courses ORDER BY type_id', $conn) or die(mysql_error());
		if(mysql_num_rows($courselist)==0)
		{
			echo "<option>ERROR</option>";
		}
		else
		{
			while($row = mysql_fetch_assoc($courselist))
			{
					echo "<option value='".$row['course_id']."' selected>".$row['type_id'].", ".$row['detailed_name']."</option>";
			}
		}
	echo "</select>";
}		
echo "</form>";
echo 	"<div class='button' style='padding: 20px; text-align: center; margin-left:45%; font-size:100%; line-height:20px;' value='".$row['course_id']."' onclick='pressinvis2(\"".$row['course_id']."\")'>Add Course</div>";
?>
