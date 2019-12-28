<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

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
    if(array_key_exists($p, $_SESSION['ORDERBASKET']))
    {
          $_SESSION['ORDERBASKET'][$p]["quantity"]++;
          $incobj->quantity = $_SESSION['ORDERBASKET'][$p]["quantity"];
          $incobj->sessionsize = sizeof($_SESSION['ORDERBASKET']);
          $incJSON = json_encode($incobj);
          echo $incJSON;
    }
    
}
else if($q == "L")
{   if(array_key_exists($p, $_SESSION['ORDERBASKET']))
    {
    if(($_SESSION['ORDERBASKET'][$p]["quantity"] - 1) <= 0)
    {
        unset($_SESSION['ORDERBASKET'][$p]);
         $decobj->text = "REMOVE";
         $decobj->sessionsize = sizeof($_SESSION['ORDERBASKET']);
         $decJSON = json_encode($decobj);
         echo $decJSON;

    }
    else
    {
        $_SESSION['ORDERBASKET'][$p]["quantity"]--;
        $decobj->quantity = $_SESSION['ORDERBASKET'][$p]["quantity"];
        $decobj->sessionsize = sizeof($_SESSION['ORDERBASKET']);
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