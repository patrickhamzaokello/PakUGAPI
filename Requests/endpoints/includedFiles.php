<?php
require '../../Includes/config/Database.php';
require "../../Includes/TableClasses/User.php";
require "../../Includes/TableClasses/Village.php";


include_once '../../Includes/TableFunctions/Models.php';
include_once '../../Includes/TableFunctions/MapData.php';
include_once '../../Includes/TableFunctions/Handler.php';


$database_object = new Database(false);
$db = $database_object->getConnection();