<?php 
// This file is www.developphp.com curriculum material
// Written by Adam Khoury January 01, 2011
// http://www.youtube.com/view_play_list?p=442E340A42191003
session_start();
if (!isset($_SESSION["customer"])) {
    header("location: customer_login.php"); 
    exit();
}
// Be sure to check that this manager SESSION value is in fact in the database
$customerID = preg_replace('#[^0-9]#i', '', $_SESSION["id"]); // filter everything but numbers and letters
$customer = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION["customer"]); // filter everything but numbers and letters
$password = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION["password"]); // filter everything but numbers and letters
// Run mySQL query to be sure that this person is an admin and that their password session var equals the database information
// Connect to the MySQL database  
include "../storescripts/connect_to_mysql.php"; 
$sql = mysql_query("SELECT * FROM customer WHERE id='$customerID' AND username='$customer' AND password='$password' LIMIT 1"); // query the person
// ------- MAKE SURE PERSON EXISTS IN DATABASE ---------
$existCount = mysql_num_rows($sql); // count the row nums
if ($existCount == 0) { // evaluate the count
	 echo "Your login session data is not on record in the database.";
     exit();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>Store Home Page</title>
      <!--<link rel="stylesheet" href="style/style.css" type="text/css" media="screen" />-->
      <!-- Latest compiled and minified CSS -->
      <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">

      <!-- Optional theme -->
      <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">
  </head>
  <body>
    <header class="navbar-inverse" role="banner"> 
      <div align="center" id="mainWrapper" class="container">
        <?php include_once("template_header6.php");?>
      <div id="pageContent">
    </header>

    <table width="100%" border="0" cellspacing="100" cellpadding="120" >
      <tr>
        <td width="32%" valign="top" align="center"><h3><img src="../style/logo.png" alt="Logo" width="252" height="36" border="0" /></h3>
           <form action='./result.php' method='get'>
              <input type='text' name='input' size='100' class="sarch-field" /> 
              <input type='submit' value='Search' class="seach-button  ">
           </form>
        </td>
      </tr>
    </table>

    </div>
      <?php include_once("template_footer6.php");?>
    </div>

    <!-- Latest compiled and minified JavaScript -->
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../js/bootstrap.min.js"></script>
  </body>
</html>