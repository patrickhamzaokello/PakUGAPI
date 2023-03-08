<?php

class Leader {
    private $id;
    private $name;
    private $title;
    private $contact;
    private $role;
    private $jobDescription;
    private $dateAdded;
    private $villageNo;
    private $imageBaseUrl = "https://pakug.com/";

    public function __construct($con, $id) {
        $query = $con->prepare("SELECT * FROM `leaders` WHERE `id` = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        $row = $query->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->title = $row['title'];
            $this->contact = $row['contact'];
            $this->role = $row['role'];
            $this->jobDescription = $row['job description'];
            $this->dateAdded = $row['dateAdded'];
            $this->villageNo = $row['village_no'];
        } else {
            // Handle the case where no leader was found with the given ID
            return null;
        }
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getContact() {
        return $this->contact;
    }

    public function getRole() {
        return $this->role;
    }

    public function getJobDescription() {
        return $this->jobDescription;
    }

    public function getDateAdded() {
        return $this->dateAdded;
    }

    public function getVillageNo() {
        return $this->villageNo;
    }
}
