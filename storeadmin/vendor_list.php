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
// Delete Item Question to Admin, and Delete Product if they choose
if (isset($_GET['deleteid'])) {
	echo 'Do you really want to delete vendor with ID of ' . $_GET['deleteid'] . '? <a href="vendor_list.php?yesdelete=' . $_GET['deleteid'] . '">Yes</a> | <a href="vendor_list.php">No</a>';
	exit();
}
if (isset($_GET['yesdelete'])) {
	// remove item from system and delete its picture
	// delete from database
	$id_to_delete = $_GET['yesdelete'];
	$sql = mysql_query("DELETE FROM vendor WHERE id='$id_to_delete' LIMIT 1") or die (mysql_error());
	// unlink the image from server
	// Remove The Pic -------------------------------------------
    $pictodelete = ("../vendor_images/$id_to_delete.jpg");
    if (file_exists($pictodelete)) {
       		    unlink($pictodelete);
    }
	header("location: vendor_list.php"); 
    exit();
}
?>
<?php 
// Parse the form data and add inventory item to the system
if (isset($_POST['username'])) {
	
    $username = mysql_real_escape_string($_POST['username']);
	$password = mysql_real_escape_string($_POST['password']);
	$name = mysql_real_escape_string($_POST['name']);
	$address = mysql_real_escape_string($_POST['address']);
	$telephone = mysql_real_escape_string($_POST['telephone']);
	$email = mysql_real_escape_string($_POST['email']);
	// See if that product name is an identical match to another product in the system
	$sql = mysql_query("SELECT id FROM vendor WHERE username='$username' LIMIT 1");
	$productMatch = mysql_num_rows($sql); // count the output amount
    if ($productMatch > 0) {
		echo 'Sorry you tried to place a duplicate "Username" into the system, <a href="vendor_list.php">click here</a>';
		exit();
	}
	// Add this product into the database now
	$sql = mysql_query("INSERT INTO vendor (username, password, name, address, telephone, email,last_log_date) 
        VALUES('$username','$password','$name','$address','$telephone','$email',now())") or die (mysql_error());
     $pid = mysql_insert_id();
	// Place image in the folder 
	$newname = "$pid.jpg";
	move_uploaded_file( $_FILES['fileField']['tmp_name'], "../vendor_images/$newname");
	header("location: vendor_list.php"); 
    exit();
}
?>
<?php 
// This block grabs the whole list for viewing
$vendor_list = "";
$sql = mysql_query("SELECT * FROM vendor");
$productCount = mysql_num_rows($sql); // count the output amount
if ($productCount > 0) {
	while($row = mysql_fetch_array($sql)){ 
             $id = $row["id"];
			 $username = $row["username"];
			 $password = $row["password"];
			 $vendor_list .= "Vendor ID: $id - <strong>$username</strong> - $password - &nbsp; &nbsp; &nbsp; <a href='vendor_edit.php?pid=$id'>edit</a> &bull; <a href='vendor_list.php?deleteid=$id'>delete</a><br />";
    }
} else {
	$vendor_list = "You have no vendor listed in your store yet";
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
    <div align="right" style="margin-right:32px;"><a href="vendor_list.php#inventoryForm">+ Add New Vendor</a></div>
<div align="left" style="margin-left:24px;">
      <h2>Vendor list</h2>
      <?php echo $vendor_list; ?>
    </div>
    <hr />
    <a name="inventoryForm" id="inventoryForm"></a>
    <h3>
    &darr; Add New Vendor Form &darr;
    </h3>
    <form action="vendor_list.php" enctype="multipart/form-data" name="myForm" id="myform" method="post">
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
          
          <input name="password" type="text" id="password" size="12" />
        </label></td>
      </tr>
      <tr>
        <td align="right">Company name</td>
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
        <td align="right">Telephone</td>
        <td><label>
           <input name="telephone" type="text" id="telephone" size="10" />
        </label></td>
      </tr>
      <tr>
        <td align="right">Email</td>
        <td><label>
          <input name="email" type="text" id="email" size="20" />
        </label></td>
      </tr>      
	     <tr>
        <td align="right">Company logo</td>
        <td><label>
          <input type="file" name="fileField" id="fileField" />
        </label></td>
      </tr>   
      <tr>
        <td>&nbsp;</td>
        <td><label>
          <input type="submit" name="button" id="button" value="Add This Vendor Now" />
  		  <a href="index.php"><input type="button" name="button" id="button" value="Back"/></a>
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