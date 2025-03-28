<?php
            include("connect.php");
            if ($_SERVER["REQUEST_METHOD"]=="GET")
    try	{
        $dataSourceName="mysql:host=$dbHost;dbname=$dbDatabase;";		//compose data source name as a string
        $pdo=new PDO($dataSourceName,$dbUser,$dbPassword);	        	//create PDO object
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);	//tell PDO to report errors by exceptions
    
        $sql = "SELECT r.reservation_id, u.username, i.title, r.reservation_date 
                FROM reservations r 
                INNER JOIN users u 
                ON r.user_id = u.user_id 
                INNER JOIN items i 
                ON r.item_id = i.item_id";
        

        $keyword=$_GET["keyword"];      //look for keyword parameter in GET request
        if (isSet($keyword))
            $sql=$sql." where u.username like '%".$keyword."%'"; //append filter to SQL query

        $result=$pdo->query($sql);	//execute SQL query
        header("Content-type: application/json");  //set content-type to JSON
        http_response_code(200);        //OK for retrieval

        foreach ($result as $row)       //iterate through rows in result
            {
                $toReturn[]=$row;       //append to PHP array
            }
        echo json_encode($toReturn);      //return array as JSON-formatted string

        $pdo=null;	//Destroy PDO object by removing all references to it
                    //This will close the connection to MySQL.
        } catch (PDOException $exception)
        {
            /*
                In case of any exception, use PDOException::getMessage()
                to get the error as a string and output it to the web page.
            */
            http_response_code(500);
            echo "<div class='error'>".$exception->getMessage()."</div>";
        } //end then part for GET method
else    {
        http_response_code(400);    //does not support other methods
        } //end else
?>

