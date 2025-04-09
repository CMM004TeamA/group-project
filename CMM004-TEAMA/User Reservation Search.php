<?php
            include("connect.php");
            if ($_SERVER["REQUEST_METHOD"]=="GET")
    try	{    
        $sql = "SELECT r.reservation_id, u.username, i.title, r.reservation_date, s.status_name 
                FROM reservations r 
                INNER JOIN users u 
                ON r.user_id = u.user_id 
                INNER JOIN items i 
                ON r.item_id = i.item_id
                INNER JOIN statuses s
                ON i.status_id = s.status_id";
        

        $keyword = isset($_GET["keyword"]) ? trim($_GET["keyword"]) : "";
        if (!empty($keyword)) {
            $sql .= " WHERE u.username LIKE :keyword";
            $stmt = $conn->prepare($sql);
            $stmt->execute(['keyword' => '%' . $keyword . '%']);
        } else {
            $stmt = $conn->query($sql);
        }

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header("Content-type: application/json");
        http_response_code(200);
        echo json_encode($result);

        $conn = null;
    } catch (PDOException $exception) {
        http_response_code(500);
        echo json_encode(["error" => $exception->getMessage()]);
    } else {
    http_response_code(400);
    echo json_encode(["error" => "Only GET requests are supported."]);
}

?>