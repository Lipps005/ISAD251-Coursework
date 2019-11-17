<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MySQLDatabase
 *
 * @author samue
 */
include_once 'iDatabase.php';

class MySQLDatabase implements Idatabase
{

    private static $conn = null;
    
    private function _construct()
    {
        
        
    }
    
    /*
    * @override
    */
    public static function getConnection()
    {
        if(!isset(self::$conn))
        {
        $host = "proj-mysql.uopnet.plymouth.ac.uk";
        $db = "ISAD251_SLippett";
        $dsn = "mysql:host=$host;dbname=$db";
        try
         {
            self::$conn = new PDO($dsn, 'ISAD251_SLippett', 'ISAD251_22214241');
         }
        catch(PDOException $ex)
         {
         }
        }
        
        return self::$conn;
        
        
    }
    
   public static  function queryDatabase()
   {
      
   }

}
