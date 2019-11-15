<?php
//uses function in src/retreive/products/allproducts/allproductsascustomer.php
include_once 'navbar.php';
include_once '../src/retreive/product/allproducts/allproductsascustomer.php';
?>
<head>
    <link rel ="stylesheet" href="../assets/css/navbarstyle.css">
    <link rel="stylesheet" href="../assets/css/productcardstyle.css">
</head>





<ul class="listing"> 
       <?php
         $Players = AllProductsAsCustomer::queryDatabase();
         foreach ($Players as $player) 
            {
            echo '<li class="li" id="'.$player[0].'">';
            echo '   <img class="img" src="'.$player[5].'"><img>';
            echo '    <h2>'.$player[1].'</h2>';
            echo '    <div class="body"><p>'.$player[2].'</p></div>';
            echo '    <div class="btn">Call to action!</div>';
            echo '</li>';
            }
       ?>
</ul>


