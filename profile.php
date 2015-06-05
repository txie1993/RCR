<!DOCTYPE HTML>
<?php

$current_user_record = "";

include 'login_function.php';
/*for security purposes, the servername, username, and password have not been included.*/

$conn = mysql_connect($servername, $username, $password, 'rcr_db');
if ($conn->connect_error) {
    die('CONNECTION FAILED');
}
ContinueSession("../profile.php?");

$netid = $_GET['u'];
$role = checkRoles($netid);

if (checkRoles($netid) == 1) {
    if ($_GET['a_uid']) {
        $netid = $_GET['a_uid'];
    }
}
if ($netid != null) {
    $qstring = "SELECT * FROM rcr_db.rcr_person WHERE net_id='" . $netid . "'";
    $current_user_record = mysql_query($qstring);
    while ($current_record = mysql_fetch_assoc($current_user_record)) {
        $fname = $current_record["f_name"];
        $lname = $current_record["l_name"];
        $net_id = $current_record["net_id"];
        $n_number = $current_record["n_number"];
        $email = $current_record["email"];
        $phone = $current_record["phone"];
        $dept_code = $current_record["dept_id"];
        $level_id = $current_record["level_id"];
    }
}
if (isset($_POST['profile-update'])) {
    $update_first = $_REQUEST['first'];
    $update_last = $_REQUEST['last'];
    $update_email = $_REQUEST['email'];
    $update_phone = $_REQUEST['phone'];
    $update_dept = $_REQUEST['SCHOOL'];
    $update_level = $_REQUEST['level'];
    $update_n_number = $_REQUEST['n_number'];
    $update_grant = $_REQUEST['GRANT'];

    $query = mysql_query("UPDATE rcr_db.rcr_person SET rcr_person.phone='" . $update_phone . "', rcr_person.level_id='" . $update_level . "', rcr_person.n_number = '" . $update_n_number . "', rcr_person.email = '" . $update_email . "', rcr_person.f_name='" . $update_first . "', rcr_person.l_name='" . $update_last . "', rcr_person.dept_id='" . $update_dept . "' WHERE rcr_person.net_id='" . $netid . "'");
    $additionalInfo = "&a_uid=" . $netid;
    $qstringarray = explode('&', $_SERVER['QUERY_STRING']);
    $loginQuery = $qstringarray[0] . "&" . $qstringarray[1];
    ContinueSessionWParams("../profile.php?", $loginQuery, $additionalInfo);
}
if (isset($_POST['grant-save-single'])) {
    $gnum = $_POST['newgrant'];
    $sponsor = $_POST['sponsor'];
    $checker = mysql_query("SELECT * FROM rcr_db.rcr_grants WHERE grant_number = '" . $gnum . "'");
    $num_check = mysql_num_rows($checker);
    if ($num_check == 0) $new_query = mysql_query("INSERT INTO rcr_db.rcr_grants(grant_number, sponsor) VALUES ('" . $gnum . "', '" . $sponsor . "')");
    $checker2 = mysql_query("SELECT * FROM rcr_db.rcr_associated_grants WHERE grant_number = '" . $gnum . "' AND net_id = '" . $netid . "'");
    $num_check2 = mysql_num_rows($checker2);
    echo $num_check2;
    if ($num_check2 == 0) $add_query = mysql_query("INSERT INTO rcr_db.rcr_associated_grants (grant_number, net_id) VALUES ('" . $gnum . "', '" . $netid . "')");
}
if (isset($_POST['invisButton'])) {
    $grantnum = $_POST['invisButton'];
    $remove_string = "DELETE FROM rcr_db.rcr_associated_grants WHERE grant_number = '".$grantnum."' AND net_id = '" . $netid . "'";
    $queryResult = mysql_query($remove_string);
}
?>
<html>
<head>
    <title>Profile</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta name="description" content=""/>
    <meta name="keywords" content=""/>
    <!--[if lte IE 8]>
    <script src="css/ie/html5shiv.js"></script><![endif]-->
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery.dropotron.min.js"></script>
    <script src="js/skel.min.js"></script>
    <script src="js/skel-layers.min.js"></script>
    <script src="js/init.js"></script>
    <script type="text/javascript">
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
		function searchKeyPress(e)
		{
        // look for window.event in case event isn't passed in
			e = e || window.event;
			if (e.keyCode == 13)
			{
				searchUser();
			}
		}
        function displayoverlay() {
            
                document.getElementById("overlay").style.visibility = "visible";
                document.getElementById("openModal").style.visibility = "visible";
                document.getElementById("form_add_grant").style.visibility = "visible";
            

        }
        function closeOverlay() {
                document.getElementById("overlay").style.visibility = "hidden";
                document.getElementById("openModal").style.visibility = "hidden";
                document.getElementById("form_add_grant").style.visibility = "hidden";
        }
        function pressinvis(x) {            
            var b = document.getElementById("invisButton");
            b.value = x;
            document.getElementById('invisButton').click();
        }

    </script>
    <?php
    $scriptStringEnable = "<script>
							function enableFields() {
								var active = 'images/active_icon.png';
								var settings = 'images/settings_icon.png';
								
								if(document.getElementById('first').disabled == true)
								{
									document.getElementById('editRecordButton').src = 'http://i.imgur.com/7QVJzz2.png';
									document.getElementById('first').disabled = false;
									document.getElementById('last').disabled = false;
									document.getElementById('net_id').disabled = false;
									document.getElementById('email').disabled = false;
									document.getElementById('phone').disabled = false;
									document.getElementById('n_number').disabled = false;
									document.getElementById('phone').disabled = false;
									document.getElementById('school_dropdown').disabled = false;
									document.getElementById('level_dropdown').disabled = false;
									document.getElementById('grant_dropdown').disabled = false;
									document.getElementById('grant_button').disabled = false;
									
								}
						
								else{
									document.getElementById('editRecordButton').src = settings;
									var r = confirm('Do you want to save these changes?');
										if (r == true) {
											document.getElementById('profile-update').click();
										} else {
											location.reload();
										}
									document.getElementById('first').disabled = true;
									document.getElementById('last').disabled = true;
									document.getElementById('net_id').disabled = true;
									document.getElementById('email').disabled = true;
									document.getElementById('phone').disabled = true;
									document.getElementById('n_number').disabled = true;
									document.getElementById('phone').disabled = true;
									document.getElementById('school_dropdown').disabled = true;
									document.getElementById('level_dropdown').disabled = true;
									document.getElementById('grant_dropdown').disabled = true;
									document.getElementById('grant_button').disabled = true;
								}
						}
						</script>";
    if ($_GET['a_uid']) {
        $netid = $_GET['a_uid'];
        $currentUser = $_GET['u'];
        if (canEditUser($currentUser, $netid)) {
            echo $scriptStringEnable;
        }
    } else {
        echo $scriptStringEnable;
    }
    ?>
    <noscript>
        <link rel="stylesheet" href="css/custom_styles.css"/>
        <link rel="stylesheet" href="css/skel.css"/>
        <link rel="stylesheet" href="css/style.css"/>
        <link rel="stylesheet" href="css/style-wide.css"/>
    </noscript>
    <!--[if lte IE 8]>
    <link rel="stylesheet" href="css/ie/v8.css"/><![endif]-->
</head>
<body>

<?php include 'nav.php'; ?>

<div>
    <div>
        </br>
        <?php include 'adminbar.php'; ?>

        </br>
        <h2 style='width: 50%; margin-left:25%;float: left'>My Profile</h2><img class='settingButton'
                                                                                id='editRecordButton'
                                                                                src='images/settings_icon.png'
                                                                                onclick='enableFields()'></img>
    </div>
    <form method='POST' name='PROFILE' id='PROFILE'>
        <div class="row 50%">
            <div style="float:left; margin-left:10%; width:40%">
                <input disabled name="first" type="text" id="first" placeholder="First: John"
                       title="First Name" <?php echo "value='" . $fname . "'"; ?>/>
            </div>
            <div style="float:right;margin-right:10%; width:40%">
                <input disabled name="last" type="text" id="last"
                       placeholder="Last: Smith" <?php echo "value='" . $lname . "'"; ?>/>
            </div>
            <div style="float:left; margin-left:10%; width:40%">
                <input disabled name="net_id" type="text" id="net_id" placeholder="NetID"
                       title="net_id" <?php echo "value='" . $net_id . "'"; ?>/>
            </div>
            <div style="float:right;margin-right:10%; width:40%">
                <input disabled name="n_number" type="text" id="n_number"
                       placeholder="N123456789" <?php echo "value='" . $n_number . "'"; ?>/>
            </div>
            <div class="6u 12u(3)" style="float:left; margin-left:10%; width:40%">
                <input disabled name="email" type="email" id="email"
                       placeholder="Email: js123@nyu.edu" <?php echo "value='" . $email . "'"; ?>/>
            </div>
            <div class="6u 12u(3)" style="float:right;margin-right:10%; width:40%">
                <input disabled name="phone" type="text" id="phone"
                       placeholder="Phone: 18001234567" <?php echo "value='" . $phone . "'"; ?>/>
            </div>
            <input type='submit' style='display:none' name='profile-update' id='profile-update'></input>
        </div>


        <div class="row 50%">
            <div style="float:left; margin-left:10%; width:40%">
                <select disabled name='SCHOOL' id='school_dropdown'
                        style='border: solid 1px #e0e0e0; border-radius:5px; display:block;outline:0;padding:.75em;width:100%;'>
                    <?php
                    $depts = mysql_query('SELECT * FROM rcr_db.rcr_department ORDER BY dept_name', $conn) or die(mysql_error());
                    if (mysql_num_rows($depts) <= 0) {
                        echo "<option>ERROR</option>";
                    }
                    while ($row = mysql_fetch_assoc($depts)) {
                        if ($row['dept_id'] == $dept_code) {
                            echo "<option value='" . $row['dept_id'] . "' selected>" . $row['dept_name'] . "</option>";
                        } else {
                            echo "<option value='" . $row['dept_id'] . "'>" . $row['dept_name'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div style="float:right;margin-right:10%; width:40%">
                <select disabled name="level" id='level_dropdown'
                        style='border: solid 1px #e0e0e0; border-radius:5px; display:block;outline:0;padding:.75em;width:100%;'>
                    <?php
                    $levels = mysql_query('SELECT * from rcr_db.rcr_level_lookup ORDER BY level', $conn) or die(mysql_error());
                    if (mysql_num_rows($levels) == 0) {
                        echo "<option>ERROR</option>";
                    } else {
                        while ($row = mysql_fetch_assoc($levels)) {
                            if ($row['level_id'] == $level_id) {
                                echo "<option value='" . $row['level_id'] . "' selected>" . $row['level'] . "</option>";
                            } else {
                                echo "<option value='" . $row['level_id'] . "'>" . $row['level'] . "</option>";
                            }
                        }
                    }
                    ?>
                </select>
            </div>
        </div>

</div>
</br>
<div style="float:left; margin-left:5%; width:49%; display:block">
    <table id="tfhover" class="tftable" border="1" align="left">
        <tr>
            <th style="font-weight:bold">Associated Grants</th>
			<?php
			if (canEditUser($_GET['u'], $_GET['a_uid'])) {
                echo "<th style='font-weight:bold'>Remove</th>";
            }
			?>
        </tr>
        <?php
        $grant_types = mysql_query("SELECT rcr_grants.grant_number, sponsor FROM rcr_db.rcr_grants
			INNER JOIN rcr_db.rcr_associated_grants ON rcr_db.rcr_grants.grant_number = rcr_db.rcr_associated_grants.grant_number
			WHERE rcr_associated_grants.net_id = '" . $netid . "'
			ORDER BY rcr_db.rcr_grants.grant_number;");
        while ($row = mysql_fetch_assoc($grant_types)) {
            echo "<tr><td>" . $row['grant_number'] . " (Sponsored by " . $row['sponsor'] . ")</td>";
			if (canEditUser($_GET['u'], $_GET['a_uid'])) {
                echo "<td><div class='button' style='text-align: center; margin-left:40%; font-size:100%; line-height:20px;' value='" . $row['grant_number'] . "' onclick='pressinvis(\"" . $row['grant_number'] . "\")'><em class='icon minor fa-times'></em></div></td>";
            }
            echo "</tr>";
        }
        ?>
    </table>


</div>
<div style="float:right; margin-left:5%; width:40%; display:inline">
    <?php
	if (canEditUser($_GET['u'], $_GET['a_uid'])) { 
	echo "<a class='button alt' name='grant_button' id='grant_button' onclick='displayoverlay()'
		  style='display:block; width:80%; margin-right: 10%; margin-left: 10%'>Add Grant</a>";
	}
	   ?>
</div>
<div style="float:left; margin-left:5%; width:80%; display:block">
    <?php
    $certhist = mysql_query("SELECT * FROM rcr_db.rcr_certification_history INNER JOIN rcr_db.rcr_department ON rcr_department.dept_id = rcr_certification_history.dept_id WHERE net_id = '". $netid ."' ORDER BY cert_id;");
    while ($row = mysql_fetch_assoc($certhist)) {
        echo "<p style = 'margin-left:6%; color:green;'>Received certification for '" . $row['dept_name'] . "'</p>";
    }
    ?>
</div>
</form>
<br>
<br>

<p></p>


</body>
<div id="openModal" class="modalDialog">
    <!--ADD USER FORM (GETS CALLED BY DISPLAY FUNCTION)-->
    <div id='form_add_grant'>
        <form method="POST">
            <a title="Close" onclick='closeOverlay("grant")' class="close">X</a>

            <h2>Add Grant</h2>

            <input type='text' name='newgrant' id='newgrant' placeholder='Grant Number'
                   style='border: solid 1px #e0e0e0; border-radius:5px; display:inline ;outline:0;padding:.75em;width:75%;margin-left:10%'>
            </br>
            <input type='text' name='sponsor' id='sponsor' placeholder='Sponsor'
                   style='border: solid 1px #e0e0e0; border-radius:5px; display:inline ;outline:0;padding:.75em;width:75%;margin-left:10%'>
            </br>
            <button type="submit" class="register_button" name='grant-save-single' value="grant-save-single"
                    style="margin-left:45%">SAVE
            </button>
        </form>
    </div>
</div>
<div id="overlay" class='modalOverlay'>
</div>
<form name='register_form' id='register_form' method='POST'>
    <button type="submit" value="" style="opacity: 0; font-size:0.1px;" name="invisButton" id="invisButton"></button>
</form>
</html>