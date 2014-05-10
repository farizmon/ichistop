<?php 

// Connect to the MySQL database  
include "storescripts/connect_to_mysql.php"; 
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
          <td width="17%" valign="top"><a href="product.php?id=' . $id . '"><img style="border:#666 0px solid;" src="inventory_images/' . $id . '.jpg" alt="' . $product_name . '" width="77" height="102" border="1" /></a></td>
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
        <?php include_once("template_header.php");?>
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
  <?php include_once("template_footer.php");?>
  </div>
  
      <!-- Latest compiled and minified JavaScript -->
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html> 