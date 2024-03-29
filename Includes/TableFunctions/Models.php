<?php

class Models
{

    private $conn;

    public function __construct($con)
    {
        $this->conn = $con;
    }


    function mapModel($id, $name, $description, $type, $long, $lat, $iconPath): array
    {
        $temp = array();
        $temp['id'] = $id;
        $temp['name'] = $name;
        $temp['description'] = $description;
        $temp['type'] = $type;
        $temp['long'] = $long;
        $temp['lat'] = $lat;
        $temp['iconPath'] = $iconPath;
        return $temp;
    }

    function villageModel($village): array
    {
        $temp = array();
        $temp['id'] = $village->getNo();
        $temp['name'] = $village->getName();
        $temp['county'] = $village->getCounty();
        $temp['description'] = $village->getSummary();
        $temp['subcounty'] = $village->getSubcounty();
        $temp['long'] = $village->getLong();
        $temp['lat'] = $village->getLat();
        $temp['population'] = $village->getPopulation();
        $temp['families'] = $village->getFamilies();
        $temp['lastupdate'] = $village->getLastUpdate();
        $temp['sourceWater'] = $village->getSourceWater();
        $temp['sourceDeepWell'] = $village->getSourceDeepWell();
        $temp['priority'] = $village->getPriority();
        $temp['iconPath'] = $village->getIconPath();
        return $temp;
    }


}


