<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once '../../../src/zmodels/database/MySQLDatabase.php';

if(!isset($_SESSION))
{
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

if(sizeof($_SESSION['ORDERBASKET']) > 0)
{
    //GET CURRENT ORDER STATUS (SEE IF IT HAS CHANGED)
    $id = $_SESSION['ORDERID'];
    $SQL = "SELECT OrderStatus FROM `order` WHERE OrderID = $id;";
    $Players = MySQLDatabase::getConnection()->query($SQL);
    
    if($Players != NULL)
    {
        $orderstatus;
        foreach($Players as $player)
        {
           $orderstatus = $player[0];   
        }
        if($orderstatus == 'Pending')
        {
            $cartkeys = array_keys($_SESSION['ORDERBASKET']);
            foreach($cartkeys as $item)
            {    
               if($_SESSION['ORDERBASKET'][$item]["isdirty"] != 0)
               {
                  //if the quantity of the item in the basket is different to that when the order
                  //was changed, then change the quantities to reflect those in the db.
                $SQL = "SELECT StockQuantity FROM Product WHERE ProductID = $item;";
                //get the quantity of the product in stock.
                $Players = MySQLDatabase::getConnection()->query($SQL);           
                  if($Players != NULL)
                  {
                     $oid = $_SESSION['ORDERID'];
                     $SQL = "SELECT QUANTITY FROM OrderProduct WHERE ProductID = $item AND orderid = $oid;";
                     $Players2 = MySQLDatabase::getConnection()->query($SQL);           
           
                      //foreach makes $player an array of the result
                      foreach($Players as $player)
                      {
                         foreach($Players2 as $pl2)
                         {
                      //var_dump($player[0]);
                           //while the quantity of the item in the basket is more than the stock quantity,
                           //and the item is still in the basket, subtract 1 from the quantity of the item. 
                           //if the quantity is less than 1, remove the item from the basket
                          while($_SESSION['ORDERBASKET'][$item]["quantity"] > ($player[0]+$pl2[0]) && array_key_exists($item, $_SESSION['ORDERBASKET']))
                          {

                              if($_SESSION['ORDERBASKET'][$item]["quantity"] > 0)
                              {
                                  $_SESSION['ORDERBASKET'][$item]["quantity"]--;
                              }
                              else
                              {
                                unset($_SESSION['ORDERBASKET'][$item]);
                              } 

                          }
                         }
                      }
                  }
                  else
                  {
                      //error getting stock quantity
                      //var_dump($item);
                      //var_dump(MySQLDatabase::getConnection()->errorInfo());
                      $ERRORobj->text = "error1";
                       $SUCC = false;
                  }
               }
            }
            if(sizeof($_SESSION['ORDERBASKET']) > 0)
            {
                //orderbasket still has items in it. 
                //delete all the old items that were in the order, and insert the items in the db,
                //which may/may not have new quantities.
                $transsuccess = false;

                try
                {
                   $cartkeys = array_keys($_SESSION['ORDERBASKET']);

                    //have an array of dirty products
                    MySQLDatabase::getConnection()->beginTransaction();
                    foreach($cartkeys as $key)
                    {
                      MySQLDatabase::getConnection()->query("UPDATE product SET StockQuantity = "); 
                    }
                    //delete all the products currently being ordered
                    $id = $_SESSION["ORDERID"];
                    $SQL = "DELETE FROM `orderproduct` WHERE orderid = $id";
                    MySQLDatabase::getConnection()->query($SQL);
                    $cartkeys = array_keys($_SESSION['ORDERBASKET']);
                    foreach($cartkeys as $key)
                  {
                      //for each item in the basket, add it to the db. the stock quantity updates
                      //automatically by itself. 
                        $basketquantity = $_SESSION['ORDERBASKET'][$key]["quantity"];
                        //var_dump($basketquantity);
                        MySQLDatabase::getConnection()->exec("INSERT INTO OrderProduct (OrderID, ProductID, Quantity)
                        VALUES ($id, $key, $basketquantity);");

                  } 
                  MySQLDatabase::getConnection()->commit();
                $transsuccess = true;

                }
                catch(PDOException $e)
                {
                   MySQLDatabase::getConnection()->rollback();

                }
                

                
            }
            
        }
        else
        {
            //no order status, OR ORDER STATUS IS COOKING
            $ERRORobj->text = "error2";
            $SUCC = false;
        }
    }
    else 
    {
        //error getting order status
        $ERRORobj->text = "error3";
        $SUCC = false;
    }
}
else
{
  //order basket haa no items in it.   

    
    //delete the order. 
    //double check the order status hasnt changed to Cooking. 
    $id = $_SESSION['ORDERID'];
    $Players = MySQLDatabase::getConnection()->query("SELECT OrderStatus FROM `order` WHERE orderid = $id ;");
    if($Players != NULL)
    {
        $status = null;
        foreach($Players as $player)
        {
            $status = $player[0];
        }
        if($status == 'Pending')
        {
            //can delete order. 
            //start transaction
            try
                {
                    MySQLDatabase::getConnection()->beginTransaction();
                   $SQL = "DELETE FROM `orderproduct` WHERE orderid = $id";
                   MySQLDatabase::getConnection()->query($SQL);
                   $id = $_SESSION['ORDERID'];
                   MySQLDatabase::getConnection()->query("DELETE FROM `order` WHERE orderid = $id ;");
                   $id = $_SESSION['CUSTOMERID'];
                   MySQLDatabase::getConnection()->query("DELETE FROM `customer` WHERE customerid = $id ;");
                  MySQLDatabase::getConnection()->commit();
                  unset($_SESSION['ORDERID']);
                  unset($_SESSION['CUSTOMERID']);
                  unset($_SESSION['ORDERBASKET']);


                }
                catch(PDOException $e)
                {
                    MySQLDatabase::getConnection()->rollback();
                    $ERRORobj->text = "Could not delete your order.";
                    $SUCC = false;
                }
        }
        else if($status == 'Cooking')
        {
            //status has changed to cooking, cant change the order. 
            $ERRORobj->text = "Cannot change your order anymore!";
            $SUCC = false;
        }
        else
        {
            $ERRORobj->text = "error4";
            $SUCC = false;
        }
    }
    //start transaction
    //delete all products from order where orderid = that in session
    //delete the order from the orders table. 
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