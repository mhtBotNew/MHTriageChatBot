<!DOCTYPE html>
<html lang="en">
	<head>
	<meta charset="utf-8">
	<title>Database Access for Mental Health Triage Chatbot</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	</head>

<header>
<h1>Database Access for Mental Health Triage Chatbot</h1>
</header>

<body>
<p>
This page provides access to the database supporting the mental health triage chatbot.
</p>

<?php



//https://docs.microsoft.com/en-us/azure/sql-database/sql-database-connect-query-php
// Connection to Azure Database
$serverName = "mhtbotdb.database.windows.net";
$connectionOptions = array(
	"Database" => "mhtBotDB",
	"Uid" => "mng17@mhtbotdb",
	"PWD" => "1PlaneFifth"
);


// Establishes the connection
$conn = sqlsrv_connect($serverName, $connectionOptions);

/*
// Read Query
$tsql = "SELECT * FROM UserResponsesNew;";
$getResults = sqlsrv_query($conn, $tsql);
echo("Reading data from table" . PHP_EOL);

if($getResults == FALSE)
	die(FormatErrors(sqlsrv_errors()));
	//echo (sqlsrv_errors());
while($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)){
	echo($row['UserResponse'] . PHP_EOL);
}
sqlsrv_free_stmt($getResults);

function FormatErrors( $errors )
{
    /* Display errors. 
    echo "Error information: ";

    foreach ( $errors as $error )
    {
        echo "SQLSTATE: ".$error['SQLSTATE']."";
        echo "Code: ".$error['code']."";
        echo "Message: ".$error['message']."";
    }
}*/
?>


//==========
//FUNCTIONS
//==========

<h2>Test values</h2>
<?php
$userID = 3;
$username = 'Sam';
?>

<?php

function FormatErrors( $errors )
{
    //Display errors. 
    echo "Error information: ";

    foreach ( $errors as $error )
    {
        echo "SQLSTATE: ".$error['SQLSTATE']."";
        echo "Code: ".$error['code']."";
        echo "Message: ".$error['message']."";
    }
}

function getAllUsers($conn){
	$usernameArr = [];
	$tsql = "SELECT UserName FROM Users;";
	$getResults = sqlsrv_query($conn, $tsql);
	if($getResults == FALSE)
		die("Error in executing getAllUsers() query <br>");
	
	echo "getAllUsers() query successfully executed <br>";
	while($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)){
		array_push($usernameArr, $row['UserName']);
	}
	return $usernameArr;
}

function getUserID($conn, $username){
	$userID = 0;
	$tsql = "SELECT UserID FROM Users WHERE UserName = '" . $username . "';";
	$getResults = sqlsrv_query($conn, $tsql);
	if($getResults == FALSE){
		if( ($errors = sqlsrv_errors() ) != null) {
        foreach( $errors as $error ) {
            echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
            echo "code: ".$error[ 'code']."<br />";
            echo "message: ".$error[ 'message']."<br />";
        }
		die("Error in executing getUserID() query");
		}
	}

	echo "getUserID() query successfully executed <br>";
	$userID = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)['UserID'];
	return $userID;
}

function getUserResponses($conn, $userID){
	$userResponses = [];
	$tsql = "SELECT u.UserResponse FROM UserResponsesNew u JOIN UserQuestionIDs q ON u.QuestionID = q.QuestionID WHERE UserID = $userID;";

	$getResults = sqlsrv_query($conn, $tsql);  
	if($getResults == False){
		if( ($errors = sqlsrv_errors() ) != null) {
	        foreach( $errors as $error ) {
	            echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
	            echo "code: ".$error[ 'code']."<br />";
	            echo "message: ".$error[ 'message']."<br />";
			}
		die("Error in executing getUserResponses() query");
		}
	}
	echo "getUserResponses() query successfully executed <br>";
	while($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)){
		echo $row['UserResponse'];
		array_push($userResponses, $row['UserResponse']);
	}
	return $userResponses;
}
?>



<h2> Testing getUserResponses()</h2>

<?php
$userResponsesArr = getUserResponses($conn, 3);
foreach($userResponsesArr as $value){
	echo "$value <br>";
}
print_r($userResponsesArr);
?>


<h2> Testing php getAllUsers() function</h2>
<?php

$usernameArr= getAllUsers($conn);

foreach($usernameArr as $value){
	echo "$value <br>";
}
?>


<h2>Testing php getUserID() function</h2>

<?php
echo "UserID of $username is " . getUserID($conn, $username);
?>

<h2> Page </h2>

<button type="button">Change Content</button>

<script>
$('button').on('click', function(e){
	e.preventDefault();
	$.ajax({
		url: 'query.php',
		type: 'GET',
		success: function(data){
			console.log(data);
		}
	});
});

</script>


</body>

</html>