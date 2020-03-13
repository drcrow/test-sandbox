<?php
header("Content-type: application/json; charset=utf-8");

//$link = mysqli_connect("mysql", "root", "tiger", null);


$mysqli = new mysqli('mysql', 'root', 'tiger', 'docker');

if ($mysqli->connect_errno) {
    http_response_code(500); 
    $res['error'] = "Database connection error";
    $res['db_errno'] = $mysqli->connect_errno;
    $res['db_error'] = $mysqli->connect_error;
    echo json_encode($res);
    die();
}

$payload = file_get_contents('php://input');
$payload = json_decode($payload);

switch($_SERVER['REQUEST_METHOD']){

    case 'GET':
        $data = $mysqli->query('SELECT * FROM task WHERE is_deleted=0');
        while ($row = $data->fetch_assoc()) {
            $res[] = $row;
        }
        http_response_code(200); 
        echo json_encode(array('tasks_list' => $res));
        break;

    case 'POST':
        $name = trim(strip_tags($payload->name));

        if($name != ''){
            $mysqli->query('INSERT INTO task (task, created) VALUES ("'.$name.'", now())');
            http_response_code(200);
            echo json_encode($payload);
        }else{
            http_response_code(500);
        }

        
        break;

    case 'DELETE':
        
        $mysqli->query('UPDATE task SET is_deleted=1 WHERE id ='.(int)$_GET['id']);
        http_response_code(200);
        echo json_encode($_GET);
        break;

    default:
        http_response_code(405); 
        echo json_encode(array('error' => 'Method '.$_SERVER['REQUEST_METHOD'].' not supported'));

}