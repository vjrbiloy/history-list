<?php

function verifyUser($realm = "Secured Area")
{
    // Fetch some users from the database or a config file
    $allowedUsers = array('admin' => 'admin', 'user' => 'pass');

	if( ! empty($_SERVER['PHP_AUTH_DIGEST']))
	{
		// Decode the data the client gave us
		$default = array('nounce', 'nc', 'cnounce', 'qop', 'username', 'uri', 'response');
		preg_match_all('~(\w+)="?([^",]+)"?~', $_SERVER['PHP_AUTH_DIGEST'], $matches);
		$data = array_combine($matches[1] + $default, $matches[2]);

		// Generate the valid response
		$A1 = md5($data['username'] . ':' . $realm . ':' . $allowedUsers[$data['username']]);
		$A2 = md5(getenv('REQUEST_METHOD').':'.$data['uri']);
		$valid_response = md5($A1.':'.$data['nonce'].':'.$data['nc'].':'.$data['cnonce'].':'.$data['qop'].':'.$A2);

		// Compare with what was sent
		if($data['response'] === $valid_response)
		{
			return TRUE;
		}
	}

	// Failed, or haven't been prompted yet
	header('HTTP/1.1 401 Unauthorized');
	header('WWW-Authenticate: Digest realm="' . $realm.
		'",qop="auth",nonce="' . uniqid() . '",opaque="' . md5($realm) . '"');
	exit;
}

function clearRecords($tableName = 'users'){
	$sql = 'TRUNCATE TABLE '.$tableName;

	$dbConnection = dbConnection();
	$result = $dbConnection->query($sql);

	if ($result === TRUE) {
		echo "Table {$tableName} truncated successfully<br>";
	}
}

function seedUsers($length = 3000){
	$dbConnection = dbConnection();

	$stmt = $dbConnection->prepare("INSERT INTO users (`username`, `active`) VALUES (?, ?)");
	$stmt->bind_param('ss', $username, $active);

	for($i = 0; $i < $length; $i++){
		$username = "user".($i+1);
		$active = $i % 10 == 0 ? 0 : 1;
		
		$stmt->execute();
	}

	$stmt->close();
	
	echo "Done seeding users.</br>";
}

function seedHistories($length = 3000000, $usersLenght = 3000){
    $dbConnection = dbConnection();
	$countries = getCountries();
	$datetimes = getDateTimeList();

	$stmt = $dbConnection->prepare("INSERT INTO histories (`user_id`, `amount`, `country`, `active`, `datetime`) VALUES (?, ?, ?, ?, ?)");
	$stmt->bind_param('idsis', $userId, $amount, $country, $active, $datetime);

	for($i = 0; $i < $length; $i++){
		$userId = rand(1, $usersLenght);
		$amount = rand(100,3000).'.'.rand(0,99);
		$country = $countries[rand(0,29)];
		$active = $i % 10 == 0 ? 0 : 1;
		$datetime = $datetimes[rand(0, count($datetimes)-1)].' '.generateRandomTime();
		
		$stmt->execute();
	}

	$stmt->close();

	echo "Done seeding histories.</br>";
}

function getHistories($country = "all", $datetimeFrom, $datetimeTo){
    $sql = "SELECT h.country, 
		SUM(IF(`h`.`active` = 1, h.amount, 0)) AS `total_active_user_amount`, 
		MAX(`h`.`datetime`) AS `last_history_datetime`, 
		SUM(IF(`h`.`active` = 1, 1, 0)) AS `number_of_unique_users`
		FROM histories AS h
		WHERE h.`active` = 1";

	if($country !== "all"){
		$sql .= " AND `h`.`country` = ?";
	}

	$sql .= " AND `h`.`datetime` BETWEEN ? AND ?";
	$sql .= " GROUP BY h.`country`";
	$sql .= " ORDER BY h.`country`";
	
	$dbConnection = dbConnection();
	$stmt = $dbConnection->prepare($sql);

	if($country !== "all"){
		$stmt->bind_param('sss', $country, $datetimeFrom, $datetimeTo);
	}else{
		$stmt->bind_param('ss', $datetimeFrom, $datetimeTo);
	}

	$stmt->execute();

	$result = $stmt->get_result();
	$rows = $result->fetch_all(MYSQLI_ASSOC);

	$stmt->close();
	$dbConnection->close();

	return $rows;
}

function getHistoriesByCountryAndDate($country = "Malaysia", $username = "", $datetimeFrom, $datetimeTo){
    $sql = "SELECT u.user_id, u.username,
		SUM(h.amount) AS `total_user_amount`,
		MAX(`h`.`datetime`) AS `last_history_datetime`
		FROM users AS u
		LEFT JOIN histories AS h ON u.user_id = h.user_id AND h.active = 1
		WHERE u.`active` = 1";

	$sql .= " AND `u`.`username` LIKE ?";
	$sql .= " AND `h`.`country` = ?";
	$sql .= " AND `h`.`datetime` BETWEEN ? AND ?";
	$sql .= " GROUP BY u.`user_id`";
	$sql .= " ORDER BY u.`user_id`";
	
	$dbConnection = dbConnection();
	$stmt = $dbConnection->prepare($sql);
	$stmt->bind_param('ssss', $username, $country, $datetimeFrom, $datetimeTo);
	$stmt->execute();

	$result = $stmt->get_result();
	$rows = $result->fetch_all(MYSQLI_ASSOC);

	$stmt->close();
	$dbConnection->close();

	return $rows;
}

function dbConnection(){
	$host = 'localhost';
	$username = 'root';
	$password = '';
	$dbname = 'technical_test';

	// Create a connection
	$conn = new mysqli($host, $username, $password, $dbname);

	// Check the connection
	if ($conn->connect_error) {
		echo "Connection failed: " . $conn->connect_error; exit;
	}

	return $conn;
}

function getCountries(){
	$countries = [
		"United States",
		"Canada",
		"Mexico",
		"Brazil",
		"Argentina",
		"United Kingdom",
		"Germany",
		"France",
		"Italy",
		"Spain",
		"Russia",
		"China",
		"Japan",
		"India",
		"South Korea",
		"Australia",
		"New Zealand",
		"South Africa",
		"Egypt",
		"Nigeria",
		"Kenya",
		"Saudi Arabia",
		"Turkey",
		"Israel",
		"United Arab Emirates",
		"Thailand",
		"Vietnam",
		"Indonesia",
		"Malaysia",
		"Singapore"
	];

	sort($countries);

	return $countries;
}

function getDateTimeList() {
    $start = new DateTime('2023-05-01');
    $end = new DateTime('2024-07-30'); 

	$datetimes = array();
    while ($start < $end) {
        $datetimes[] = $start->format('Y-m-d');
        $start->modify('+1 day');
    }

	return $datetimes;
}

function generateRandomTime() {
    $hours = str_pad(rand(0, 23), 2, '0', STR_PAD_LEFT);
    $minutes = str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT);
    $seconds = str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT);

    return "{$hours}:{$minutes}:{$seconds}";
}