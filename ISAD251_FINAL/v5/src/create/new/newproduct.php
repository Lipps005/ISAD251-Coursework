
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
               $name = '"'.$changesarray[1].'"';
               $description = '"'.$changesarray[2].'"';
               $SQL = "INSERT INTO product (productid, ImageSrc, productname, "
                       . "productdescription, price, stockquantity, isdeleted) VALUES(DEFAULT, '$changesarray[0]', '$name',$description,"
                       . " $changesarray[3], $changesarray[4], 0);";
               //echo var_dump($SQL);
               MySQLDatabase::getConnection()->query($SQL);
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