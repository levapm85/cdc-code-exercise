<?php
$db_server = "localhost";
$db_user = "root";
$db_pwd = "";
$db_schema = "php-test";
$db_conn = null;
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
function get_url() {
    if (isset($_SERVER['REQUEST_URI'])) {
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $url = rtrim($url, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        return $url;
    }
}
function db_connect() {
    global $db_server, $db_user, $db_pwd, $db_schema, $db_conn;
    $db_conn = new mysqli($db_server, $db_user, $db_pwd, $db_schema);
    // Check connection
    if ($db_conn->connect_error) {
        die("Connection failed: " . $db_conn->connect_error);
    }
}
function db_close() {
    global $db_conn;
    if($db_conn) {
        $db_conn->close();
    }
}

function get_message($msgId) {
    global $db_conn;
    db_connect();
    if (isset($msgId)) {
        $sql = "SELECT `id`, `title`, `from`, `to`, `body`, `created`, `updated` FROM messages WHERE `id` = $msgId";
        $result = $db_conn->query($sql);

        if($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $data = [
                'title' => $row["title"],
                'from' => $row["from"],
                'to' => $row["to"],
                'body' => $row["body"],
                'created' => $row["created"],
                'updated' => $row["updated"]
            ];
            db_close();
            return ["success" => true, "data" => $data];
        }
    }
    db_close();
    return [ "success" => false];
}
function create_message() {
    global $db_conn;
    extract($_POST);
    db_connect();

    if (isset($title) && isset($from) && isset($to) && isset($body)) {
        $sql = "SELECT id FROM messages WHERE title = '" . $title . "'";
        $result = $db_conn->query($sql);
        if($result->num_rows > 0) {
            db_close();
            return ["success" => false, $msg = "Title should be unique"];
        }
        $dt = new DateTime();
        $dt->setTimezone(new DateTimeZone('Europe/Moscow'));
        $created = $dt->format('Y-m-d h:i:s');
        $sql = "INSERT INTO messages (`title`, `from`, `to`, `body`, `created`, `updated`)
                       VALUES ('$title', $from, $to, '$body', '$created', '$created')";
        if ($db_conn->query($sql) == true) {
            $new_id = $db_conn->insert_id;
            db_close();
            return ["success" => true, "data" => $new_id];
        }
    }
    db_close();
    return [ "success" => false, $msg = "Invalid Title"];
}
function update_message() {
    global $db_conn;
    extract($_POST);
    db_connect();

    if (isset($body) && isset($id)) {
        $dt = new DateTime();
        $dt->setTimezone(new DateTimeZone('Europe/Moscow'));
        $updated = $dt->format('Y-m-d h:i:s');
        $sql = "UPDATE messages SET `body` = '$body', `updated` =  '$updated' WHERE `id` = $id";
        if ($db_conn->query($sql) == true) {
            db_close();
            return ["success" => true];
        }
    }
    db_close();
    return [ "success" => false];
}
function delete_message($msgId) {
    global $db_conn;
    db_connect();
    if (isset($msgId)) {
        $sql = "DELETE FROM messages WHERE `id` = $msgId";
        if ($db_conn->query($sql) == true) {
            db_close();
            return ["success" => true];
        }
    }
    db_close();
    return [ "success" => false];
}
$url = get_url();
if( strpos('/api/message', $url) !== false ){
    switch($_SERVER['REQUEST_METHOD']){
        case "GET":
            if(isset($_GET['id']))
                $result = get_message($_GET['id']);
            break;
        case "POST":
            $result = create_message();
            break;
        case "PUT":
            $result = update_message();
            break;
        case "DELETE":
            if(isset($_GET['id']))
                $result = delete_message($_GET['id']);
            break;
    }
    echo json_encode($result);
}else{
    echo 'Invalid REST API';
}
die();
