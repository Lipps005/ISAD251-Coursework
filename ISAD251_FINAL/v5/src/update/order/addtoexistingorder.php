<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



include_once '../../../src/zmodels/database/MySQLDatabase.php';

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




if(sizeof($_SESSION['CART']) > 0)
{
	//order has items in in it
	if(isset($_SESSION['ORDERID']))
	{
		$id = $_SESSION['ORDERID'];
		$Players = MySQLDatabase::getConnection()->query("SELECT OrderStatus FROM `order` WHERE orderid = $id;");
		
		if($Players != NULL)
		{
			$orderstatus = null;
			foreach($Players as $player)
			{
				$orderstatus = $player[0];
			}
			if($orderstatus == 'Pending')
			{
				//can modify order
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
                                                              else
                                                              {
                                                                    unset($_SESSION['CART'][$item]);
                                                              } 

                                                      }
                                              }
                                      }
                                      else
                                      {
                                              $ERRORobj->text = "could not get product information";
                                              $SUCC = false;
                                      }

                                      if(sizeof($_SESSION['CART']) > 0)
                                      {
                                              //cart still has items in it. now add them to the existing order.
                                              $id = $_SESSION['ORDERID'];
                                              $cartkeys = array_keys($_SESSION['CART']);

                                              $transsuccess = false;
                                              try
                                              {
                                                  MySQLDatabase::getConnection()->beginTransaction();
                                                      foreach($cartkeys as $key)
                                                      {
                                                            $basketquantity = $_SESSION['CART'][$key]['quantity'];
                                                            MySQLDatabase::getConnection()->query("INSERT INTO `orderproduct` VALUES ($id, $key, $basketquantity);");
                                                      }
                                                    MySQLDatabase::getConnection()->commit();
                                                    $transsuccess = true;
                                              }
                                              catch(PDOException $e)
                                              {
                                                      MySQLDatabase::getConnection()->rollback();
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
                                                    $ERRORobj->text = "could not update the order";
                                                    $transsuccess = false;
                                            }

                                      }
                                      else
                                      {
                                              $ERRORobj->text = "no items left in cart";
                                              $SUCC = false;
                                      }
                            }
                    }
                    elseif($orderstatus == 'Cooking')
                    {
                            $ERRORobj->text = "cannot change order anymore!";
                            $SUCC = false;
                    }
                    else
                    {
                            $ERRORobj->text = "error getting status";
                            $SUCC = false;
                    }

		}
		else
		{
			$ERRORobj->text = "error getting status";
			$SUCC = false;
		}

	}
	else
	{
		$ERRORobj->text = "NO ORDER ID";
		$SUCC = false;
	}
}
else
{
	$ERRORobj->text = "No items in basket";
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