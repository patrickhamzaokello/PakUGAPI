<?php

class MapData
{

    private $id;
    private $name;
    private $description;
    private $type;
    private $long;
    private $lat;
    private $iconPath;

    public function __construct($id, $name, $description, $type, $long, $lat,$iconPath)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->type = $type;
        $this->long = $long;
        $this->lat = $lat;
        $this->iconPath = $iconPath;
    }

    function mapModel(): array
    {
        $temp = array();
        $temp['id'] = $this->id;
        $temp['name'] = $this->name;
        $temp['description'] = $this->description;
        $temp['type'] = $this->type;
        $temp['long'] = $this->long;
        $temp['lat'] = $this->lat;
        $temp['iconPath'] = $this->iconPath;
        return $temp;
    }
}


