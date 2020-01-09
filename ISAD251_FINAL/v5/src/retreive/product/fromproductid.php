<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



include_once '../src/zmodels/database/MySQLDatabase.php';

class fromproductid extends MySQLDatabase  
{
    private function _construct(){}
    
    
    public static function queryDatabaseByID(int $ProdID)
    {
 //      if(!self::$instance)
 //      {
 //         self::$instance = new AllProductsAsCustomer();
 //      }
        if(MySQLDatabase::getConnection() != NULL)
        {  
         $Players = MySQLDatabase::getConnection()->query("SELECT * FROM Product WHERE ProductID = $ProdID;");
         return $Players;
        }
       
       
    }


}