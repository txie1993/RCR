<?php
$servername = /*for security purposes, the servername, username, and password have not been included.*/
			$conn = mysql_connect($servername,$username,$password);
			if($conn->connect_error)
			{
				die('CONNECTION FAILED');
			}

function ContinueSession($page)
{
	if($_GET['s_id'] == null)
			{
			$ref = $_SERVER['HTTP_REFERER'];
			$ref_array = preg_split("/[?]/",$ref);
			if($ref_array[1] == "" || $ref_array[1] == null)
			{
				header("Location: ../login.php");
			}
			else{
			header("Location:".$page.$ref_array[1]);	
			}
			}
	else{
		isLoggedIn();
	}
}

function ContinueSessionWParams($page,$currentQuery,$additionalInformation)
{
			header("Location:".$page.$currentQuery.$additionalInformation);
}
	
function isLoggedIn()
{
$session_id = $_GET['s_id'];

$currentip = $_SERVER['REMOTE_ADDR']; 
$user = $_GET['u'];
$session_status = session_check($user,$currentip,$session_id);
if($session_status == false)
{
	header("Location: http://128.122.209.95/login.php");
}

			
}
function session_check($user,$current_ip,$session_id)
{
	//first: split session_id into following pattern: [0] = randNum + char, [1] = first octet of ip...so on
	$session_id_array = preg_split("/[a-z]/",$session_id);
	//check for match of current ip to session_id
	$current_ip_array = explode(".",$current_ip);

	
	//FIX FIX FIX FIX FIX FIX
	
	for($i = 0; $i < 4; $i = $i+1)
	{
		if($current_ip_array[$i] != $session_id_array[$i + $i + 1])
			{
					return false;
			}
	}
	
	$after_ip_check = mysql_query("SELECT * FROM rcr_db.rcr_logins WHERE net_id='".$user."' AND session_id='".$session_id."';");
	//check to see if user and session are valid
	if(mysql_num_rows($after_ip_check) < 1)
	{
		return false;
	}
	
	return true; 
}

function checkRoles($netid)
{
	$result = mysql_query("SELECT * FROM rcr_db.rcr_logins WHERE net_id='".$netid."'");
	if($row = mysql_fetch_assoc($result))
	{
		return $row['group_id'];
	}
}
function canEditUser($currentUser, $user_record)	
{
	// Check if user is global admin, do anything
	if(checkRoles($currentUser) == 1)
	{
		return true;
	}
	
	
	//Check if user is dept admin
	//Needs to be built out
	
	//if(checkRoles() == 2)
	//{
		//verify department access
	//}
	
	else{
		return false;
	}
		
}
?>