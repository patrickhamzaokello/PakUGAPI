<?php


class Outreach {
    private $id;
    private $title;
    private $description;
    private $startDate;
    private $endDate;
    private $dateAdded;
    private $villageNo;

    public function __construct($con, $id) {
        $query = $con->prepare("SELECT * FROM `outreach` WHERE `id` = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        $row = $query->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->id = $row['id'];
            $this->title = $row['title'];
            $this->description = $row['description'];
            $this->startDate = $row['startDate'];
            $this->endDate = $row['endDate'];
            $this->dateAdded = $row['dateAdded'];
            $this->villageNo = $row['village_no'];
        } else {
            // Handle the case where no outreach was found with the given ID
            return null;
        }
    }

    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getStartDate() {
        return $this->startDate;
    }

    public function getEndDate() {
        return $this->endDate;
    }

    public function getDateAdded() {
        return $this->dateAdded;
    }

    public function getVillageNo() {
        return $this->villageNo;
    }
}
