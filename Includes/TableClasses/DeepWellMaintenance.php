<?php
class DeepWellMaintenance {
    private $id;
    private $description;
    private $cost;
    private $dateDone;
    private $technicianName;
    private $technicianContact;
    private $dateAdded;
    private $deepWellId;
    private $imageBaseUrl = "https://example.com/";

    public function __construct($con, $id) {
        $query = $con->prepare("SELECT * FROM `deep_well_maintainence` WHERE `id` = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        $row = $query->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->id = $row['id'];
            $this->description = $row['description'];
            $this->cost = $row['cost'];
            $this->dateDone = $row['dateDone'];
            $this->technicianName = $row['Technician Name'];
            $this->technicianContact = $row['Technician Contact'];
            $this->dateAdded = $row['dateAdded'];
            $this->deepWellId = $row['deepWells_id'];
        } else {
            // Handle the case where no maintenance record was found with the given ID
            return null;
        }
    }

    public function getId() {
        return $this->id;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getCost() {
        return $this->cost;
    }

    public function getDateDone() {
        return $this->dateDone;
    }

    public function getTechnicianName() {
        return $this->technicianName;
    }

    public function getTechnicianContact() {
        return $this->technicianContact;
    }

    public function getDateAdded() {
        return $this->dateAdded;
    }

    public function getDeepWellId() {
        return $this->deepWellId;
    }

}
