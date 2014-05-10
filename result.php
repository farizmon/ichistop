<?php 
// This file is www.developphp.com curriculum material
// Written by Adam Khoury January 01, 2011
// http://www.youtube.com/view_play_list?p=442E340A42191003
// Script Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>
<?php 
// Run a select query to get my letest 6 items
// Connect to the MySQL database  
include "storescripts/connect_to_mysql.php"; 
$dynamicList = "";
$sql = mysql_query("SELECT * FROM products ORDER BY date_added DESC LIMIT 6");
$productCount = mysql_num_rows($sql); // count the output amount
if ($productCount > 0) {
	while($row = mysql_fetch_array($sql)){ 
             $id = $row["id"];
			 $product_name = $row["product_name"];
			 $price = $row["price"];
			 $date_added = strftime("%b %d, %Y", strtotime($row["date_added"]));
			 $dynamicList .= '<table width="100%" border="0" cellspacing="0" cellpadding="6">
        <tr>
          <td width="17%" valign="top"><a href="product.php?id=' . $id . '"><img style="border:#666 1px solid;" src="inventory_images/' . $id . '.jpg" alt="' . $product_name . '" width="77" height="102" border="1" /></a></td>
          <td width="83%" valign="top">' . $product_name . '<br />
            $' . $price . '<br />
            <a href="product.php?id=' . $id . '">View Product Details</a></td>
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
        <?php include_once("template_header.php");?>
      <div id="pageContent">
    </header>
  <div id="pageContent">
  <table width="100%" border="0" cellspacing="0" cellpadding="10">
  <tr>
    <td width="32%" valign="top" align="left"><h3><img src="style/logo.png" alt="Logo" width="252" height="36" border="0" /></h3>
	<form action='result.php' method='get'>
		<input type='text' name='input' size='50' value='<?php echo $_GET['input']; ?>' class="sarch-field" /> 
		<input type='submit' value='Search' class="seach-button">
	</form>
	</br>
	
	<?php
	$i = 0;
		$input = $_GET['input'];//Note to self $input in the name of the search feild
		$terms = explode(" ", $input);
		$query = "SELECT * FROM products WHERE ";
		
		foreach ($terms as $each){
			
			$i++;
			if ($i == 1)
				$query .= "product_name LIKE '%$each%' ";
			else
				$query .= "OR product_name LIKE '%$each%' ";
		}
		
		include "storescripts/connect_to_mysql.php"; 
		
		$query = mysql_query($query) or die(mysql_error());;
		$numrows = mysql_num_rows($query);
		if ($numrows > 0){
			
			while ($row = mysql_fetch_assoc($query)){
				$id = $row['id'];
				$product_name = $row['product_name'];
				$price = $row['price'];
				$details = $row['details'];
				$category = $row['category'];
				$subcategory = $row['subcategory'];
				$date_added = $row['date_added'];
				$vendor = $row['vendor'];
				echo '<table width="100%" border="0" cellspacing="1" cellpadding="6">
         <tr>
          <td width="17%" valign="top"><a href="product.php?id=' . $id . '"><img style="border:#666 0px solid;" src="inventory_images/' . $id . '.jpg" alt="' . $product_name . '" width="77" height="102" border="1" /></a></td>
          <td width="83%" valign="top">       
            <a href="product.php?id=' . $id . '">' . $product_name . '</a><br />
            $' . $price . '<br />
          </td>
        </tr>
      </table>';

			}
			
		}
		else
			echo "No results found for \"<b>$input</b>\"";
		
		// disconnect
		mysql_close();
	?>
     
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