<?php

class Activity {
    private $id;
    private $title;
    private $description;
    private $dateCarriedout;
    private $dateCompleted;
    private $nextDueDate;
    private $inchargePerson;
    private $villageNo;
    private $dateAdded;
    private $long;
    private $lat;
    private $iconPath;
    private $imageBaseUrl = "https://pakug.com/";

    public function __construct($con, $id) {
        $query = $con->prepare("SELECT * FROM `activities` WHERE `id` = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        $row = $query->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->id = $row['id'];
            $this->title = $row['title'];
            $this->description = $row['description'];
            $this->dateCarriedout = $row['dateCarriedout'];
            $this->dateCompleted = $row['dateCompleted'];
            $this->nextDueDate = $row['nextDueDate'];
            $this->inchargePerson = $row['InchargePerson'];
            $this->villageNo = $row['village_no'];
            $this->dateAdded = $row['dateAdded'];
            $this->long = $row['long'];
            $this->lat = $row['lat'];
            $this->iconPath = $row['iconPath'];
        } else {
            // Handle the case where no activity was found with the given ID
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

    public function getDateCarriedout() {
        return $this->dateCarriedout;
    }

    public function getDateCompleted() {
        return $this->dateCompleted;
    }

    public function getNextDueDate() {
        return $this->nextDueDate;
    }

    public function getInchargePerson() {
        return $this->inchargePerson;
    }

    public function getVillageNo() {
        return $this->villageNo;
    }

    public function getDateAdded() {
        return $this->dateAdded;
    }

    public function getLong() {
        return $this->long;
    }

    public function getLat() {
        return $this->lat;
    }

    public function getIconPath() {
        return $this->iconPath;
    }
}
