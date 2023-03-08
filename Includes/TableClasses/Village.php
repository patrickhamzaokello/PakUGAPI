<?php

class Village {
    private $no;
    private $name;
    private $county;
    private $subcounty;
    private $long;
    private $lat;
    private $population;
    private $families;
    private $lastupdate;
    private $sourceWater;
    private $sourceDeepWell;
    private $priority;
    private $iconPath;
    private $image_base_url = "https://pakug.com/";

    public function __construct($con, $id) {
        $query = $con->prepare("SELECT * FROM `village` WHERE `no` = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        $row = $query->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->no = $row['no'];
            $this->name = $row['name'];
            $this->county = $row['county'];
            $this->subcounty = $row['subcounty'];
            $this->long = $row['long'];
            $this->lat = $row['lat'];
            $this->population = $row['population'];
            $this->families = $row['families'];
            $this->lastupdate = $row['lastupdate'];
            $this->sourceWater = $row['sourceWater'];
            $this->sourceDeepWell = $row['sourceDeepWell'];
            $this->priority = $row['priority'];
            $this->iconPath = $row['iconPath'];
        } else {
            // Handle the case where no village was found with the given ID
            return null;
        }
    }

    public function getNo() {
        return $this->no;
    }

    public function getName() {
        return $this->name;
    }

    public function getCounty() {
        return $this->county;
    }

    public function getSubcounty() {
        return $this->subcounty;
    }

    public function getLong() {
        return $this->long;
    }

    public function getLat() {
        return $this->lat;
    }

    public function getPopulation() {
        return $this->population;
    }

    public function getFamilies() {
        return $this->families;
    }

    public function getLastupdate() {
        return $this->lastupdate;
    }

    public function getSourceWater() {
        return $this->sourceWater;
    }

    public function getSourceDeepWell() {
        return $this->sourceDeepWell;
    }

    public function getPriority() {
        return $this->priority;
    }

    public function getIconPath() {
        return $this->iconPath;
    }
}
