<?php

class DeepWell
{
    private $id;
    private $name;
    private $description;
    private $depth;
    private $longitude;
    private $latitude;
    private $flowrate;
    private $waterTestingFindings;
    private $waterTestingLastUpdate;
    private $installationDate;
    private $drillDate;
    private $staticWaterLevel;
    private $lastMaintainence;
    private $inChargeName;
    private $inChargeContact;
    private $villageNo;
    private $dateAdded;
    private $iconPath;
    private $imageBaseUrl = "https://pakug.com/";

    public function __construct($con, $id)
    {
        $query = $con->prepare("SELECT * FROM `deepwells` WHERE `id` = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        $row = $query->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->description = $row['description'];
            $this->depth = $row['depth'];
            $this->longitude = $row['longitude'];
            $this->latitude = $row['latitude'];
            $this->flowrate = $row['flowrate'];
            $this->waterTestingFindings = $row['water_testing_findings'];
            $this->waterTestingLastUpdate = $row['water_testing_last_update'];
            $this->installationDate = $row['installationDate'];
            $this->drillDate = $row['drillDate'];
            $this->staticWaterLevel = $row['staticWaterLevel'];
            $this->lastMaintainence = $row['lastMaintainence'];
            $this->inChargeName = $row['InChargeName'];
            $this->inChargeContact = $row['InChargeContact'];
            $this->villageNo = $row['village_no'];
            $this->dateAdded = $row['dateAdded'];
            $this->iconPath = $row['iconPath'];
        } else {
            // Handle the case where no deepwell was found with the given ID
            return null;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getDepth()
    {
        return $this->depth;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function getFlowrate()
    {
        return $this->flowrate;
    }

    public function getWaterTestingFindings()
    {
        return $this->waterTestingFindings;
    }

    public function getWaterTestingLastUpdate()
    {
        return $this->waterTestingLastUpdate;
    }

    public function getInstallationDate()
    {
        return $this->installationDate;
    }

    public function getDrillDate()
    {
        return $this->drillDate;
    }

    public function getStaticWaterLevel()
    {
        return $this->staticWaterLevel;
    }

    public function getLastMaintainence()
    {
        return $this->lastMaintainence;
    }

    public function getInChargeName()
    {
        return $this->inChargeName;
    }

    public function getInChargeContact()
    {
        return $this->inChargeContact;
    }

    public function getVillageNo()
    {
        return $this->villageNo;
    }

    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    public function getIconPath()
    {
        return $this->iconPath;
    }
}