<?php

/* function uses dbconnection in MySQLDatabase class
 * server-side only function call, so doesnt encode in JSON,
 * just returns results from DB.  
 */

include_once '../src/zmodels/database/MySQLDatabase.php';
//class has static function, extends MySQLDatabase. 

class AllProductsAsCustomer extends MySQLDatabase
{
   //private static $instance = null;
   
   
    private function _construct()
    {
       
    }
    /*
     * @override
     */
    public static function queryDatabase()
    {
 //      if(!self::$instance)
 //      {
 //         self::$instance = new AllProductsAsCustomer();
 //      }
        if(MySQLDatabase::getConnection() != NULL)
        {  
         $Players = MySQLDatabase::getConnection()->query("SELECT * FROM Product");
         return $Players;
        }
       
       
    }
}
