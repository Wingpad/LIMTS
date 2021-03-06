<?php
	// Import the "Grab Bag"
  require("../common.php");

	// Open an (OO) MySQL Connection
	$conn = new mysqli($GLOBALS["dbhost"], $GLOBALS["dbuser"], $GLOBALS["dbpass"], $GLOBALS["dbname"]);

	// Check connection
  if ($conn->connect_error || !session_start()) {
    die("{\"response\": \"Connection failed: " . $conn->connect_error + "\"}");
	}

	$userId = $_SESSION[USER_ID];

	// Get all of the "Parameters"
	$building = $_GET["building"];
	$number = $_GET["number"];
	$capacity = $_GET["capacity"];
	$handicap_accessible = json_decode($_GET["handicapAccessible"]);
	$database_id = $_GET["id"];

	// Check to make sure the required information is present
	if (!($building && $number && $capacity)) {
		die("{\"response\": \"You must specify the building, number and capacity!\"}");
	}

  if (!$handicap_accessible) {
    $handicap_accessible = "N";
  }

  if (!$database_id) {
  	// Check to see if the Room already exists in the database
  	$result = $conn->query("SELECT *
  							FROM `Room`
                WHERE `UserID` = $userId
  							AND `Building`='$building'
                AND `Number`='$number'");
  	if ($result->num_rows > 0) {
  		die("{\"response\": \"Room already exists in database\"}");
  	}
  	$result->close();

  	// Everything seems ok at this point, so just add the room
  	$result = $conn->query("INSERT INTO `Room`(`Building`, `Number`, `Capacity`, `HandicapAccessible`, `UserID`)
  							            VALUES('$building', '$number', '$capacity', '$handicap_accessible', '$userId')");
  } else {
    $query = "UPDATE `Room`
              SET Building='$building', `Number`='$number', Capacity='$capacity', HandicapAccessible='$handicap_accessible'
              WHERE id=$database_id";
    $result = $conn->query($query);
  }

	if (!$result) {
		die("{\"response\": \"Could not insert Room!\"}");
	}

	// Give a success response
	echo "{\"response\": \"Success\"}";

	// Finally, close the connection
	$conn->close();
?>
