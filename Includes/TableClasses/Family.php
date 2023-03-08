<?php

class Family {
    private $id;
    private $demographic;
    private $needs;
    private $dateAdded;
    private $villageNo;

    public function __construct($con, $id) {
        $query = $con->prepare("SELECT * FROM `family` WHERE `id` = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        $row = $query->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->id = $row['id'];
            $this->demographic = $row['demographic'];
            $this->needs = $row['needs'];
            $this->dateAdded = $row['dateAdded'];
            $this->villageNo = $row['village_no'];
        } else {
            // Handle the case where no family was found with the given ID
            return null;
        }
    }

    public function getId() {
        return $this->id;
    }

    public function getDemographic() {
        return $this->demographic;
    }

    public function getNeeds() {
        return $this->needs;
    }

    public function getDateAdded() {
        return $this->dateAdded;
    }

    public function getVillageNo() {
        return $this->villageNo;
    }
}
