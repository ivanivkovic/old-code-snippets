<?php

switch(HOSTING_LOCATION)
{

case 'TEST_SERVER':

$dbConfig['SERVER'] = 'localhost';
$dbConfig['USER'] 	= 'ivanivk_pixplore';
$dbConfig['PASS'] 	= 'W_eLV.{Gp,-G';
$dbConfig['DB'] 	= 'ivanivk_pixplorer';

break;


case 'WEB_SERVER':

$dbConfig['SERVER'] = '';
$dbConfig['USER'] 	= '';
$dbConfig['PASS'] 	= '';
$dbConfig['DB'] 	= '';

break;

}