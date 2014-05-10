<?php 
// This file is www.developphp.com curriculum material
// Written by Adam Khoury January 01, 2011
// http://www.youtube.com/view_play_list?p=442E340A42191003
session_start();
if (!isset($_SESSION["manager"])) {
    header("location: admin_login.php"); 
    exit();
}
// Be sure to check that this manager SESSION value is in fact in the database
$managerID = preg_replace('#[^0-9]#i', '', $_SESSION["id"]); // filter everything but numbers and letters
$manager = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION["manager"]); // filter everything but numbers and letters
$password = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION["password"]); // filter everything but numbers and letters
// Run mySQL query to be sure that this person is an admin and that their password session var equals the database information
// Connect to the MySQL database  
include "../storescripts/connect_to_mysql.php"; 
$sql = mysql_query("SELECT * FROM admin WHERE id='$managerID' AND username='$manager' AND password='$password' LIMIT 1"); // query the person
// ------- MAKE SURE PERSON EXISTS IN DATABASE ---------
$existCount = mysql_num_rows($sql); // count the row nums
if ($existCount == 0) { // evaluate the count
	 echo "Your login session data is not on record in the database.";
     exit();
}
?>
<?php 
// Script Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>
<?php 
// Parse the form data and add inventory item to the system
if (isset($_POST['username'])) {
	
	$pid = mysql_real_escape_string($_POST['thisID']);
    $username = mysql_real_escape_string($_POST['username']);
	$password = mysql_real_escape_string($_POST['password']);
	$name = mysql_real_escape_string($_POST['name']);
	$address = mysql_real_escape_string($_POST['address']);
	$telephone = mysql_real_escape_string($_POST['telephone']);
	$email = mysql_real_escape_string($_POST['email']);
	// See if that product name is an identical match to another product in the system
	$sql = mysql_query("UPDATE vendor SET username='$username', password='$password', name='$name', address='$address', telephone='$telephone' ,email='$email' WHERE id='$pid'");
	if ($_FILES['fileField']['tmp_name'] != "") {
	    // Place image in the folder 
	    $newname = "$pid.jpg";
	    move_uploaded_file($_FILES['fileField']['tmp_name'], "../vendor_images/$newname");
	}
	header("location: vendor_list.php"); 
    exit();
}
?>
<?php 
// Gather this product's full information for inserting automatically into the edit form below on page
if (isset($_GET['pid'])) {
	$targetID = $_GET['pid'];
    $sql = mysql_query("SELECT * FROM vendor WHERE id='$targetID' LIMIT 1");
    $productCount = mysql_num_rows($sql); // count the output amount
    if ($productCount > 0) {
	    while($row = mysql_fetch_array($sql)){ 
             
			 $username = $row["username"];
			 $password = $row["password"];
			 $name = $row["name"];
			 $address = $row["address"];
			 $telephone = $row["telephone"];
			 $email = $row["email"];
			 $last_log_date = strftime("%b %d, %Y", strtotime($row["last_log_date"]));
        }
    } else {
	    echo "Sorry dude that crap dont exist.";
		exit();
    }
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Vendor List</title>
      <!--<link rel="stylesheet" href="style/style.css" type="text/css" media="screen" />-->
      <!-- Latest compiled and minified CSS -->
      <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">

      <!-- Optional theme -->
      <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">
</head>

<body>
    <header class="navbar-inverse" role="banner"> 
      <div align="center" id="mainWrapper" class="container">
        <?php include_once("../template_header4.php");?>
      <div id="pageContent">
    </header>

  <div id="pageContent"><br />
    <div align="right" style="margin-right:32px;"><a href="inventory_list.php#inventoryForm">+ Add New Inventory Item</a></div>
<div align="left" style="margin-left:24px;">
      <h2>Vendor list</h2>

    </div>
    <hr />
    <a name="inventoryForm" id="inventoryForm"></a>
    <h3>
    &darr; Edit Vendor Form &darr;
    </h3>
    <form action="vendor_edit.php" enctype="multipart/form-data" name="myForm" id="myform" method="post">
    <table width="90%" border="0" cellspacing="0" cellpadding="6">
      <tr>
        <td width="20%" align="right">Username</td>
        <td width="80%"><label>
          <input name="username" type="text" id="username" size="64" value="<?php echo $username; ?>" />
        </label></td>
      </tr>
      <tr>
        <td align="right">Password</td>
        <td><label>
          
          <input name="password" type="text" id="password" size="12" value="<?php echo $password; ?>" />
        </label></td>
      </tr>
      <tr>
        <td align="right">Name</td>
        <td><label>
          <input name="name" type="text" id="name" size="12" value="<?php echo $name; ?>" />
        </label></td>
      </tr>
      <tr>
        <td align="right">Address</td>
        <td><label>
          <input name="address" type="text" id="address" size="12" value="<?php echo $address; ?>" />
        </label></td>
      </tr>
      <tr>
        <td align="right">Telephone</td>
        <td><label>
          <input name="telephone" type="text" id="telephone" size="12" value="<?php echo $telephone; ?>" />
        </label></td>
      </tr>
	        <tr>
        <td align="right">Email</td>
        <td><label>
          <input name="email" type="text" id="email" size="12" value="<?php echo $email; ?>" />
        </label></td>
      </tr>
      <tr>
        <td align="right">Company Logo</td>
        <td><label>
          <input type="file" name="fileField" id="fileField" />
        </label></td>
      </tr>      
      <tr>
        <td>&nbsp;</td>
        <td><label>
          <input name="thisID" type="hidden" value="<?php echo $targetID; ?>" />
          <input type="submit" name="button" id="button" value="Make Changes" />
		  <a href="vendor_list.php"><input type="button" name="button" id="button" value="Back"/></a>
        </label></td>
      </tr>
    </table>
    </form>
    <br />
  <br />
  </div>
  <?php include_once("../template_footer4.php");?>
</div>

    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../js/bootstrap.min.js"></script>
</body>
</html>