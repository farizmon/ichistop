<?php 
// This file is www.developphp.com curriculum material
// Written by Adam Khoury January 01, 2011
// http://www.youtube.com/view_play_list?p=442E340A42191003
session_start();
if (!isset($_SESSION["customer"])) {
    header("location: storecustomer/customer_login.php"); 
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
<?php 

// Connect to the MySQL database  
include "../storescripts/connect_to_mysql.php"; 
$dynamicList = "";
$status = "";
$sql = mysql_query("SELECT * FROM products ORDER BY date_added");
$productCount = mysql_num_rows($sql); // count the output amount
if ($productCount > 0) {
	while($row = mysql_fetch_array($sql)){ 
       $id = $row["id"];
			 $product_name = $row["product_name"];
			 $price = $row["price"];
       $quantity = $row["quantity"];
			 $date_added = strftime("%b %d, %Y", strtotime($row["date_added"]));
       $status = $quantity == 0 ? "Sold Out" : "Available";
			 $dynamicList .= '<table width="100%" border="0" cellspacing="1" cellpadding="6">
         <tr>
          <td width="17%" valign="top"><a href="product.php?id=' . $id . '"><img style="border:#666 0px solid;" src="../inventory_images/' . $id . '.jpg" alt="' . $product_name . '" width="77" height="102" border="1" /></a></td>
          <td width="83%" valign="top">       
            <a href="product.php?id=' . $id . '">' . $product_name . '</a><br />
            $' . $price . '<br />
            Quantity =' .$quantity . ' 
            <br /> Status = ' . $status . ' <br />
          </td>
        </tr>
      </table>';
    }
} else {
	$dynamicList = "We have no products listed in our store yet";
}
mysql_close();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Product List</title>
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

  <div id="pageContent">
   <table width="100%" border="0" cellspacing="0" cellpadding="10" >
  <tr>
    <td width="35%" valign="top"><h3>Latest Designer Fashions</h3>
      <p><?php echo $dynamicList; ?><br />
        </p>
      <p><br />
      </p></td>
		
     </td>
    </tr>
</table>

  </div>

  <div>
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