<?php
include_once 'navbar.php';
include_once '../src/retreive/product/fromproductid.php';
if(!isset($_SESSION)){
   session_start();
   if(!isset($_SESSION['CART']))
   {
      $_SESSION['CART'] = array();
   }
}
?>
<!DOCTYPE html>
<html>
    <head>
    <title>Basket</title>
    <link rel="stylesheet" href="../assets/css/productcardstyle.css">
    <link rel ="stylesheet" href="../assets/css/navbarstyle.css">
    <link rel ="stylesheet" href="../assets/css/shoppingcartstyle.css">
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">    <meta name="robots" content="noindex,follow" />
      <script>
    $( document ).ready(function()
  {
      var xmlhttp = new XMLHttpRequest();
   //function to execute on response
   xmlhttp.onreadystatechange = function() 
   {
      if (this.readyState == 4 && this.status == 200) 
      {
        var myObj = JSON.parse(this.responseText);
       if(myObj === 0)
        {
            document.getElementById("checkoutbuttons").style.display = "none";  
        }

      }
  };
        xmlhttp.open("GET","../src/retreive/basket/basketquantity.php", true);
        xmlhttp.send();
  });
  </script>
</head>


  <body>
  <div class="bgimg-1">
  <div class="shopping-cart">
      <!-- Title -->
      <div class="title">
        Shopping Bag
      </div>
      <?php
//check session cart size > 0 (has products in it)

if(sizeof($_SESSION['CART']) <= 0)
{
    echo "<p> Please add some items to your basket first!</p>";
}
else
{
    $sum = 0.0;

    $cartkeys = array_keys($_SESSION['CART']);
    foreach($cartkeys as $key)
    {
     $Player = fromproductid::queryDatabaseByID($key);
     if($Player != NULL)
     {
         foreach($Player as $pl)
         {
            echo '<div class="item" id = "item_'. $pl[0]. '">';
            echo '<div class="buttons">';
            echo '<span class="plus-btn"><button class="btn" id="'.$pl[0].'" onclick="increasequantity(this.id)"><i class="fa fa-plus"></i></button></span>';
         echo ' <span class="quantity" id = "quantityspan_'.$pl[0].'">'.$_SESSION['CART'][$pl[0]]["quantity"].'</span>';
            echo '<span class="minus-btn"><button class="btn" id="'.$pl[0].'" onclick="decreasequantity(this.id)"><i class="fa fa-minus"></i></button></span>';
            echo '</div>';

            echo '<div class="image">';
            echo '<img src="'.$pl[5].'" alt="'.$pl[1].'" title="'.$pl[1].'" />';
            echo '</div>';

            echo '<div class="description">';
            echo '<span>'.$pl[1].'</span>';
            echo '<span></span>';
            echo '<span>'.$pl[2].'</span>';
            echo '</div>';

        
         $x = $_SESSION['CART'][$key]["quantity"];
         $sum += $x* $pl[6];
            echo '<div class="total-price" id="itemprice_'.$pl[0].'">'. number_format($pl[6], 2).'</div>';
            echo '</div>';
         }    
     }
    }
    echo '<div>';
    echo '<br>TOTAL </br>';
echo '<div id = "total"><br class="total-price">'. number_format($sum, 2) . '</br></div>';
echo '</div>';
}

?>
      
  </div>
    <div class="checkoutbuttons" id="checkoutbuttons">  
    <?php 
    if(array_key_exists("CUSTOMERID", $_SESSION))
    {
       echo' <span> <button class="cqoutbutton" id="addtoxorderbutton" onclick="addtoexistingorder()">ADD TO EXISTING ORDER</button></span>';
    }
    ?>     
    <span><button class = "cqoutbutton" id="addtoneworderbutton" onclick="addtoneworder()">ADD TO NEW ORDER</button></span>
    </div>
      <span><br> *NOTE: Some quantities might be amended when you check out.</br></span>
      <span><br>  Stock of all products is limited </br></span>

  </body>
</html>      
 

<script>
    function addtoexistingorder()
    {
        var xmlhttp = new XMLHttpRequest();
   //function to execute on response
   xmlhttp.onreadystatechange = function() 
   {
       if (this.readyState == 4 && this.status == 200) 
        {
            
        //alert(this.responseText);
        window.location.href = "orders.php";
        }
    };
       xmlhttp.open("GET","../src/update/order/addtoexistingorder.php", true);
   xmlhttp.send();
    }
    
    function addtoneworder()
    {
        var xmlhttp = new XMLHttpRequest();
   //function to execute on response
   xmlhttp.onreadystatechange = function() 
   {
      if (this.readyState == 4 && this.status == 200) 
      {
         
         //parse JSON 
         //var myObj = JSON.parse(this.responseText);
         //console.log(myObj);
         //alert(this.responseText);
             window.location.href = "orders.php";

      }
  }; 
   //goes to src/update/basekt/addnewtobasket.php
   //alert("added" + $id);
   xmlhttp.open("GET","../src/create/new/neworder.php", true);
   xmlhttp.send();
    }
  
   function increasequantity($id)
   {
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
         //document.getElementById($id).innerHTML = myObj.text;
         $dar = "quantityspan_"+$id;
         document.getElementById($dar).textContent= myObj.quantity;
         $vdar = "itemprice_"+$id;
         var x = document.getElementById($vdar).textContent;
         console.log(x);
         var y = parseFloat(+x);
         x = document.getElementById("total").textContent;
         console.log(y);
         var z = parseFloat(+x);
         var y = z+y;
         document.getElementById("total").textContent = y.toFixed(2);
         
      }
  };

      
      
   //goes to src/update/basekt/addnewtobasket.php
   //alert("added" + $id);
   xmlhttp.open("GET","../src/update/basket/productquantity.php?p=" + $id + "&q=M", true);
   xmlhttp.send();
   }
   
   function decreasequantity($id)
   {
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
         //document.getElementById($id).innerHTML = myObj.text;
         if(myObj.hasOwnProperty('quantity'))
         {
             $dar = "quantityspan_"+$id;
             document.getElementById($dar).textContent = myObj.quantity;
             $vdar = "itemprice_"+$id;
             var x = document.getElementById($vdar).textContent;
         var y = parseFloat(+x);
         x = document.getElementById("total").textContent;
         var z = parseFloat(+x);
         var y = z-y;
         document.getElementById("total").textContent = y.toFixed(2);

         }
         else if(myObj.hasOwnProperty('text'))
         {
             if(myObj.text === 'REMOVE')
             {
                 $vdar = "itemprice_"+$id;
                var x = document.getElementById($vdar).textContent;
                var y = parseFloat(+x);
                x = document.getElementById("total").textContent;
                var z = parseFloat(+x);
                var y = z-y;
         document.getElementById("total").textContent = y.toFixed(2);
         
                 var div = document.getElementById("item_"+$id);
                 div.parentNode.removeChild(div);
                 if(myObj.hasOwnProperty('sessionsize'))
                 {
                     if(myObj.sessionsize === 0)
                     {
                         var x = document.getElementById("checkoutbuttons");
                         if (x.style.display === "none") 
                         {
                            x.style.display = "inline-block";
                         } else 
                         {
                            x.style.display = "none";
                            
                         }
                     }
                 }

             }

         }
     }  
   };

      
      
   //goes to src/update/basekt/addnewtobasket.php
   //alert("added" + $id);
   xmlhttp.open("GET","../src/update/basket/productquantity.php?p=" + $id + "&q=L" , true);
   xmlhttp.send();
   }
</script>