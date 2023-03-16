<?php
require '../../Includes/config/Database.php';
require "../../Includes/TableClasses/Activity.php";
require "../../Includes/TableClasses/DeepWell.php";
require "../../Includes/TableClasses/DeepWellMaintenance.php";
require "../../Includes/TableClasses/Family.php";
require "../../Includes/TableClasses/Leader.php";
require "../../Includes/TableClasses/Need.php";
require "../../Includes/TableClasses/Outreach.php";
require "../../Includes/TableClasses/User.php";
require "../../Includes/TableClasses/Village.php";


include_once '../../Includes/TableFunctions/Models.php';
include_once '../../Includes/TableFunctions/Handler.php';


$database_object = new Database(false);
$db = $database_object->getConnection();