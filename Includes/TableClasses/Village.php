<?php

class Village
{
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
    private $con;
    private $image_base_url = "https://pakug.com/";

    public function __construct($con, $id)
    {
        $this->con = $con;
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

    public function getNo()
    {
        return $this->no;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCounty()
    {
        return $this->county;
    }

    public function getSubcounty()
    {
        return $this->subcounty;
    }

    public function getLong()
    {
        return $this->long;
    }

    public function getLat()
    {
        return $this->lat;
    }

    public function getPopulation()
    {
        return $this->population;
    }

    public function getFamilies()
    {
        return $this->families;
    }

    public function getLastupdate()
    {
        return $this->lastupdate;
    }

    public function getSourceWater()
    {
        return $this->sourceWater;
    }

    public function getSourceDeepWell()
    {
        return $this->sourceDeepWell;
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function getIconPath() {
        return $this->iconPath;
    }

    public function getSummary()
    {
        return "The village of $this->name is located in $this->subcounty, $this->county county. It has a population of $this->population people and $this->families families. The main source of water is $this->sourceWater, and it is $this->priority priority. The last update was on $this->lastupdate";
    }

    public function getActivities(): array
    {
        $village_activities_array = array();
        $query = $this->con->prepare("SELECT `id`, `title`, `description`, `dateCarriedout`, `dateCompleted`, `nextDueDate`, `InchargePerson`, `village_no`, `dateAdded`, `long`, `lat`, `iconPath` FROM `activities` WHERE village_no = :village_id ");
        $query->bindParam(":village_id", $this->no, PDO::PARAM_INT);
        $query->execute();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {

            $temp = array();
            $temp['id'] = $row['id'];
            $temp['title'] = $row['title'];
            $temp['description'] = $row['description'];
            $temp['dateCarriedout'] = $row['dateCarriedout'];
            $temp['dateCompleted'] = $row['dateCompleted'];
            $temp['nextDueDate'] = $row['nextDueDate'];
            $temp['InchargePerson'] = $row['InchargePerson'];
            $temp['long'] = $row['long'];
            $temp['lat'] = $row['lat'];
            array_push($village_activities_array, $temp);
        }

        return $village_activities_array;
    }

    public function getLeaders(): array
    {
        $village_leaders_array = array();
        $query = $this->con->prepare("SELECT `id`, `name`, `title`, `contact`, `role`, `job description`, `dateAdded`, `village_no` FROM `leaders` WHERE village_no = :village_id ");
        $query->bindParam(":village_id", $this->no, PDO::PARAM_INT);
        $query->execute();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $temp = array();
            $temp['id'] = $row['id'];
            $temp['name'] = $row['name'];
            $temp['title'] = $row['title'];
            $temp['contact'] = $row['contact'];
            array_push($village_leaders_array, $temp);
        }

        return $village_leaders_array;
    }

    public function getDeepWells(): array
    {
        $village_deepWell_array = array();
        $query = $this->con->prepare("SELECT `id`, `name`, `description`, `depth`, `longitude`, `latitude`, `flowrate`, `water_testing_findings`, `water_testing_last_update`, `installationDate`, `drillDate`, `staticWaterLevel`, `lastMaintainence`, `InChargeName`, `InChargeContact`, `village_no`, `dateAdded`, `iconPath` FROM `deepwells` WHERE village_no = :village_id ");
        $query->bindParam(":village_id", $this->no, PDO::PARAM_INT);
        $query->execute();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {

            $temp = array();
            $temp['id'] = $row['id'];
            $temp['name'] = $row['name'];
            $temp['depth'] = $row['depth'];
            $temp['InChargeName'] = $row['InChargeName'];
            array_push($village_deepWell_array, $temp);

        }

        return $village_deepWell_array;
    }

    public function getNeeds(): array
    {
        $village_needs_array = array();
        $query = $this->con->prepare("SELECT `id`, `title`, `description`, `dateAdded`, `status`, `village_no`, `tags` FROM `needs` WHERE village_no = :village_id ");
        $query->bindParam(":village_id", $this->no, PDO::PARAM_INT);
        $query->execute();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {

            $temp = array();
            $temp['id'] = $row['id'];
            $temp['title'] = $row['title'];
            $temp['status'] = $row['status'];
            $temp['tags'] = $row['tags'];
            array_push($village_needs_array, $temp);
        }

        return $village_needs_array;
    }

    public function getOutreach(): array
    {
        $village_outreach_array = array();
        $query = $this->con->prepare("SELECT `id`, `title`, `description`, `startDate`, `endDate`, `dateAdded`, `village_no` FROM `outreach` WHERE village_no = :village_id ");
        $query->bindParam(":village_id", $this->no, PDO::PARAM_INT);
        $query->execute();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $temp = array();
            $temp['id'] = $row['id'];
            $temp['title'] = $row['title'];
            $temp['startDate'] = $row['startDate'];
            $temp['endDate'] = $row['endDate'];
            $temp['description'] = $row['description'];
            array_push($village_outreach_array, $temp);
        }

        return $village_outreach_array;
    }
}
