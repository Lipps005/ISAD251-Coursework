<?php
//uses function in src/retreive/products/allproducts/allproductsascustomer.php
include_once 'navbar.php';
include_once '../src/retreive/product/allproducts/allproductsascustomer.php';

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
    <link rel ="stylesheet" href="../assets/css/navbarstyle.css">
    <link rel="stylesheet" href="../assets/css/productcardstyle.css">
</head>





<ul class="listing"> 
       <?php
         $Players = AllProductsAsCustomer::queryDatabase();
         if($Players != NULL)
         {
            
           foreach ($Players as $player) 
            {
              if(array_key_exists($player[0], $_SESSION["CART"]))
            {
               $buttontext = "remove from basket";
            }
            else
            {
               $buttontext = "add to basket";
            }
            echo '<li class="li" ">';
            echo '   <img class="img" src="'.$player[5].'"><img>';
            echo '    <h2>'.$player[1].'</h2>';
            echo '    <div class="body"><p>'.$player[2].'</p></div>';
            echo '    <div class="btn" id="'.$player[0].'" onclick="addtobasket(this.id)">'.$buttontext.'</div>';
            echo '</li>';
            } 
            
         }
         else
         {
            echo "<p>Sorry, there was trouble connecting to the Database. Please try again later. </p>";
         }
         
       ?>
</ul>

<script>
function addtobasket($id)
{
   
   
   //new xmlhttprequest object
   var xmlhttp = new XMLHttpRequest();
   //function to execute on response
   xmlhttp.onreadystatechange = function() 
   {
      if (this.readyState == 4 && this.status == 200) 
      {
         
         //parse JSON 
         var myObj = JSON.parse(this.responseText);
         //set button style from JSON
         //set button text from JSON
         document.getElementById($id).innerHTML = myObj.text;
      }
   };

      
      
   //goes to src/update/basekt/addnewtobasket.php
   //alert("added" + $id);
   xmlhttp.open("GET","../src/update/basket/addnewtobasket.php?p=" + $id, true);
   xmlhttp.send();
}
</script>

