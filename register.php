<!DOCTYPE HTML>
<?php 

include 'login_function.php';

/*for security purposes, the servername, username, and password have not been included.*/

			$conn = mysql_connect($servername,$username,$password,'rcr_db');
			if($conn->connect_error){
				die('CONNECTION FAILED');
			}
			
			ContinueSession("../register.php?");
			$netid = $_GET['u'];
			if($_GET['a_uid'])
			{
				$netid = $_GET['a_uid'];
			}
			
			if(isset($_POST['invisButton'])){
				$courseid = $_POST['invisButton'];
				$msSQLDate = date("Y-m-d H:i:s");
				//$registereddate = $msSQLDate;
				$insert_string = "INSERT INTO rcr_db.rcr_course_history (net_id, course_id, date_registered) VALUES ('".$netid."',".$courseid.",'".$msSQLDate."')";
				$queryResult = mysql_query($insert_string);
			}
			
			if(isset($_POST['invisButton2'])){
				$courseid = $_POST['invisButton'];
				$msSQLDate = date("Y-m-d H:i:s");
				//$registereddate = $msSQLDate;
				$insert_string = "INSERT INTO rcr_db.rcr_course_history (net_id, course_id, date_registered) VALUES ('".$netid."',".$courseid.",'".$msSQLDate."')";
				$queryResult = mysql_query($insert_string);
			}
			
			
?>
<html>
<head>
<title>Register</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="description" content="" />
<meta name="keywords" content="" />
<!--[if lte IE 8]><script src="css/ie/html5shiv.js"></script><![endif]-->
<script src="js/jquery.min.js"></script>
<script src="js/jquery.dropotron.min.js"></script>
<script src="js/skel.min.js"></script>
<script src="js/skel-layers.min.js"></script>
<script src="js/init.js"></script>
<noscript>
<link rel="stylesheet" href="css/skel.css" />
<link rel="stylesheet" href="css/style.css" />
<link rel="stylesheet" href="css/style-wide.css" />
</noscript>
<!--[if lte IE 8]><link rel="stylesheet" href="css/ie/v8.css" /><![endif]-->
</head>
<body>

  <?php include 'nav.php';?> 
<script type="text/javascript">
	window.onload=function(){
	var tfrow = document.getElementById('tfhover').rows.length;
	var tbRow=[];
	for (var i=1;i<tfrow;i++) {
		tbRow[i]=document.getElementById('tfhover').rows[i];
		tbRow[i].onmouseover = function(){
		  this.style.backgroundColor = '#F0E1F2';
		};
		tbRow[i].onmouseout = function() {
		  this.style.backgroundColor = '#ffffff';
		};
	}
};
	function pressinvis(x) {
		var b = document.getElementById("invisButton");
		b.value = x;
		document.getElementById('invisButton').click();
	}

	
	function changeUser()
{
		var q = window.location.toString();
		var index = document.getElementById('USERS');
		var netid = index.options[index.selectedIndex].value;
	

		if(q.indexOf("&a_uid") > -1)
		{
			q = q.substring(0,q.lastIndexOf('&a_uid'));
			q = q + "&a_uid=" + netid;
			window.open(q,"_self");
		}
		else{
			q = q + "&a_uid=" + netid;
			window.open(q,"_self");
			}
	//document.getElementById('userBar').submit();
}
		 function searchKeyPress(e)
		{
        // look for window.event in case event isn't passed in
			e = e || window.event;
			if (e.keyCode == 13)
			{
				searchUser();
			}
		}
function searchUser() {
            var q = window.location.toString();
            var index = document.getElementById('USERSEARCH');
            var netid = index.value;


            if (q.indexOf("&a_uid") > -1) {
                q = q.substring(0, q.lastIndexOf('&a_uid'));
                q = q + "&a_uid=" + netid;
                window.open(q, "_self");
            }
            else {
            q = q + "&a_uid=" + netid;
           window.open(q, "_self");
        }
    }

</script>
<div>
</br>
<?php include 'adminbar.php';?>
<h2>Register for a Course</h2>
</div>

<table id="tfhover" class="tftable" border="1" align="center">
<tr><th style="font-weight:bold">Course Name</th><th style="font-weight:bold">Course Date</th><th style="font-weight:bold">Register</th></tr>
<?php
$mycourses;
if($netid != null){
			//$qstring = "SELECT rcr_courses.course_id, detailed_name, course_date, rcr_course_type_lookup.type_name FROM rcr_db.rcr_courses INNER JOIN rcr_db.rcr_course_type_lookup ON rcr_courses.type_id = rcr_course_type_lookup.type_id ORDER BY course_date";
			$qstring = "SELECT rcr_courses.course_id, detailed_name, course_date, rcr_courses.type_id, rcr_course_type_lookup.type_name 
			FROM rcr_db.rcr_courses 
			INNER JOIN rcr_db.rcr_course_type_lookup 
			ON rcr_courses.type_id = rcr_course_type_lookup.type_id 
			WHERE rcr_courses.type_id = 1 OR rcr_courses.type_id = 2
            AND NOT EXISTS
			(SELECT rcr_course_history.course_id FROM rcr_db.rcr_course_history WHERE net_id = '".$net_id."' 
			AND rcr_course_history.course_id = rcr_courses.course_id) 
			ORDER BY rcr_courses.type_id,rcr_db.rcr_courses.detailed_name,rcr_db.rcr_courses.course_date";
			$mycourses = mysql_query($qstring);
			}

while($row = mysql_fetch_assoc($mycourses)){
	echo	"<tr><td>".$row['type_name'].":</strong> ".$row['detailed_name']."</td>";
	if ($row['course_date'] != NULL) echo    "<td style='text-align:center'>".date("m/d/Y", strtotime($row['course_date']))."</td>";
	else echo    "<td style='text-align:center'></td>";
	echo    "<td><div class='button' style='text-align: center; margin-left:45%; font-size:100%; line-height:20px;' value='".$row['course_id']."' onclick='pressinvis(\"".$row['course_id']."\")'><em class='icon minor fa-plus'></em></div></td></tr>";
} 

?>
</table>
<!--</div>-->
<form name='register_form' id='register_form' method='POST'><button type="submit" value="" style="opacity: 0; font-size:0.1px;" name = "invisButton" id="invisButton"></button></form>

</body>
</html>
