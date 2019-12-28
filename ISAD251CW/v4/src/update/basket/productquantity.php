<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


//session stuff

if(!isset($_SESSION))
{
   session_start();
   if(!isset($_SESSION['CART']))
   {
      $_SESSION['CART'] = array();
   }
}

//JSON will have to be dynamically generated. 
if (!isset($incobj)) $incobj = new stdClass();
if (!isset($decobj)) $decobj = new stdClass();

//get first parameter from string
$p = $_REQUEST["p"];
//get second parameter from string
$q = $_REQUEST["q"];

if($q == "M")
{
    if(array_key_exists($p, $_SESSION['CART']))
    {
          $_SESSION['CART'][$p]["quantity"]++;
          $incobj->quantity = $_SESSION['CART'][$p]["quantity"];
          $incobj->sessionsize = sizeof($_SESSION['CART']);
          $incJSON = json_encode($incobj);
          echo $incJSON;
    }
    
}
else if($q == "L")
{   if(array_key_exists($p, $_SESSION['CART']))
    {
    if(($_SESSION['CART'][$p]["quantity"] - 1) <= 0)
    {
        unset($_SESSION['CART'][$p]);
         $decobj->text = "REMOVE";
         $decobj->sessionsize = sizeof($_SESSION['CART']);
         $decJSON = json_encode($decobj);
         echo $decJSON;

    }
    else
    {
        $_SESSION['CART'][$p]["quantity"]--;
        $decobj->quantity = $_SESSION['CART'][$p]["quantity"];
        $decobj->sessionsize = sizeof($_SESSION['CART']);
        $decJSON = json_encode($decobj);
        echo $decJSON;
        //var_dump($_SESSION['CART']);
        


    }
    }
    else
    {
        $decobj->text = "NAN";
         $decJSON = json_encode($decobj);
         echo $decJSON;
    }
}
    
   


//if quantity - 1 = 0, then remove the product from the session cart, and return REM string to user,
//which JS functio will interpret as a command to remove the html item element. 

//else increase the quantity in the basket and return the new quantity to the user

