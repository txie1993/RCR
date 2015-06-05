<!DOCTYPE HTML>
<?php

include 'login_function.php';
/*for security purposes, the servername, username, and password have not been included.*/

$conn = mysql_connect($servername, $username, $password, 'rcr_db');
if ($conn->connect_error) {
    die('CONNECTION FAILED');
}
ContinueSession("../courses.php?");
$netid = $_GET['u'];
if ($_GET['a_uid']) {
    $netid = $_GET['a_uid'];
}

if (isset($_POST['invisButton'])) {
    $courseid = $_POST['invisButton'];
    $msSQLDate = date("Y-m-d H:i:s");
    $remove_string = "DELETE FROM rcr_db.rcr_course_history WHERE course_id = $courseid AND net_id = '" . $netid . "'";
    $queryResult = mysql_query($remove_string);
}
if (isset($_POST['invisButton2'])) {
    $courseid = $_POST['invisButton2'];
    $msSQLDate = date("Y-m-d H:i:s");
    $insert_string = "INSERT INTO rcr_db.rcr_course_history (net_id, course_id, date_registered) VALUES ('" . $netid . "'," . $courseid . ",'" . $msSQLDate . "')";
    $queryResult = mysql_query($insert_string);
}
if (isset($_POST['invisButton3'])) {
    $courseid = $_POST['invisButton3'];
    $update_string = "UPDATE rcr_db.rcr_course_history SET date_completed = NULL WHERE course_id = '" . $courseid . "' AND net_id = '" . $netid . "'";
    $queryResult = mysql_query($update_string);
}
if (isset($_POST['date-save-single'])) {
    $courseid = $_POST['course_date_toggle'];
    $str_date = $_POST['date_toggled'];
    $p_date = strtotime($str_date);
    $c_date = date('Y-m-d', $p_date);
    $update_string = "UPDATE rcr_db.rcr_course_history SET date_completed = '" . $c_date . " 00:00:00' WHERE course_id = '" . $courseid . "' AND net_id = '" . $netid . "'";
    $queryResult = mysql_query($update_string);
}

?>
<html>
<head>
    <title>Courses</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta name="description" content=""/>
    <meta name="keywords" content=""/>
    <!--[if lte IE 8]>
    <script src="css/ie/html5shiv.js"></script><![endif]-->
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery.dropotron.min.js"></script>
    <script src="js/skel.min.js"></script>
    <script src="js/datepicker.js"></script>
    <script src="js/skel-layers.min.js"></script>
    <script src="js/init.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
    <noscript>
        <link rel="stylesheet" href="css/skel.css"/>
        <link rel="stylesheet" href="css/style.css"/>
        <link rel="stylesheet" href="css/style-wide.css"/>
        <link rel="stylesheet" href="css/datepicker.css"/>
    </noscript>
    <!--[if lte IE 8]>
    <link rel="stylesheet" href="css/ie/v8.css"/><![endif]-->
</head>
<body>

<?php include 'nav.php'; ?>

<div>
    <script type="text/javascript">
        window.onload = function () {
            var tfrow = document.getElementById('tfhover').rows.length;
            var tbRow = [];
            for (var i = 1; i < tfrow; i++) {
                tbRow[i] = document.getElementById('tfhover').rows[i];
                tbRow[i].onmouseover = function () {
                    this.style.backgroundColor = '#F0E1F2';
                };
                tbRow[i].onmouseout = function () {
                    this.style.backgroundColor = '#ffffff';
                };
            }
        };
        function displayoverlay(x) {
            var to_lock = document.getElementById('all');
            to_lock.setAttribute("disabled", true);
            document.getElementById("overlay").style.visibility = "visible";
            document.getElementById("form_add_course").style.visibility = "visible";
            var b = document.getElementById("course_date_toggle");
            b.value = x;
        }
        function closeOverlay(type) {

            if (type == "course") {
                var to_lock = document.getElementById('openModal');
                to_lock.setAttribute("disabled", false);
                document.getElementById("overlay").style.visibility = "hidden";
                document.getElementById("form_add_course").style.visibility = "hidden";
            }

        }
        function pressinvis(x) {
            var b = document.getElementById("invisButton");
            b.value = x;
            document.getElementById('invisButton').click();
        }
        function pressinvis2() {
            var menu = document.getElementById('COURSES');
            var dropdown = menu.value;
            var b = document.getElementById("invisButton2");
            b.value = dropdown;
            document.getElementById('invisButton2').click();
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
        function changeUser() {
            var q = window.location.toString();
            var index = document.getElementById('USERS');
            var netid = index.options[index.selectedIndex].value;


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
        window.addEvent('load', function () {
            new DatePicker('.date_toggled', {
                pickerClass: 'datepicker_dashboard',
                allowEmpty: true,
                toggleElements: '.date_toggler'
            });
        });
        function confirmDelete(x) {
            var r = confirm("Are you sure you want to delete?");
            if (r == true) {
                var b = document.getElementById("invisButton3");
                b.value = x;
                document.getElementById('invisButton3').click();
            }

        }
    </script>

    <script>
        $(function () {
            $(".datepicker").datepicker();
        });
    </script>
    <div id=all>
        </br>
        <?php include 'adminbar.php'; ?>
        <h2>My Courses</h2>
    </div>
    <table id="tfhover" class="tftable" border="1" align="center">
        <tr>
            <th style="font-weight:bold">Course Name</th>
            <th style="font-weight:bold">Course Date</th>
            <th style="font-weight:bold">Date Registered</th>
			<th style="font-weight:bold">Date Completed</th>
            <th style="font-weight:bold">Status</th>
        </tr>
        <?php
        $course_counter = 1;
        $mycourses;
        if ($netid != null) {
            $qstring = "SELECT rcr_course_history.course_id, net_id,date_completed, date_registered, rcr_courses.type_id, course_date, detailed_name, rcr_course_type_lookup.type_name FROM rcr_db.rcr_course_history INNER JOIN rcr_db.rcr_courses ON rcr_course_history.course_id = rcr_courses.course_id INNER JOIN rcr_db.rcr_course_type_lookup ON rcr_course_type_lookup.type_id = rcr_courses.type_id WHERE net_id='" . $netid . "' ORDER BY type_id";
            $mycourses = mysql_query($qstring);
        }
        $coursecount = array();
        while ($row = mysql_fetch_assoc($mycourses)) {
            echo "<tr><td>" . $row['type_name'] . ":</strong> " . $row['detailed_name'] . "</td>";
			if ($row['course_date']) echo "<td>" . date("m/d/Y", strtotime($row['course_date'])) . "</td>";
			else echo "<td>No Date</td>";
            if ($row['date_completed'] != null) {
                $coursecount[$row['type_id']]++;
            }
            if ($row['date_registered']) echo "<td>" . date("m/d/Y", strtotime($row['date_registered'])) . "</td>";
			else echo "<td>No Date</td>";			
            if ($row['date_completed'] != null) {
				echo "<td> ".date("m/d/Y", strtotime($row['date_completed']))."</td>";
                echo "<td  onclick='confirmDelete(\"" . $row['course_id'] . "\")'><a style='color:green'>Complete</a></td>";
            } else {
				echo "<td>No Date</td>";
                echo "<td style='color:red' onclick='displayoverlay(\"" . $row['course_id'] . "\")'><a style='color:red'>Not Completed</a></td>";
            }
            if (canEditUser($_GET['u'], $_GET['a_uid'])) {
                echo "<td><div class='button' style='text-align: center; margin-left:20%; font-size:100%; line-height:20px;' value='" . $row['course_id'] . "' onclick='pressinvis(\"" . $row['course_id'] . "\")'><em class='icon minor fa-times'></em></div></td></tr>";
            }
            echo "</div>";
        }
        echo "<form method='POST'><input type = 'hidden' name = 'coursescounter' value = $ccstring></form>";
        ?>
    </table>
    <?php
    $deptreqs = mysql_query("SELECT * FROM rcr_db.rcr_department_requirements INNER JOIN rcr_db.rcr_person ON rcr_person.net_id = '" . $netid . "' INNER JOIN rcr_db.rcr_department ON rcr_department.dept_id = rcr_department_requirements.dept_id WHERE rcr_department_requirements.dept_id = rcr_person.dept_id ORDER BY rcr_department_requirements.dept_id");
    $tf = array();
    $reqsdone;


    while ($row = mysql_fetch_assoc($deptreqs)) {
        $mydept = $row['dept_name'];
        $mydeptid = $row['dept_id'];
        if ($coursecount[$row['type_id']] >= $row['num_req']) {
            $tf[$row['type_id']] = true;
        } else $tf['type_id'] = false;
        $reqsdone = true;
        foreach ($tf as $tf2) {
            if ($tf2 == false) {
                $reqsdone = false;
                break;
            } else continue;
        }
    }

	if (!$mydept || !$mydeptid){
		$deptreqs2 = mysql_query("SELECT * FROM rcr_db.rcr_person INNER JOIN rcr_db.rcr_department ON rcr_department.dept_id = rcr_person.dept_id WHERE net_id = '" . $netid . "'");
		while ($row = mysql_fetch_assoc($deptreqs2)) {
			$mydept = $row['dept_name'];
			$mydeptid = $row['dept_id'];
		}
	}
    if ($reqsdone == true) {
        $searchcert = mysql_query("SELECT * FROM rcr_db.rcr_certification_history WHERE net_id = '" . $netid . "' AND dept_id = '" . $mydeptid . "'");
        if (mysql_num_rows($searchcert) <= 0) {
            $incert = mysql_query("INSERT INTO rcr_db.rcr_certification_history (net_id,cert_type_id,reqs_completed,dept_id) VALUES ('" . $netid . "', 1, now(),'" . $mydeptid . "')");
        }
    } else {
        $searchcert = mysql_query("SELECT * FROM rcr_db.rcr_certification_history WHERE net_id = '" . $netid . "' AND dept_id = '" . $mydeptid . "'");
        if (mysql_num_rows($searchcert) >= 1) {
            $incert = mysql_query("DELETE FROM rcr_db.rcr_certification_history WHERE net_id = '" . $netid . "' AND dept_id = '" . $mydeptid . "'");
        }
        echo "<p style = 'color:red;margin-left:10%'>Has not completed certification requirements for '" . $mydept . "'</p>";
    }

    $certhist = mysql_query("SELECT * FROM rcr_db.rcr_certification_history INNER JOIN rcr_db.rcr_department ON rcr_department.dept_id = rcr_certification_history.dept_id WHERE net_id = '" . $netid . "' ORDER BY cert_id;");
    while ($row = mysql_fetch_assoc($certhist)) {
        echo "<p style = 'color:green;margin-left:10%'>Received certification for <b>" . $row['dept_name'] . "</b></p>";
    }
    if (checkRoles($_GET['u']) == 1) {
        echo "<form name='courseBar' id='courseBar' method='post'>";
        echo "<select id='COURSES' name='USERS' style='border: solid 1px #e0e0e0; border-radius:5px; display:inline;outline:0;padding:.75em;width:60%;margin-left:10%;'>";
        $courselist = mysql_query("SELECT * FROM rcr_db.rcr_courses INNER JOIN rcr_db.rcr_course_type_lookup ON rcr_db.rcr_courses.type_id = rcr_db.rcr_course_type_lookup.type_id WHERE rcr_db.rcr_courses.course_id NOT IN (SELECT course_id FROM rcr_db.rcr_course_history WHERE net_id = '" . $netid . "') ORDER BY rcr_db.rcr_courses.type_id,rcr_db.rcr_courses.detailed_name,rcr_db.rcr_courses.course_date", $conn) or die(mysql_error());
        if (mysql_num_rows($courselist) == 0) {
            echo "<option>No Courses</option>";
        } else {
            while ($row = mysql_fetch_assoc($courselist)) {
                if($row['course_date']) echo "<option id = 'newcourse' value='" . $row['course_id'] . "' selected>" . $row['type_name'] . ": " . $row['detailed_name'] . " (" . date("m/d/Y", strtotime($row['course_date'])) . ")</option>";
				else echo "<option id = 'newcourse' value='" . $row['course_id'] . "' selected>" . $row['type_name'] . ": " . $row['detailed_name'] . "</option>";
            }
        }

        echo "</select>";
        echo "<div class='button' style='padding: 20px; width:10%; text-align: center; margin-left:10%; font-size:100%; line-height:20px;' value='" . $row['course_id'] . "' onclick='pressinvis2()'>Add Course</div>";
        echo "</form>";
    }


    ?>
</div>
<div id="openModal" class="modalDialog">
    <div id='form_add_course'>
        <form method="POST">
            <a title="Close" onclick='closeOverlay("course")' class="close">X</a>

            <h2>Date Completed</h2>
            <input name='date_toggled' type='text' value='' class='datepicker' style='display: inline'/>
            <?php echo "<input name='course_date_toggle' id = 'course_date_toggle' type='hidden'  value='' style='display: inline' />"; ?>
            <p></p>
            <button type="submit" class="register_button" name='date-save-single' value="date-save-single">SAVE</button>
        </form>
    </div>
</div>
<div id="overlay" class='modalOverlay'>
</div>


<form name='register_form' id='register_form' method='POST'>
    <button type="submit" value="" style="opacity: 0; font-size:0.1px;" name="invisButton" id="invisButton"></button>
</form>
<form name='register_form' id='register_form' method='POST'>
    <button type="submit" value="" style="opacity: 0; font-size:0.1px;" name="invisButton2" id="invisButton2"></button>
</form>
<form name='register_form' id='register_form' method='POST'>
    <button type="submit" value="" style="opacity: 0; font-size:0.1px;" name="invisButton3" id="invisButton3"></button>
</form>
</div>
</body>
</html>