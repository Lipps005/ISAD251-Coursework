<?php
include_once '../../../src/zmodels/database/MySQLDatabase.php';


if (!isset($incobj)) $statobj = new stdClass();


$json = file_get_contents( "php://input" );

$data = json_decode($json);

if(sizeof($data) > 0)
{
   $changesarray = array();
   
   foreach($data as $d)
   {
      $clean = test_input($d);
      array_push($changesarray, $clean);
   }
   if($changesarray[3] != "" && is_numeric($changesarray[3]))
   {
      $statobj = " 3 is FALSE";
   }
   if($changesarray[4] != "" && is_numeric($changesarray[4]))
   {
      $statobj = "4 is FALSE";
   }
   
         try 
         {
            if($statobj != "FALSE")
            {
                //is deleted, so restore.
               MySQLDatabase::getConnection()->beginTransaction();

               $id = $changesarray[5];
               if($changesarray[0] != "")
               {
                  MySQLDatabase::getConnection()->query("UPDATE product SET ImageSrc = '$changesarray[0]' WHERE productid = $id;");
               }
               if($changesarray[1] != "")
               {
                  MySQLDatabase::getConnection()->query("UPDATE product SET ProductName = '$changesarray[1]' WHERE productid = $id;");
               }
               if($changesarray[2] != "")
               {
                  MySQLDatabase::getConnection()->query("UPDATE product SET ProductDescription = '$changesarray[2]' WHERE productid = $id;");
                  
               }
               if($changesarray[3] != "")
               {
                  MySQLDatabase::getConnection()->query("UPDATE product SET PRICE = $changesarray[3] WHERE productid = $id;");
               }
               if($changesarray[4] != "")
               {
                  MySQLDatabase::getConnection()->query("UPDATE product SET StockQuantity = $changesarray[4] WHERE productid = $id;");
               }
               MySQLDatabase::getConnection()->commit();
               $statobj = "TRUE";
            }
         } 
         catch (PDOException $ex) 
         {
            MySQLDatabase::getConnection()->rollback();
            $statobj = "FALSE";
         }

      //echo var_dump(MySQLDatabase::getConnection()->errorInfo());


}


$o = json_encode($statobj);
echo $o;


function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}