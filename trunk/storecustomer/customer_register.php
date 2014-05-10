<?php 
include "../storescripts/connect_to_mysql.php"; 
// Parse the form data and add inventory item to the system
if (isset($_POST['username'])) {
	
  $username = mysql_real_escape_string($_POST['username']);
	$password = mysql_real_escape_string($_POST['password']);
	$name = mysql_real_escape_string($_POST['name']);
	$address = mysql_real_escape_string($_POST['address']);
	$email = mysql_real_escape_string($_POST['email']);
	// See if that product name is an identical match to another product in the system
	$sql = mysql_query("SELECT id FROM customer WHERE username='$username' LIMIT 1");
	$customerMatch = mysql_num_rows($sql); // count the output amount
    if ($customerMatch > 0) {
		echo 'Sorry you tried to place a duplicate "Username" into the system, <a href="customer_register.php">click here</a>';
		exit();
	}
	// Add this product into the database now
	$sql = mysql_query("INSERT INTO customer (username, password, name, address, email,last_log_date) 
        VALUES('$username','$password','$name','$address', '$email',now())") or die (mysql_error());
     $pid = mysql_insert_id();
	// Place image in the folder 
	$newname = "$pid.jpg";
	move_uploaded_file( $_FILES['fileField']['tmp_name'], "../customer_images/$newname");
	header("location: index.php"); 
    exit();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Customer Registration</title>
      <!--<link rel="stylesheet" href="style/style.css" type="text/css" media="screen" />-->
      <!-- Latest compiled and minified CSS -->
      <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">

      <!-- Optional theme -->
      <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">
</head>

<body>
    <header class="navbar-inverse" role="banner"> 
      <div align="center" id="mainWrapper" class="container">
        <?php include_once("template_header5.php");?>
      <div id="pageContent">
    </header>

  <div id="pageContent"><br />
    <h3>
    &darr; Register here &darr;
    </h3>
    <form action="customer_register.php" enctype="multipart/form-data" name="myForm" id="myform" method="post">
    <table width="90%" border="0" cellspacing="0" cellpadding="6">
      <tr>
        <td width="20%" align="right">Username</td>
        <td width="80%"><label>
          <input name="username" type="text" id="username" size="12" />
        </label></td>
      </tr>
      <tr>
        <td align="right">Password</td>
        <td><label>
          
          <input name="password" type="password" id="password" size="12" />
        </label></td>
      </tr>
      <tr>
        <td align="right">Name</td>
        <td><label>
          <input name="name" type="text" id="name" size="50" />

        </label></td>
      </tr>
      <tr>
        <td align="right">Address</td>
        <td><label>
          <input name="address" type="text" id="address" size="50" />

        </label></td>
      </tr>
      <tr>
        <td align="right">Email</td>
        <td><label>
          <input name="email" type="text" id="email" size="20" />
        </label></td>
      </tr>      
	     <tr>
        <td align="right">Profile Picture</td>
        <td><label>
          <input type="file" name="fileField" id="fileField" />
        </label></td>
      </tr>   
      <tr>
        <td>&nbsp;</td>
        <td><label>
          <input type="submit" name="button" id="button" value="Register Now" />
  		  <a href="index.php"><input type="button" name="button" id="button" value="Back"/></a>
        </label></td>
      </tr>
    </table>
    </form>
    <br />
  <br />
  </div>
  <?php include_once("template_footer5.php");?>
</div>

    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../js/bootstrap.min.js"></script>
</body>
</html>