<?php

class Database {
        // ====================================================================================================// 
        // ! Connect to MySQLi to allow escaping of inputs, and form processing.                               //
        // ====================================================================================================//
        private $dbHostname = "localhost";
        private $dbUsername = "UserGiftForm";
        private $dbPassword = "Nev3rUseTh1sP4ssw0rdEverAgain!";
        private $dbDatabaseName = "charcoalRSS";
        private $mysqli;
    
        public function connectToDatabase() {
            $this->mysqli = new mysqli($this->dbHostname, $this->dbUsername, $this->dbPassword, $this->dbDatabaseName);
            if($this->mysqli->connect_errno) {
                printf("Connection failed: %s\n", $mysqli->connect_error());
                exit();
            } else {
                return $this->mysqli;
            }
        }//end connectToDatabase()

        public function addFeedToDatabase($text, $title, $type, $xmlUrl, $htmlUrl) {
            //Step 1: Prepare our statement
            if(!($stmt = $this->mysqli->prepare("INSERT INTO `" . $this->dbDatabaseName . "`.`rssFeeds` (`text`, `title`, `type`, `xmlUrl`, `htmlUrl`) VALUES (?, ?, ?, ?, ?)"))) {
                //Our prepare failed, let the user know.
                echo "Prepare failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
                die();
            }
            //Step 2: Bind our parameters
            // i - integer | d - double | s - string | b - blob
            if(!$stmt->bind_param("sssss", $text, $title, $type, $xmlUrl, $htmlUrl)) {
                echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
                die();
            }
            //Step 3: Execute
            if(!$stmt->execute()) {
                echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                die();
            }
            //Step 4: Close the statement
            $stmt->close();
        }//end addFeedToDatabase

        public function returnFeedList() {
            if(!$this->mysqli) {
                $this->connectToDatabase();
            }
            if(!($result = mysqli_query($this->mysqli, "SELECT * FROM `rssFeeds`", MYSQLI_USE_RESULT))) {
                echo "No luck getting a result from the database :(";
            } else {
                return $result;
            }
        }//end returnFeedList()

        public function closeDatabaseConnection() {
            if(!$this->mysqli->close()) {
                echo "There was a problem closing the database connection.";
            }
        }//end closeDatabaseConnection()
    }

?>