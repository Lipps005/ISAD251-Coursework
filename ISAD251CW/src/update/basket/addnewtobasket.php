<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


//check if session exists, 
//if no session exists, create one,
//init session variables/'placeholders'
if(!isset($_SESSION))
{
   session_start();
   if(!isset($_SESSION['CART']))
   {
      $_SESSION['CART'] = array();
   }
}

//define JSON obj for removing item from basket
   //button color to red
   //button text to 'add to basket'

//$removeobj->color = "red";
//$removeobj->text = "add to basket";

//$removeJSON = json_encode($removeobj);
//
//define JSON obj for adding item to basket
   //button color to green
   //button text to 'remove from basket'
if (!isset($addobj)) $addobj = new stdClass();

$addobj->text = "remove from basket";

$addJSON = json_encode($addobj);

if (!isset($remobj)) $remobj = new stdClass();

$remobj->text = "add to basket";

$remJSON = json_encode($remobj);
//receive incoming request from client
//decode/parse JSON 
//retreive product ID

$p = $_REQUEST["p"];

$newcartitem = array(
  'quantity' => 1  
);

if(array_key_exists($p, $_SESSION['CART']))
{
   unset($_SESSION['CART'][$p]);
   echo $remJSON;
}

else if(!array_key_exists($p, $_SESSION['CART']))
{
   $_SESSION['CART'][$p] = $newcartitem;
   echo $addJSON;
}




/*
$basket_item = array
        (
         'quantity'=> 1
        );

//if the item is in the array, remove
if(array_key_exists($p, $_SESSION['CART'])){
   unset($_SESSION['CART'][$p]);
   var_dump($_SESSION);
   echo $removeJSON;
}
else{
    $_SESSION['CART'][$p]=$basket_item;
       var_dump($_SESSION);

    echo $addJSON;
}
//check basket array in session to see if it contains product with matching ID
//if it does, then the button press means they want to remove it from their basket
   // echo back JSON for removing item from basket

//no entry means new item to basket, add item to basket array (should exist)
   //echo back JSON for adding item to basket
 * 
 */