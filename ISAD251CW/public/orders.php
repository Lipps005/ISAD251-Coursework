<?php

include_once 'navbar.php';
if(!isset($_SESSION))
{
      session_start();
   $_SESSION['CART'] = array();
}

?>
<head>
    <title>Orders</title>
    <link rel="stylesheet" href="../assets/css/productcardstyle.css">
</head>



