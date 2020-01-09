<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


include_once '../../../src/zmodels/database/MySQLDatabase.php';



$p = $_REQUEST["p"];

if (!isset($incobj)) $statobj = new stdClass();



try 
{
   MySQLDatabase::getConnection()->beginTransaction();
   MySQLDatabase::getConnection()->query("UPDATE `ORDER` SET ORDERSTATUS = 'Cooking' WHERE ORDERID = $p;");
   MySQLDatabase::getConnection()->commit();
   $statobj->text = "TRUE";


} 
catch (PDOException $ex) 
{
   MySQLDatabase::getConnection()->rollback();
   $statobj->text = "FALSE";
}

$STATOBJ = json_encode($statobj);

echo $STATOBJ;