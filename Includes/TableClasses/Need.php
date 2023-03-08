<?php

class Need {
    private $id;
    private $title;
    private $description;
    private $dateAdded;
    private $status;
    private $village_no;
    private $tags;
    private $image_base_url = "https://pakug.com/";

    public function __construct($con, $id) {
        $query = $con->prepare("SELECT * FROM `needs` WHERE `id` = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        $row = $query->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->id = $row['id'];
            $this->title = $row['title'];
            $this->description = $row['description'];
            $this->dateAdded = $row['dateAdded'];
            $this->status = $row['status'];
            $this->village_no = $row['village_no'];
            $this->tags = $row['tags'];
        } else {
            // Handle the case where no need was found with the given ID
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

    public function getDateAdded() {
        return $this->dateAdded;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getVillageNo() {
        return $this->village_no;
    }

    public function getTags() {
        return $this->tags;
    }
}
