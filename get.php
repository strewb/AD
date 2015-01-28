<div id="calendar">
<?php
    //$host = 'mysql.metropolia.fi';
    $host = 'mysql.metropolia.fi';
    $dbname = 'myusername'; // your username
    $user = 'myusername'; // your username
    $pass = 'password'; // your database password
    
    try {
            $DBH = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
            $DBH->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $DBH->query("SET NAMES 'UTF8';");
        
        } catch(PDOException $e) {
            echo "Could not connect to database.";
            file_put_contents('log.txt', $e->getMessage(), FILE_APPEND);
        }

try {
    $eventList = array();
    $sql = "SELECT * FROM calendar ORDER BY eDate ASC";
    $STH = $DBH->query($sql);
    $STH->setFetchMode(PDO::FETCH_OBJ);
    while ($row = $STH->fetch()){
		// create standard object which complies with this: http://fullcalendar.io/docs/event_data/Event_Object/
		$event = new stdClass();
    	$event->start = $row->eDate;
		
		// title
		$event->title = $row->eName;
		
		// description
		$event->description = $row->eDescription;
		
		// email
		$event->email = $row->pEmail;
		
		// phone
		$event->phone = $row->pPhone;
		
		$eventList[] = $event;
    }
	$eventsJSON = json_encode($eventList);
 } catch (PDOException $e) {
	echo 'Something went wrong';
	file_put_contents('log.txt', $e->getMessage()."\n\r", FILE_APPEND); // remember to set the permissions so that log.txt can be created
}      

?>
</div>
<div id="eventContent" title="Event Details" style="display:none;">  
    Start: <span id="startTime"></span><br>
    Email: <span id="eventEmail"></span><br>
    Cell: <span id="eventCell"></span><br>
    <p id="eventInfo"></p>
</div>
<script>
// JSON made in PHP is saved in JavaScript
var jsonEvents = <?php echo $eventsJSON; ?>;
console.log(jsonEvents);
$('#calendar').fullCalendar({
    events: jsonEvents,
	editable: true,
	eventLimit: true, // allow "more" link when too many events
	eventClick:  function(event, jsEvent, view) {
        //set the values and open the modal
        $("#startTime").html(moment(event.start).format('MMM Do h:mm A'));
        $("#eventEmail").html(event.email);
        $("#eventCell").html(event.phone);
        $("#eventInfo").html(event.description);
        $("#eventContent").dialog({ modal: true, title: event.title });
    }
	/*
    eventRender: function(event, element) {
        element.qtip({
            content: event.description
        });
    }
	*/
});
// TODO: use jQuery FullCalendar plugin to display the events in a proper calendar
// calendar has to have at least month, week and day views
// timeformat is 24-hour clock
// when an event is clicked all the event details (jsonEvents) are displayed	
</script>
