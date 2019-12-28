<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if(!isset($_SESSION)){
   session_start();
   if(!isset($_SESSION['CART']))
   {
      $_SESSION['CART'] = array();
   }
}




$obj = sizeof($_SESSION['CART']);
$objJSON = json_encode($obj);
echo $objJSON;