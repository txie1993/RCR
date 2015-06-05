<?php  
 function curPageName() {
 return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
 }
 include 'login_function.php';
 
	$nav_netid = $_GET['u'];
 ?>
 <div id="header"> 
  
  <!-- Logo -->
  <h1><a href="index.php" id="logo">Responsible Conduct of Research Training </a></h1>
  
  <!-- Nav -->
  <nav id="nav">
    <ul>
      <li <?php if(curPageName() == "index.php") echo 'class="current"'?>><a href="index.php">Home</a></li>
      <li <?php if(curPageName() == "profile.php" || curPageName() == "register.php" ||curPageName() == "registrations.php" || curPageName() == "courses.php") echo 'class="current"'?>> <a href="">Dropdown</a>
        <ul>
          <li><a href="profile.php">My Profile</a></li>
          <li><a href="courses.php">My Courses</a></li>
          <li><a href="register.php">Register for a Course</a></li>
          <!--<li><a href="registrations.php">My Registrations</a></li>-->
        </ul>
      </li>
      <li <?php if(curPageName() == "about.php") echo 'class="current"'?>><a href="about.php">About RCR</a></li>
	 
	 <?php if(checkRoles($nav_netid) == 1)
	 {
		 if(curPageName() == 'admin.php')
		 {
		 $isCurrent = 'class="current"';
		 }
	  echo "<li ".$isCurrent."><a href='admin.php'>Admin Tools</a></li>";
     }
	  ?>
	  <li><a href="login.php">Log In</a></li>
    </ul>
  </nav>
</div> 
