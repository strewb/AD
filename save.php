<?php
    //$host = 'mysql.metropolia.fi';
    $host = 'mysql.metropolia.fi';
    $dbname = 'lilyg'; // your username
    $user = 'lilyg'; // your username
    $pass = 'mahletab'; // your database password
	
   // TODO: get the data from the form by using $_POST
   $data = array();
   $data['name'] = $_POST['name'];
   $data['description'] = $_POST['desc'];
   $data['email'] = $_POST['email'];
   $data['phone'] = $_POST['cell'];
   $date = $_POST['date'];
   $time = $_POST['time'];
   // this is how you convert the date from the form to SQL formatted date:
   // date ("Y-m-d H:i:s", strtotime(dataFromDateField.' '.dataFromTimeField));
   $data['sqlDate'] = date ("Y-m-d H:i:s", strtotime($date.' '.$time));;
   
   
// this part was in dbConnect.php in last period:
try {
	$DBH = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
	$DBH->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$DBH->query("SET NAMES 'UTF8';");
}catch(PDOException $e) {
	file_put_contents('log.txt', $e->getMessage(), FILE_APPEND);
	$result = array(
		"status"	=>	"error",
		"message"	=>	"Could not connect to database!"
	);
	echo json_encode($result);
	exit;
}

    
try {
	// TODO: insert the data from the form to database table 'calendar'
	$STH = $DBH->prepare("INSERT INTO calendar (eName, eDescription, pEmail, pPhone, eDate) VALUES
	(:name, :description, :email, :phone, :sqlDate);");
	$STH->execute($data);
	$result = array(
		"status"	=>	"success",
		"message"	=>	"Data insert successful!"
	);
	echo json_encode($result);
} catch (PDOException $e) {
	file_put_contents('log.txt', $e->getMessage()."\n\r", FILE_APPEND); // remember to set the permissions so that log.txt can be created
	$result = array(
		"status"	=>	"error",
		"message"	=>	"Something went wrong!"
	);
	echo json_encode($result);
}
exit;
?>
