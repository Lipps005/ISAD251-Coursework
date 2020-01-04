<?php

//just a table with button

include_once '../src/zmodels/database/MySQLDatabase.php';

?>

<head>
    <meta http-equiv="refresh" content="3">
    <link rel ="stylesheet" href="../assets/css/adminorderpage.css">

</head>

<?php
echo '<ul "grid-container">';
$Players = MySQLDatabase::getConnection()->query("SELECT OrderID FROM `Order` WHERE DAY(OrderDate) = DAY(CURDATE());");
if($Players != NULL)
{
    foreach($Players as $player)
    {

        $orderid = $player[0];
        $SQL1 = "SELECT `ORDER`.ORDERID, `ORDER`.ORDERSTATUS, `ORDER`.ORDERDATE, CUSTOMER.CUSTOMERID
                FROM `ORDER`
                INNER JOIN CUSTOMER ON
                `ORDER`.CUSTOMERID = CUSTOMER.CUSTOMERID AND `ORDER`.ORDERID = $orderid;";
        
        $OrderDetails = MySQLDatabase::getConnection()->query($SQL1);
        if($OrderDetails != NULL)
        {
            foreach($OrderDetails as $details)
            {
                if($details[1] == "Cooking")
                {
                    $stylecolor = "green";
                }
                else
                {
                    $stylecolor = "red";
                }
                echo '<div class = "item" style="border-left: 12px solid '.$stylecolor.'" id="divitem_'.$details[0].'">';
                echo '<div class=title style="background-color: '.$stylecolor.'" id="divtitle_'.$details[0].'">';
                echo '<span>CUSTOMER ID : '.$details[3].'</span>';
                echo '<span >ORDER ID : ' .$details[0].'</span>';
                echo '<span >ORDER DATE : ' .$details[2].'</span>';
                echo '<span >ORDER STATUS : ' .$details[1].'</span>';
                echo '</div>'; 
            }

            $SQL2 = "SELECT PRODUCT.PRODUCTID, PRODUCT.PRODUCTNAME, ORDERPRODUCT.QUANTITY, CUSTOMER.CUSTOMERID
                    FROM ORDERPRODUCT
                    INNER JOIN `ORDER` ON
                    `ORDER`.ORDERID = ORDERPRODUCT.ORDERID AND DAY(`ORDER`.ORDERDATE) = DAY(CURDATE()) AND `ORDER`.ORDERID = $orderid
                    INNER JOIN PRODUCT ON
                    ORDERPRODUCT.PRODUCTID = PRODUCT.PRODUCTID
                    INNER JOIN CUSTOMER ON
                    CUSTOMER.CUSTOMERID = `ORDER`.CUSTOMERID
                    ORDER BY `ORDER`.ORDERDATE;
                   ";
            $Orders = MySQLDatabase::getConnection()->query($SQL2);
            if($Orders != NULL)
            {

                foreach($Orders as $order)
                {
                 echo '<li class="product">'.$order[1] .' x '.$order[2] .'</li>';   
                }
                echo '<button class="btn" id="'.$orderid.'" onclick="changeorderstatus(this.id)">Mark Cooking</button>';
                echo'</div>';
            }
        }
        
    }
}
    
echo '</ul>';


        
        
?>

<script>
   function changeorderstatus($id)
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
         var str1 = 'divitem_';
         var str2 = 'divtitle_';
        $element1 = str1.concat($id);
         $element2 = str2.concat($id);
         document.getElementById($element1).style = "border-left: 12px solid green;";
         document.getElementById($element2).style = "background-color: green;";

      }
   };

      
      
   //goes to src/update/basekt/addnewtobasket.php
   //alert("added" + $id);
   xmlhttp.open("GET","../src/update/order/orderstatusadmin.php?p=" + $id, true);
   xmlhttp.send();
   }
</script>