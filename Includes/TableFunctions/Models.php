<?php

class Models
{

    private $conn;
    public function __construct($con)
    {
        $this->conn = $con;
    }

    function villageModel($no): array
    {
        $village = new Village($this->conn, $no);
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
        $temp['activities'] = $village->getActivities();
        $temp['leaders'] = $village->getLeaders();
        $temp['deepWell'] = $village->getDeepWells();
        $temp['needs'] = $village->getNeeds();
        $temp['outreach'] = $village->getOutreach();

        return $temp;
    }
}


