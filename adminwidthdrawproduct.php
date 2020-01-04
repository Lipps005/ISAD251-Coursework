<?php
include_once '../../../src/zmodels/database/MySQLDatabase.php';

$p = $_REQUEST["p"];

if (!isset($incobj)) $statobj = new stdClass();

$statobj->text = "FALSE";
try 
{
       //is deleted, so restore.
      MySQLDatabase::getConnection()->beginTransaction();
      MySQLDatabase::getConnection()->query("update product set isdeleted = !isdeleted where productid = $p;");
      MySQLDatabase::getConnection()->commit();
      $statobj = "TRUE";

} 
catch (PDOException $ex) 
{
   MySQLDatabase::getConnection()->rollback();
   $statobj = "FALSE";
}

$o = json_encode($statobj);
echo $o;