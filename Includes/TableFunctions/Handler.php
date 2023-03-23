<?php

class Handler
{

    private $image_base_url = "https://pakug.com/";
    private $conn;
    private $version;

    // track update info

    public function __construct($con)
    {
        $this->conn = $con;
        $this->version = 1; // VersionCode
    }


    function searchNormal(): array
    {
        $page = htmlspecialchars(strip_tags($_GET["page"]));
        $search_query = htmlspecialchars(strip_tags($_GET["key_query"]));
        $search_algorithm = "normal";
        // create the base variables for building the search query

        $page = floatval($page);
        $no_of_records_per_page = 10;
        $offset = ($page - 1) * $no_of_records_per_page;

        $itemRecords = array();

        $perform_query = true;
        // create the base variables for building the search query

        if (strlen($search_query) > 100 || strlen($search_query) < 3) {
            $perform_query = false;
        }

        if (empty($search_query)) {
            $perform_query = false;
        }

        if ($perform_query == true) {
            // echo Update Search Table;
            $sh_result = mysqli_query($this->conn, "SELECT * FROM `searches` WHERE `query`='" . $this->conn->real_escape_string($search_query) . "' LIMIT 1;");
            $sh_data = mysqli_fetch_assoc($sh_result);
            if ($sh_data != null) {
                $sh_id = floatval($sh_data['id']);
                $countQuery = mysqli_query($this->conn, "SELECT `count` FROM searches WHERE id = '$sh_id'");
                $shq_data = mysqli_fetch_assoc($countQuery);
                $shq_count = floatval($shq_data['count']);
                $shq_count += 1;
                mysqli_query($this->conn, "UPDATE `searches` SET `count`= '$shq_count' WHERE id = '$sh_id'");

            } else {
                //insert data
                mysqli_query($this->conn, "INSERT INTO `searches`(`query`, `count`) VALUES ('" . $this->conn->real_escape_string($search_query) . "',1)");
            }

        }
        $search = "%{$search_query}%";

        $search_query_top = "(SELECT id,title,artist,path,plays,weekplays,'artworkPath', 'song' as type,lyrics FROM songs WHERE title LIKE ? ) 
           UNION
           (SELECT id,name,'artist','path','plays','weekplays',profilephoto, 'artist' as type,'lyrics' FROM artists  WHERE name LIKE ? ) 
           UNION
           (SELECT id,title,artist,'path','plays','weekplays',artworkPath, 'album' as type,'lyrics' FROM albums  WHERE title LIKE ? ) 
           UNION
           (SELECT id,name,'artist','path','plays','weekplays',coverurl, 'playlist' as type,'lyrics' FROM playlists WHERE name LIKE ? )"; // SQL with parameters
        $stmt = $this->conn->prepare($search_query_top);
        $stmt->bind_param("ssss", $search, $search, $search, $search);
        $stmt->execute();
        $result = $stmt->get_result(); // get the mysqli result
        $data = $result->fetch_all(MYSQLI_ASSOC);

        $total_results_got = count($data);
        $total_rows = floatval(number_format($total_results_got));
        $total_pages = ceil($total_rows / $no_of_records_per_page);


        // check if the search query returned any results

        $menuCategory = array();


        $search_query_sql = $search_query_top . " ORDER BY `title` ASC LIMIT ?,?";
        $stmt = $this->conn->prepare($search_query_sql);
        $stmt->bind_param("ssssii", $search, $search, $search, $search, $offset, $no_of_records_per_page);
        $stmt->execute();
        $result = $stmt->get_result(); // get the mysqli result
        $data = $result->fetch_all(MYSQLI_ASSOC);

        $total_results_got = count($data);


        if ($total_results_got > 0) {

            foreach ($data as $row) {
                $temp = array();

                if ($row['type'] == "song") {
                    $temp['id'] = $row['id'];
                    $song = new Song($this->conn, $row['id']);
                    $temp['artist'] = $song->getArtist()->getName();
                    $temp['artistID'] = $row['artist'];
                    $temp['title'] = $row['title'];
                    $temp['path'] = $row['path'];
                    $temp['plays'] = $row['plays'];
                    $temp['weekplays'] = $row['weekplays'];
                    $temp['artworkPath'] = $song->getAlbum()->getArtworkPath();
                    $temp['type'] = $row['type'];
                    $temp['lyrics'] = $row['lyrics'];
                }
                if ($row['type'] == "album") {
                    $temp['id'] = $row['id'];
                    $album = new Album($this->conn, $row['id']);
                    $temp['artist'] = $album->getArtist()->getName();
                    $temp['artistID'] = $row['artist'];
                    $temp['title'] = $row['title'];
                    $temp['path'] = $row['path'];
                    $temp['plays'] = $row['plays'];
                    $temp['weekplays'] = $row['weekplays'];
                    $temp['artworkPath'] = $row['artworkPath'];
                    $temp['type'] = $row['type'];
                    $temp['lyrics'] = $row['lyrics'];

                }
                if ($row['type'] == "artist") {
                    $temp['id'] = $row['id'];
                    $temp['artist'] = $row['title'];
                    $temp['artistID'] = '';
                    $temp['title'] = '';
                    $temp['path'] = $row['path'];
                    $temp['plays'] = $row['plays'];
                    $temp['weekplays'] = $row['weekplays'];
                    $temp['artworkPath'] = $row['artworkPath'];
                    $temp['type'] = $row['type'];
                    $temp['lyrics'] = $row['lyrics'];

                }
                if ($row['type'] == "playlist") {
                    $temp['id'] = $row['id'];
                    $temp['artist'] = '';
                    $temp['artistID'] = '';
                    $temp['title'] = $row['title'];
                    $temp['path'] = $row['path'];
                    $temp['plays'] = $row['plays'];
                    $temp['weekplays'] = $row['weekplays'];
                    $temp['artworkPath'] = $row['artworkPath'];
                    $temp['type'] = $row['type'];
                    $temp['lyrics'] = $row['lyrics'];

                }

                array_push($menuCategory, $temp);
            }

            $itemRecords["page"] = $page;
            $itemRecords["version"] = 1;
            $itemRecords["searchTerm"] = $search_query;
            $itemRecords["algorithm"] = $search_algorithm;
            $itemRecords["search_results"] = $menuCategory;


        } else {
            $itemRecords["page"] = $page;
            $itemRecords["version"] = 1;
            $itemRecords["searchTerm"] = $search_query;
            $itemRecords["algorithm"] = $search_algorithm;
            $itemRecords["search_results"] = [];
        }
        $itemRecords["total_pages"] = $total_pages;
        $itemRecords["total_results"] = $total_rows;


        return $itemRecords;
    }

    public function readMapSummaries(): array
    {
        $itemRecords = array();

        $itemRecords["mapFeatures"] = array();
        $itemRecords["types"] = array();

        $sql = "SELECT village.no AS id, CONCAT(village.name,' village') AS name, 
                CONCAT('The village of ', village.name, ' is located in ', village.subcounty, ', ', village.county, ' county. It has a population of ', village.population, ' people and ', village.families, ' families. The main source of water is ', village.sourceWater, ', and it is ', village.priority, ' priority. The last update was on ', village.lastupdate, '.') AS description, 
                'village' AS type, village.long, village.lat , village.iconPath AS iconPath
            FROM village
            
            UNION
            
            SELECT deepwells.id AS id, CONCAT(deepwells.name,' well') AS name, 
                CONCAT('The ', deepwells.name, ' well is located in ', village.subcounty, ', ', village.county, ' county. It has a depth of ', deepwells.depth, ' meters and a flow rate of ', deepwells.flowrate, ' L/hr. The last water testing was conducted on ', deepwells.water_testing_last_update, ', and the findings were ', deepwells.water_testing_findings, '. It was installed on ', deepwells.installationDate, ' and drilled on ', deepwells.drillDate, '. The static water level is ', deepwells.staticWaterLevel, ' meters. The last maintenance was carried out on ', deepwells.lastMaintainence, ' by ', deepwells.InChargeName, '. Contact ', deepwells.InChargeContact, ' for more information.') AS description, 
                'deepwell' AS type, deepwells.longitude, deepwells.latitude ,  deepwells.iconPath AS iconPath
            FROM deepwells 
            JOIN village ON deepwells.village_no = village.no
            
            UNION
            
            SELECT activities.id AS id, CONCAT(activities.title, ' activity') AS name,
                CONCAT('The ', activities.title, ' activity was carried out on ', activities.dateCarriedout,
                ' and completed on ', activities.dateCompleted, '. The next due date is ', activities.nextDueDate,
                '. The person in charge was ', activities.InchargePerson, ' and it took place in ', village.name, ' village.') AS description,
                'activity' AS type, activities.long, activities.lat, activities.iconPath AS iconPath
            FROM activities
            JOIN village ON activities.village_no = village.no";

        $map_features = array();
        $query = $this->conn->prepare($sql);
        $query->execute();


        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            array_push($map_features, $row);
        }
        foreach ($map_features as $no) {
            $model = new Models($this->conn);
            array_push($itemRecords["mapFeatures"], $model->mapModel($no['id'], $no['name'], $no['description'], $no['type'], $no['long'], $no['lat'],$no['iconPath']));
        }


        $temp['id'] = 1;
        $temp['name'] = "activity";
        $temp['iconpath'] = "https://xyzobide.kakebeshop.com/pakUG/Requests/icons/activity.png";
        $temp['total'] = 8;
        array_push($itemRecords["types"], $temp);

        $temp['id'] = 2;
        $temp['name'] = "deepwell";
        $temp['iconpath'] = "https://xyzobide.kakebeshop.com/pakUG/Requests/icons/water.png";
        $temp['total'] = 14;
        array_push($itemRecords["types"], $temp);

        $temp['id'] = 3;
        $temp['name'] = "village";
        $temp['iconpath'] = "https://xyzobide.kakebeshop.com/pakUG/Requests/icons/village.png";
        $temp['total'] = 20;
        array_push($itemRecords["types"], $temp);

        return $itemRecords;

    }

    function readSelectedVillage(): array
    {
        $village_page = (isset($_GET['page']) && $_GET['page']) ? htmlspecialchars(strip_tags($_GET["page"])) : '1';
        $village_id = (isset($_GET['villageID']) && $_GET['villageID']) ? htmlspecialchars(strip_tags($_GET["villageID"])) : '1';

        $page = floatval($village_page);
        $no_of_records_per_page = 10;
        $offset = ($page - 1) * $no_of_records_per_page;


        $query = $this->conn->prepare("SELECT COUNT(no) as count FROM village LIMIT 1");
        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);
        $total_rows = floatval($row['count']);
        $total_pages = ceil($total_rows / $no_of_records_per_page);


        $villageArray = array();
        $itemRecords = array();

        $model = new Models($this->conn);

        if ($page == 1) {
            array_push($villageArray, $model->villageModel($village_id));
//            array_push($villageArray, $model->villageDetails($village_id));
        }

        $other_villages = array();
        $query = $this->conn->prepare("SELECT no FROM village WHERE no != :village_id ORDER BY `village`.`no` DESC LIMIT :offset, :no_of_records_per_page");
        $query->bindParam(":village_id", $village_id, PDO::PARAM_INT);
        $query->bindParam(":no_of_records_per_page", $no_of_records_per_page, PDO::PARAM_INT);
        $query->bindParam(":offset", $offset, PDO::PARAM_INT);
        $query->execute();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            array_push($other_villages, $row['no']);
        }
        foreach ($other_villages as $no) {
            array_push($villageArray, $model->villageModel($no));
        }

        $itemRecords["version"] = $this->version;
        $itemRecords["page"] = $page;
        $itemRecords["Village"] = $villageArray;
        $itemRecords["total_pages"] = $total_pages;
        $itemRecords["total_results"] = $total_rows;

        return $itemRecords;
    }


    function readSingleVillage(): array
    {
        $village_id = (isset($_GET['villageID']) && $_GET['villageID']) ? htmlspecialchars(strip_tags($_GET["villageID"])) : '1';


        $itemRecords = array();
        $singleVillageCategory = array();

        $model = new Models($this->conn);
        $village = new Village($this->conn, $village_id);


//        Village Details
        array_push($singleVillageCategory, $model->villageModel($village));

        //        Village Details
        $slider_temps = array();
        $slider_temps['heading'] = "Village Activities";
        $slider_temps['Activities'] = $village->getActivities();
        array_push($singleVillageCategory, $slider_temps);

        //        Village Details
        $slider_temps = array();
        $slider_temps['heading'] = "Village Needs";
        $slider_temps['Needs'] = $village->getNeeds();
        array_push($singleVillageCategory, $slider_temps);

        //        Village Leaders
        $slider_temps = array();
        $slider_temps['heading'] = "Village Leaders";
        $slider_temps['Leaders'] = $village->getLeaders();
        array_push($singleVillageCategory, $slider_temps);

        //        Village Deep Wells
        $slider_temps = array();
        $slider_temps['heading'] = "Village Deep Wells";
        $slider_temps['DeepWell'] = $village->getDeepWells();
        array_push($singleVillageCategory, $slider_temps);

        //Village Outreach
        $slider_temps = array();
        $slider_temps['heading'] = "Village Outreach";
        $slider_temps['Outreach'] = $village->getOutreach();
        array_push($singleVillageCategory, $slider_temps);





        $itemRecords["version"] = $this->version;
        $itemRecords["page"] = 1;
        $itemRecords["SingleVillage"] = $singleVillageCategory;
        $itemRecords["total_pages"] = 1;
        $itemRecords["total_results"] = 1;

        return $itemRecords;
    }




    public function saveAuthUser($data): array
    {

        //getting the values
        $m_id = "mw" . $data->id;
        $m_username = $data->username;
        $m_full_name = $data->full_name;
        $m_email = $data->email;
        $m_phone = $data->phone;
        $m_password = md5($data->id);
        $m_signUpDate = date('Y-m-d H:i:s', time());
        $m_profilePic = $data->profilePic;
        $m_status = "registered";
        $m_mwRole = "mwuser";
        $m_accountOrigin = "googleAuth";

        $stmt = $this->conn->prepare("SELECT `id`, `username`, `firstName`, `email`,`phone`,`password`, `signUpDate`, `profilePic`, `status`, `mwRole` FROM users WHERE password = ? AND (email = ? OR id = ?)");
        $stmt->bind_param("sss", $m_password, $m_email, $m_id);
        $stmt->execute();
        $stmt->bind_result($m_id, $m_username, $m_full_name, $m_email, $m_phone, $m_password, $m_signUpDate, $m_profilePic, $m_status, $m_mwRole);
        $stmt->store_result();
        $stmt->fetch();
        $response = array();

        //if the user already exist in the database
        if ($stmt->num_rows > 0) {
            $response['id'] = $m_id;
            $response['username'] = $m_username;
            $response['full_name'] = $m_full_name;
            $response['email'] = $m_email;
            $response['phone'] = $m_phone;
            $response['password'] = $m_password;
            $response['signUpDate'] = $m_signUpDate;
            $response['profilePic'] = $m_profilePic;
            $response['status'] = $m_status;
            $response['mwRole'] = $m_mwRole;
            $response['error'] = false;
            $response['message'] = 'User already registered, Here are details';
            $stmt->close();
        } else {

            //if user is new creating an insert query
            $stmt = $this->conn->prepare("INSERT INTO users (`id`,`username`,`firstName`,`email`,`phone`,`Password`,`signUpDate`,`profilePic`,`status`,`accountOrigin`) VALUES (?, ?, ?, ?,?, ?, ?, ?, ?,?)");
            $stmt->bind_param("ssssssssss", $m_id, $m_username, $m_full_name, $m_email, $m_phone, $m_password, $m_signUpDate, $m_profilePic, $m_status, $m_accountOrigin);

            //if the user is successfully added to the database
            if ($stmt->execute()) {

                //fetching the user back
                $stmt = $this->conn->prepare("SELECT `id`, `username`, `firstName`, `email`,`phone`,`password`, `signUpDate`, `profilePic`, `status`, `mwRole` FROM users WHERE email = ? AND password = ?");
                $stmt->bind_param("ss", $m_email, $m_password);
                $stmt->execute();
                $stmt->bind_result($m_id, $m_username, $m_full_name, $m_email, $m_phone, $m_password, $m_signUpDate, $m_profilePic, $m_status, $m_mwRole);
                $stmt->store_result();
                $stmt->fetch();

                //if the user already exist in the database
                if ($stmt->num_rows > 0) {
                    $response['id'] = $m_id;
                    $response['username'] = $m_username;
                    $response['full_name'] = $m_full_name;
                    $response['email'] = $m_email;
                    $response['phone'] = $m_phone;
                    $response['password'] = $m_password;
                    $response['signUpDate'] = $m_signUpDate;
                    $response['profilePic'] = $m_profilePic;
                    $response['status'] = $m_status;
                    $response['mwRole'] = $m_mwRole;
                    $response['error'] = false;
                    $response['message'] = 'Registration Complete';
                    $stmt->close();
                } else {
                    $response['id'] = null;
                    $response['username'] = null;
                    $response['full_name'] = null;
                    $response['email'] = null;
                    $response['phone'] = null;
                    $response['password'] = null;
                    $response['signUpDate'] = null;
                    $response['profilePic'] = null;
                    $response['status'] = null;
                    $response['mwRole'] = null;
                    $response['error'] = true;
                    $response['message'] = 'User Registration Failed';
                }
            }
        }

        return $response;
    }

    public function Versioning()
    {
        $itemRecords = array();
        $itemRecords["version"] = $this->version; // build number should match
        $itemRecords["update"] = true; // update dialog dismissable
        $itemRecords["message"] = "We have new updates for you";
        return $itemRecords;
    }


}
