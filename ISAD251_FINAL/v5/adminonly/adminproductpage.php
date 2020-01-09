<head>
   <title>Admin Products</title>
    <link rel ="stylesheet" href="../assets/css/adminproductpage.css">

</head>

<ul class="listing"> 
<?php
include_once '../src/retreive/product/allproducts/allproductsasadmin.php';
         $Players = AllProductsAsAdmin::queryDatabase();
         if($Players != NULL)
         { 
           foreach ($Players as $player) 
            {

               echo '<li class = "li">';
                echo '   <img class="img" src="'.$player[5].'"><img>';
                echo '<input class= "body" type="text" id="imageurl'.$player[0].'" placeholder="images.google.com/..." maxlength="400"><br>';
               echo '<input class= "body" type="text" id="productname'.$player[0].'" placeholder="'.$player[1].'" maxlength="40"><br>';
               echo '<input class= "body" type="text" id="productdescription'.$player[0].'" placeholder="'.$player[2].'" maxlength="400"><br>';
               echo '<input class= "body" type="text" id="productprice'.$player[0].'" placeholder="'. number_format($player[6], 2).'" maxlength="5"><br>';
               echo '<input class= "body" type="text" id="productstock'.$player[0].'" placeholder="'.$player[3].'" maxlength="4"<br>';

               if($player[4] == 0)
               {
                  $isdeleted = 'Selling';
               }
                if($player[4] == 1)
               {
                  $isdeleted = 'Widthdrawn';
               }
               echo '<br>'.$isdeleted.'</br>';

               echo '<div class="btn" id="savechanges'.$player[0].'" onclick="savechanges('.$player[0].')">Save Changes</div>';
               echo '<div class="btn" id="withdrawbtn'.$player[0].'" onclick="withdraw('.$player[0].')">Change Status</div>';
              echo ' </li>';

            } 
            
                echo '<li class = "li">';
                echo '   <img class="img" src=""><img>';
                echo '<input class= "body" type="text" id="imageurlnew" placeholder="images.google.com/..." maxlength="400"><br>';
               echo '<input class= "body" type="text" id="productnamenew" placeholder="enter product name" maxlength="40"><br>';
               echo '<input class= "body" type="text" id="productdescriptionnew" placeholder=" description..." maxlength="400"><br>';
               echo '<input class= "body" type="text" id="productpricenew" placeholder="10.99" maxlength="5"><br>';
               echo '<input class= "body" type="text" id="productstocknew" placeholder="64" maxlength="4"<br>';
               echo '<div class="btn" id="newproductbtn" onclick="createnewproduct(this.id)">Change Status</div>';
              echo ' </li>';
         }
         else
         {
            echo "<p>Sorry, there was trouble connecting to the Database. Please try again later. </p>";
         }
         
       ?>
</ul>

<script>
   
function createnewproduct($id)
{
   
   var changes = [];
   
    
   var c = document.getElementById("imageurlnew").value;
   changes.push(c);
   c = document.getElementById("productnamenew").value;
   changes.push(c);
   c = document.getElementById("productdescriptionnew").value;
   changes.push(c);
   c = document.getElementById("productpricenew").value;
   changes.push(c);
   c = document.getElementById("productstocknew").value;
   changes.push(c);
   changes.push($id);


   var xmlhttp = new XMLHttpRequest();
   //function to execute on response
   xmlhttp.onreadystatechange = function() 
   {
      if (this.status == 200) 
      {
         window.location.href("/adminproductpage.php");

         if(this.responseText == "TRUE")
         {
            alert("Product changes applied");
            window.location.reload();
         }
         else
         {
            alert("sorry, there was a problem.\n\
                   please try again later."); 
            

         }


      }
   };
   var xmlhttp = new XMLHttpRequest();   // new HttpRequest instance 
   xmlhttp.open("POST","../src/create/new/newproduct.php");
   xmlhttp.setRequestHeader("Content-Type", "application/json");
   xmlhttp.send(JSON.stringify(changes));
};

function withdraw($id)
{
   
   
   //new xmlhttprequest object
   var xmlhttp = new XMLHttpRequest();
   //function to execute on response
   xmlhttp.onreadystatechange = function() 
   {
      if (this.readyState == 4 && this.status == 200) 
      {

         if(JSON.parse(this.responseText) == "TRUE")
         {
            alert("Product status updated"); 
         }
         else
         {
            alert("sorry, there was a problem.\n\
                   please try again later."); 

         }
             window.location.reload();


      }
   };

      
      
   //goes to src/update/basekt/addnewtobasket.php
   //alert("added" + $id);
   xmlhttp.open("GET","../src/update/product/adminwidthdrawproduct.php?p=" + $id, true);
   xmlhttp.send();
};

function savechanges($id)
{
   
   var changes = [];
   
    
   var c = document.getElementById("imageurl".concat($id)).value;
   changes.push(c);
   c = document.getElementById("productname".concat($id)).value;
   changes.push(c);
   c = document.getElementById("productdescription".concat($id)).value;
   changes.push(c);
   c = document.getElementById("productprice".concat($id)).value;
   changes.push(c);
   c = document.getElementById("productstock".concat($id)).value;
   changes.push(c);
   changes.push($id);


   var xmlhttp = new XMLHttpRequest();
   //function to execute on response
   xmlhttp.onreadystatechange = function() 
   {
      if (this.status == 200) 
      {
         window.location.href("/adminproductpage.php");

         if(this.responseText == "TRUE")
         {
            alert("Product changes applied");
            window.location.reload();
         }
         else
         {
            alert("sorry, there was a problem.\n\
                   please try again later."); 
            

         }


      }
   };
   var xmlhttp = new XMLHttpRequest();   // new HttpRequest instance 
   xmlhttp.open("POST","../src/update/product/adminproductchanges.php");
   xmlhttp.setRequestHeader("Content-Type", "application/json");
   xmlhttp.send(JSON.stringify(changes));
}
</script>

