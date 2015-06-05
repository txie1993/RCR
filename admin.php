<!DOCTYPE HTML>
<?php

function cutQString()
{
    //CUTS UNNECESSARY QUERY STRING PARTS, KEEPING ONLY SESSION INFO AND LAST PART

    //DO NOT REMOVE
    $qstringarray = explode('&', $_SERVER['QUERY_STRING']);
    $separatorcount = substr_count($_SERVER['QUERY_STRING'], '&');
    if ($qstringarray[2] != NULL AND $qstringarray[$separatorcount] != NULL AND $separatorcount != 2) {
        $additionalInfo = "&" . $qstringarray[$separatorcount];
        $loginQuery = $qstringarray[0] . "&" . $qstringarray[1];
        ContinueSessionWParams("../admin.php?", $loginQuery, $additionalInfo);
    }
}

include 'login_function.php';
/*for security purposes, the servername, username, and password have not been included.*/
$conn = mysql_connect($servername, $username, $password, 'rcr_db');
if ($conn->connect_error) {
    die('CONNECTION FAILED');
}


ContinueSession("../admin.php?");


$netid = $_GET['u'];
$selected_dept_id = $_GET['d_id'];
$selected_course_id = $_GET['c_id'];
cutQString();
$course_types = mysql_query("SELECT * FROM rcr_db.rcr_course_type_lookup ORDER BY type_name");
$departments = mysql_query("SELECT * FROM rcr_db.rcr_department ORDER BY dept_name");
$requirements = mysql_query("SELECT * FROM rcr_db.rcr_department_requirements ORDER BY req_id");
if (isset($_POST['user-save-single'])) {
    $f_name = $_POST['new-user-first'];
    $l_name = $_POST['new-user-last'];
    $netid = $_POST['new-user-netid'];
    $password = $_POST['new-user-password'];

    $insert_string = "INSERT INTO rcr_db.rcr_person(f_name,l_name,net_id, email) VALUES ('" . $f_name . "', '" . $l_name . "', '" . $netid . "', '" . $netid . "@nyu.edu')";
    $queryResult = mysql_query($insert_string);
    if (mysql_num_rows($queryResult) < 1) {
        $insert_login = "INSERT INTO rcr_db.rcr_logins(net_id,password,bool_active,group_id) VALUES ('" . $netid . "', '" . $password . "', 1, '3')";
        mysql_query($insert_login);
    }
    $additionalInfo = "&a_uid=" . $netid;
    $qstringarray = explode('&', $_SERVER['QUERY_STRING']);
    $loginQuery = $qstringarray[0] . "&" . $qstringarray[1];
    ContinueSessionWParams("../profile.php?", $loginQuery, $additionalInfo);
}
if (isset($_POST['course-save-single'])) {
    $c_type = $_POST['new-course-type'];
    $c_name = $_POST['new-course-name'];
	$c_netid = $_POST['cnetid'];
    $str_date = $_POST['new-course-date2'];
    $p_date = strtotime($str_date);
    $c_date = date('Y-m-d', $p_date);
	
	//THIS PART LOOKS FOR THE COURSE ID OF LAB SAFETY, ASSUMES ONLY 1
	$coursefinder = mysql_query("SELECT * FROM rcr_db.rcr_courses WHERE type_id = 3");
	while ($row = mysql_fetch_assoc($coursefinder)) {
		$labsafety = $row['course_id'];
	}
	
	//THIS PART LOOKS FOR THE COURSE ID OF CITI, ASSUMES ONLY 1
	$coursefinder = mysql_query("SELECT * FROM rcr_db.rcr_courses WHERE type_id = 4");
	while ($row = mysql_fetch_assoc($coursefinder)) {
		$CITI = $row['course_id'];
	}
	
	
	if ($c_type == 3) $insert_string = "INSERT INTO rcr_db.rcr_course_history (course_id, net_id, date_registered, date_completed) VALUES (".$labsafety.", '".$c_netid."', now(), '".$c_date."')";
	else if ($c_type == 4) $insert_string = "INSERT INTO rcr_db.rcr_course_history (course_id, net_id, date_registered, date_completed) VALUES (".$CITI.", '".$c_netid."', now(), '".$c_date."')";
    else $insert_string = "INSERT INTO rcr_db.rcr_courses(type_id,detailed_name,course_date) VALUES (" . $c_type . ", '" . $c_name . "', '" . $c_date . " 00:00:00')";
    $queryResult = mysql_query($insert_string);
}
if (isset($_POST['req-save-single'])) {
    $d_type = $_POST['dept-type'];
    $inheritreqs = 0;
    if (isset($_POST['dept-inherit'])) {
        $inheritreqs = 1;
    }
    if ($inheritreqs == 1) {

    }
    foreach ($_POST['numreq'] as $typeId => $numreq) {
        if ($typeId == NULL || $numreq == NULL) {
            continue;
        }
        if ($numreq == 0) {
            $delete_num = "DELETE FROM rcr_db.rcr_department_requirements WHERE dept_id = '" . $d_type . "' AND type_id = '" . $typeId . "'";
            $delQuery = mysql_query($delete_num);
        } else {
            $update_num = "UPDATE rcr_db.rcr_department_requirements SET num_req = $numreq WHERE dept_id = '" . $d_type . "' AND type_id = '" . $typeId . "'";
            $upQuery = mysql_query($update_num);
        }
    }

    $update_string = "UPDATE rcr_db.rcr_department SET inherits_reqs=$inheritreqs WHERE dept_id = '" . $d_type . "'";
    $queryResult = mysql_query($update_string);
}
if (isset($_POST['req-add-single'])) {
    $d_type = $_POST['dept-type'];
    $c_type = $_POST['add-type-req'];
    $numrequired = $_POST['newnumreq'];
    $insert_req = "INSERT INTO rcr_db.rcr_department_requirements (dept_id,type_id,num_req) VALUES ('" . $d_type . "', '" . $c_type . "', $numrequired)";
    $insQuery = mysql_query($insert_req);
}
if (isset($_POST['invisButton'])) {
    $deptid = $_POST['invisButton'];
    echo $deptid;
    $numreq = $_POST['numreq'];
    if (empty($_POST['numreq'])) {
        echo "failure";
    } 
    $update_string = "UPDATE rcr_db.rcr_course_history SET num_req = $numreq WHERE dept_id = '" . $deptid . "'";
    $queryResult = mysql_query($remove_string);
}

?>
<html>
<head>
    <title>Admin</title>
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
    <script>
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
            //alert('loading...');
            <?php
            if($selected_dept_id != null)
            {
            echo "displayoverlay('req');";
            }
            if($selected_course_id != null)
            {
            echo "displayoverlay('course');";
            }
            ?>
        };
        function pressinvis(x) {
            var b = document.getElementById("invisButton");
            b.value = x;
            document.getElementById('invisButton').click();
            //alert(b.value);
        }
        function changeReq() {
            var index = document.getElementById('dept-type');
            var reqs = index.options[index.selectedIndex].value;
            return reqs;
        }
        function changeDepartment() {
            var q = window.location.toString();
            var index = document.getElementById('dept-type');
            var deptid = index.options[index.selectedIndex].value;


            if (q.indexOf("&d_id") > -1) {
                q = q.substring(0, q.lastIndexOf('&d_id'));
                //alert(q);
                q = q + "&d_id=" + deptid;
                //alert(q);
                window.open(q, "_self");
            }
            else {
                q = q + "&d_id=" + deptid;
                //alert("else "+q);
                window.open(q, "_self");
            }
            //document.getElementById('userBar').submit();
        }
        function changeType() {
            var q = window.location.toString();
            var index = document.getElementById('crstype');
            var crsid = index.options[index.selectedIndex].value;


            if (q.indexOf("&c_id") > -1) {
                q = q.substring(0, q.lastIndexOf('&c_id'));
                //alert(q);
                q = q + "&c_id=" + crsid;
                //alert(q);
                window.open(q, "_self");
            }
            else {
                q = q + "&c_id=" + crsid;
                //alert("else "+q);
                window.open(q, "_self");
            }
            //document.getElementById('userBar').submit();
        }
        function displayoverlay(type) {
            if (type == "user") {
                var to_lock = document.getElementById('admin-tools');
                to_lock.setAttribute("disabled", true);
                document.getElementById("overlay").style.visibility = "visible";
                document.getElementById("openModal").style.visibility = "visible"
                document.getElementById("form_add_user").style.visibility = "visible";
            }
            if (type == "course") {
                var to_lock = document.getElementById('admin-tools');
                to_lock.setAttribute("disabled", true);
                document.getElementById("overlay").style.visibility = "visible";
                document.getElementById("openModal").style.visibility = "visible"
                document.getElementById("form_add_course").style.visibility = "visible";
            }
            if (type == "req") {
                var to_lock = document.getElementById('admin-tools');
                to_lock.setAttribute("disabled", true);
                document.getElementById("overlay").style.visibility = "visible";
                document.getElementById("openModal").style.visibility = "visible"
                document.getElementById("form_edit_req").style.visibility = "visible";
            }
        }
        function closeOverlay(type) {
            if (type == "user") {
                var to_lock = document.getElementById('admin-tools');
                to_lock.setAttribute("disabled", false);
                document.getElementById("overlay").style.visibility = "hidden";
                document.getElementById("openModal").style.visibility = "hidden"
                document.getElementById("form_add_user").style.visibility = "hidden";
            }
            if (type == "course") {
                var to_lock = document.getElementById('admin-tools');
                to_lock.setAttribute("disabled", false);
                document.getElementById("overlay").style.visibility = "hidden";
                document.getElementById("openModal").style.visibility = "hidden"
                document.getElementById("form_add_course").style.visibility = "hidden";
            }
            if (type == "req") {
                var to_lock = document.getElementById('admin-tools');
                to_lock.setAttribute("disabled", false);
                document.getElementById("overlay").style.visibility = "hidden";
                document.getElementById("openModal").style.visibility = "hidden"
                document.getElementById("form_edit_req").style.visibility = "hidden";
            }
        }
        function checkForm(x) {
            var table = document.getElementById('tfhover');
            var colname = "numreq" + x;
            alert(colname);
            var cols = document.getElementById(colname);
            var reqNum = cols.value;
            //alert(ayy);
            /*
             var colslen = cols.length, i = -1;
             //if (colslen > 0){alert(cols);}
             while(++i < colslen){
             //alert (cols[i].innerHTML);
             if(i%2 != 0){
             var ayy = cols[i];
             alert(ayy);
             }
             else {alert(cols[i].value);}
             }
             */
        }
        //window.addEvent('load', function() {
        /*function openPicker() {
         new DatePicker('.demo_vista', { pickerClass: 'datepicker_vista' });
         new DatePicker('.demo_dashboard', { pickerClass: 'datepicker_dashboard' });
         new DatePicker('.demo_jqui', { pickerClass: 'datepicker_jqui', positionOffset: { x: 0, y: 5 } });
         new DatePicker('.demo', { positionOffset: { x: 0, y: 5 }});
         };*/
    </script>

    <script>
        $(function () {
            $("#datepicker").datepicker();
        });
    </script>
    <noscript>
        <link rel="stylesheet" href="css/skel.css"/>
        <link rel="stylesheet" href="css/datepicker.css"/>
        <link rel="stylesheet" href="css/style.css"/>
        <link rel="stylesheet" href="css/style-wide.css"/>
    </noscript>
    <!--[if lte IE 8]>
    <link rel="stylesheet" href="css/ie/v8.css"/><![endif]-->
</head>
<?php
if ($selected_dept_id == null) {
    echo "<body>";
} else {
    echo "<body onload=displayoverlay('req')>";
}
include 'nav.php';

?>

<div>
    </br>
    <h2>Administrator Tools</h2>
</div>
<div id='admin-tools' class='admin-tools'>

    <table class='admin-tools-menu'>
        <tr>
            <td>
                <div class='admin-tools-item' onclick='displayoverlay("user")'>ADD USER</div>
            </td>
            <td>
                <div class='admin-tools-item' onclick='displayoverlay("course")'>ADD COURSE</div>
            </td>
        </tr>
        <tr>
            <td>
                <div class='admin-tools-item' onclick='displayoverlay("req")'>EDIT DEPARTMENT REQS</div>
            </td>
            <td>
                <div class='admin-tools-item' onclick='displayoverlay("report")'>RUN REPORTS</div>
            </td>
        </tr>

    </table>

</div>

</div>

<div id="openModal" class="modalDialog">
    <!--ADD USER FORM (GETS CALLED BY DISPLAY FUNCTION)-->
    <div id='form_add_user'>
        <form method="POST">
            <a title="Close" onclick='closeOverlay("user")' class="close">X</a>

            <h2>NEW USER</h2>
            <input type='text' name='new-user-first' placeholder='First Name'></input></br>
            <input type='text' name='new-user-last' placeholder='Last Name'></input></br>
            <input type='text' name='new-user-netid' placeholder='NetID'></input></br>
            <input type='password' name='new-user-password' placeholder='Temporary Password'></input></br>
            <!--<div class='register_button' style="width: 45%; max-width:45%;display:inline-block; float:left; text-align: center" method='post' name='new-user-save'>Save</div>
            <div class='register_button' style="width: 45%;max-width:45%; display:inline-block; float:right; text-align: center" name='new-user-save-multiple'>Save and Add Another</div>	-->
            <button type="submit" class="register_button" name='user-save-single' value="user-save-single">SAVE</button>
        </form>
    </div>

    <div id='form_add_course'>
        <form method="POST">
            <a title="Close" onclick='closeOverlay("course")' class="close">X</a>

            <h2>NEW COURSE</h2>
            <select name='new-course-type' id='crstype' onchange='changeType()'
                    style='border: solid 1px #e0e0e0; border-radius:5px; display:inline;outline:0;padding:.75em; width: 100%'>
                <option selected='selected' disabled='disabled'>Select Course Type</option>
                <?php
                while ($row = mysql_fetch_assoc($course_types)) {
                    if ($selected_course_id == $row['type_id']) {
                        echo "<option value='" . $row['type_id'] . "' selected>" . $row['type_name'] . "</option>";
                    } else {
                        echo "<option value='" . $row['type_id'] . "'>" . $row['type_name'] . "</option>";
                    }
                }
                ?>
            </select>

            <p></p>

            <?php
            if ($selected_course_id != 3 && $selected_course_id != 4)echo "<input type='text' name='new-course-name' placeholder='Course Name'></input><p></p>";
            if ($selected_course_id == 3 || $selected_course_id == 4) {
                echo "<input name='cnetid' type='text' id='netid' placeholder='NetID'/>";
            }
            ?>
            <p></p>
            <input name='new-course-date2' type='text' id='datepicker' placeholder='Course Date'/>

            <p></p>
            <button type="submit" class="register_button" name='course-save-single' onclick = "closeOverlay('course')" value="course-save-single">SAVE
            </button>
        </form>
    </div>

    <div id='form_edit_req'>
        <form method="POST">
            <a title="Close" onclick='closeOverlay("req")' class="close">X</a>

            <h2>EDIT REQUIREMENTS</h2>
            <select id="dept-type" name='dept-type'
                    style='border: solid 1px #e0e0e0; border-radius:5px; display:inline;outline:0;padding:.75em; width: 100%'
                    onchange="changeDepartment()">
                <option selected='selected' disabled='disabled'>Select Department</option>
                <?php
                while ($row = mysql_fetch_assoc($departments)) {
                    if ($selected_dept_id == $row['dept_id']) {
                        echo "<option value='" . $row['dept_id'] . "' selected>" . $row['dept_name'] . "</option>";
                    } else {
                        echo "<option value='" . $row['dept_id'] . "'>" . $row['dept_name'] . "</option>";
                    }
                }
                echo "</select></br></br>";
                $select_dept_row = "SELECT * FROM rcr_db.rcr_department WHERE dept_id = '" . $selected_dept_id . "'";
                $dept_info = mysql_query($select_dept_row);
                $dept_row = mysql_fetch_assoc($dept_info);
                $does_inherit = $dept_row['inherits_reqs'];
                $parent_id = $dept_row['parent_id'];
                $select_parent = "SELECT * FROM rcr_db.rcr_department WHERE dept_id = '" . $parent_id . "'";
                $parent_info = mysql_query($select_parent);
                $parent_row = mysql_fetch_assoc($parent_info);
                $parent_name = $parent_row['dept_name'];
                $parent_dept_id = $parent_row['dept_id'];
                if ($does_inherit == '1') {
                    echo "<p>Inherit Requirements <input type='checkbox' name='dept-inherit' placeholder='Inherit Requirements' value='1' checked></input></p>";
                    echo "<p style='color:gray'>from '" . $parent_name . "'</p>";
                } else {
                    echo "<p>Inherit Requirements <input type='checkbox' name='dept-inherit' placeholder='Inherit Requirements' value='1'></input></p>";
                }
                ?>
                <table id="tfhover" class="tftable" border="1" align="center">
                    <tr>
                        <th style="font-weight:bold">Type</th>
                        <th style="font-weight:bold">Number Required</th>
                    </tr>
                    <?php
                    if ($does_inherit == '0') {
                        $myreqs = mysql_query("SELECT * FROM rcr_db.rcr_department_requirements
			INNER JOIN rcr_db.rcr_course_type_lookup 
			ON rcr_db.rcr_department_requirements.type_id=rcr_db.rcr_course_type_lookup.type_id 
			WHERE dept_id = '" . $selected_dept_id . "'
			ORDER BY rcr_db.rcr_course_type_lookup.type_id");

                        while ($row = mysql_fetch_assoc($myreqs)) {
                            echo "<tr><td>" . $row['type_name'] . "</td>";
                            echo "<td><input type = 'text' name = 'numreq[" . $row['type_id'] . "]' placeholder = " . $row['num_req'] . "></td>";
                            echo "</tr>";

                        }
                    } else {
                        $myreqs = mysql_query("SELECT * FROM rcr_db.rcr_department_requirements
			INNER JOIN rcr_db.rcr_course_type_lookup
			ON rcr_db.rcr_department_requirements.type_id=rcr_db.rcr_course_type_lookup.type_id 
			WHERE dept_id = '" . $parent_id . "'
			ORDER BY rcr_db.rcr_course_type_lookup.type_id");

                        while ($row = mysql_fetch_assoc($myreqs)) {
                            echo "<tr><td>" . $row['type_name'] . "</td>";
                            echo "<td><input type = 'text' name = 'numreq[" . $row['type_id'] . "]' disabled = 'disabled' placeholder = " . $row['num_req'] . "></td>";
                            echo "</tr>";
                        }
                    }
                    ?>

                </table>
                <p></p>

                <select id="add-type-req" name="add-type-req"
                        style="border: solid 1px #e0e0e0; border-radius:5px; display:inline;outline:0;padding:.75em; width:35%; margin-left:10%">
                    <?php
                    $dept_course_types = mysql_query("SELECT req_id, dept_id, cr.type_id, num_req, type_name
		FROM rcr_db.rcr_course_type_lookup as cr 
		LEFT JOIN (SELECT * FROM rcr_db.rcr_department_requirements WHERE dept_id = '" . $selected_dept_id . "') rq
		ON cr.type_id = rq.type_id WHERE dept_id IS NULL 
		ORDER BY type_name;");
                    echo "<option selected = selected' disabled = 'disabled'>Add New Requirement</option>";
                    while ($row = mysql_fetch_assoc($dept_course_types)) {
                        echo "<option name='req_option' value='" . $row['type_id'] . "'>" . $row['type_name'] . "</option>";
                    }

                    ?>
                </select>
                <input type="text" name='newnumreq' id='newnumreq' placeholder="New Number Required"
                       style="width:35%; margin-left:10fg %; display:inline">

                <p></p>
                <button type="submit" class="register_button" name='req-add-single' value="req-add-single"
                        style='float:right; width:15%; height:50px;'>ADD
                </button>
                <button type="submit" class="register_button" name='req-save-single' value="req-save-single"
                        style='width:15%; height:50px;'>SAVE
                </button>
                <!--<form name='register_form' id='register_form' method='POST'><button type="submit" value="" style="opacity: 0; font-size:0.1px;" name = "invisButton" id="invisButton"></button></form>-->
        </form>
    </div>
</div>
<?php echo $changeReq; ?>
<div id="overlay" class='modalOverlay'>
</div>


</body>
</html>