<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author samue
 */
interface Idatabase 
{
    //functions the database model should do:
    
    static function getConnection(); //returns current db connection
                              //initialises singleton if none exists
    

   static function queryDatabase();
}
