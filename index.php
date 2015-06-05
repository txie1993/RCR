<!DOCTYPE HTML>
<?php 


include 'login_function.php';


ContinueSession("../index.php?");

$netid = $_GET['u'];

?>
<html>
<head>
<title>Responsible Conduct of Research Training</title>
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

<!-- Banner -->
<section id="banner"> </section>

<!-- Highlights -->
<section class="wrapper style1">
  <div class="container">
    <div class="row 100%">
      <section style="margin-left:17%">
        <div class="box highlight" ><em class="icon major fa-wrench"></em>
          <h3><a href="profile.php">My Profile</a></h3>
        </div>
      </section>
      <section style="margin-left:17%">
        <div class="box highlight"><em class="icon major fa-pencil"></em>
          <h3><a href="courses.php">My Courses</a></h3>
        </div>
      </section>
      <section style="margin-left:17%">
        <div class="box highlight"><em class="icon major fa-exclamation"></em>
          <h3><a href="courses.php">My Requirements</a></h3>
        </div>
      </section>
      </section>
    </div>
  </div>
</section>


  
</div>
</body>
</html>