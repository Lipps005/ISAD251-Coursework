<?php

/* quantities of all products is limited. Reserving stock for items in 
 * the basket doesnt allow for others to purchase the same item.
 * so, only when the user checks out are available quantities of the products
 * in their basket calculated, and their order amended. 
 * From experience, if you have just run out of a product and an order has just
 * come through, you would give that customer the opportunity to amend their order
 * (order something else). 
 */

include_once '../../../src/zmodels/database/MySQLDatabase.php';
//include_once      '../src/zmodels/database/MySQLDatabase.php';
//                   /src/create/new/neworder.php
//check that the session exists, if not, create it
if(!isset($_SESSION)){
   session_start();
   if(!isset($_SESSION['CART']))
   {
      $_SESSION['CART'] = array();
   }
}

 $SUCC = true;

if (!isset($ERRORobj)) $ERRORobj = new stdClass();
$ERRORobj->text = "ERROR";

if (!isset($SUCCobj)) $SUCCobj = new stdClass();
$SUCCobj->text = "SUCCESS";


$_SESSION['CUSTOMERID'] = 0;
$_SESSION['ORDERID'] = 0;
$_SESSION['ORDERBASKET'] = array();

//double check the size of the session cart
if(sizeof($_SESSION['CART']) > 0)
{

//if cart has items in it, for each item:
     $cartkeys = array_keys($_SESSION['CART']);
    foreach($cartkeys as $item)
    {   //get the quantity of the product in stock. 
          $Players = MySQLDatabase::getConnection()->query("SELECT StockQuantity FROM Product WHERE ProductID = $item;");          
          if($Players != NULL)
          {
              //foreach makes $player an array of the result
              foreach($Players as $player)
              {
              //var_dump($player[0]);
              //var_dump($_SESSION['CART'][$item]["quantity"]);
                   //while the quantity of the item in the basket is more than the stock quantity,
                   //and the item is still in the basket, subtract 1 from the quantity of the item. 
                   //if the quantity is less than 1, remove the item from the basket
                  while($_SESSION['CART'][$item]["quantity"] > $player[0] && array_key_exists($item, $_SESSION['CART']))
                  {

                      if($_SESSION['CART'][$item]["quantity"] > 0)
                      {
                          $_SESSION['CART'][$item]["quantity"]--;
                      }
                      

                  }
                  if($_SESSION['CART'][$item]["quantity"] == 0)
                      {
                        unset($_SESSION['CART'][$item]);
                      } 
              }
          }
          else
          {
              //echo "1";
              $ERRORobj->text = "error1";
              $SUCC = false;
          }
    }
    if(sizeof($_SESSION['CART']) > 0)
    {
        //get next customer id

        $newcustomer = MySQLDatabase::getConnection()->query("INSERT INTO customer VALUES(DEFAULT);");
        //create a new customer
        if($newcustomer == TRUE)
        {
           //successfully created a new customer. now can create new order
           $last_id = MySQLDatabase::getConnection()->lastInsertId();
           //set the customer id in the session. will be used when the user clicks add to exisitng order. 
           $_SESSION['CUSTOMERID'] = $last_id;
           $date = date('Y-m-d H:i:s');
           $SQL = "INSERT INTO `Order`(CustomerID, OrderDate, OrderStatus) VALUES($last_id, '$date', 'Pending' );";
           $neworder = MySQLDatabase::getConnection()->query($SQL);
           //if creation of new order success
           if($neworder == TRUE)
           {
               //added a new order, can add the users products. 
               $cartkeys = array_keys($_SESSION['CART']);
                $last_id = MySQLDatabase::getConnection()->lastInsertId();
                //get order id of order just created and store in the session. 

                $_SESSION['ORDERID'] = $last_id;
                $transsuccess = false;
                //var_dump($_SESSION['CART']);
               //attempt to add each item to the database. 
              try
              {
                  MySQLDatabase::getConnection()->beginTransaction();
                  foreach($cartkeys as $key)
                  {
                      //for each item in the basket, add it to the db. the stock quantity updates
                      //automatically by itself. 
                        $basketquantity = $_SESSION['CART'][$key]['quantity'];
                        MySQLDatabase::getConnection()->exec("INSERT INTO OrderProduct (OrderID, ProductID, Quantity)
                        VALUES ($last_id, $key, $basketquantity);");

                  } 
                  MySQLDatabase::getConnection()->commit();
                  //echo "New records created successfully";
                  $transsuccess = true;
              }
              catch(PDOException $e)
              {
                // roll back the transaction if something failed
                MySQLDatabase::getConnection()->rollback();
                //echo "Error: " . $e->getMessage();

              }
              if($transsuccess)   
              {
                  //if successfully placed order, remove the items from the basket
                  foreach($cartkeys as $key)
                  {
                    unset($_SESSION['CART']);
                  }

              }
              else
              {
                  //echo "2";
                $ERRORobj->text = "error2";

                  $SUCC = false;
              }

           }
           else
           {
               //echo "3";
               $ERRORobj->text = "error3";

               $SUCC = false;
           }
        } 
        else
        {
            //echo "4";
            $ERRORobj->text = "error4";

            $SUCC = false;
        }
    }
    
    
    else
    {
        //echo "5"              
        $ERRORobj->text = "error5";

        $SUCC = false;
    }
 
    
    //if there are still items in the cart
    
    //retrieve the quantity of the item in stock
    //while the quantity in stock is less than the quantity in the basket
    //and the quantity in the basket is >0
        //subtract 1 from the quantity in the basket. 
    
    //for each item in the cart, if the quanity is = 0, remove the item
    //if the cart is empty, alert the user that their products are out of stock
    //(someone couldve just beaten them)
    //else, commit the order:
}       //
else
{   //echo "6";
    $ERRORobj->text = "error6";

    $SUCC = false;
}

if(!$SUCC)
{
    $ERRORJSON = json_encode($ERRORobj);
    echo $ERRORJSON;
}
else
{
    $SUCCJSON = json_encode($SUCCobj);
    echo $SUCCJSON;
}