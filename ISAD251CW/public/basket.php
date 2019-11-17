<?php
include_once 'navbar.php';
if(!isset($_SESSION)){
   session_start();
   echo session_status();
   if(!isset($_SESSION['CART']))
   {
      $_SESSION['CART'] = array();
   }
}
?>
<head>
    <title>Basket</title>
    <link rel="stylesheet" href="../assets/css/productcardstyle.css">
</head>
<?php

var_dump($_SESSION);

?>