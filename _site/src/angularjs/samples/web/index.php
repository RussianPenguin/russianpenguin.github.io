<?php 

// all defines here
define("APP_ROOT", dirname(dirname(__FILE__)));
define("VENDOR_ROOT", APP_ROOT . DIRECTORY_SEPARATOR . "vendor");
define("DATABASE_ROOT", APP_ROOT . DIRECTORY_SEPARATOR . "db");
define("DATABASE", DATABASE_ROOT . DIRECTORY_SEPARATOR . "lectures.db");

// only one require for nice work
require_once(VENDOR_ROOT . DIRECTORY_SEPARATOR . "autoload.php");

function arr_get($array, $name, $default = null) {
    if (isset($array[$name])) {
	return htmlspecialchars($array[$name]);
    } else {
	return $default;
    }
}

// attach to database
$dbh = new PDO("sqlite:" . DATABASE);

$app = new \Slim\Slim();

$app->get('/lecture/', function () use ($dbh) {
    $title = \Slim\Slim::getInstance()->request()->params('title');
    if ($title) {
	$select = $dbh->prepare("select * from lectures where title like :title");
	$select->bindValue(':title', "%{$title}%", PDO::PARAM_STR);
	$rslt = $select->execute();
        if ($rslt) {
	    echo json_encode($select->fetchAll(PDO::FETCH_ASSOC));
	}
    } else {
        $select = $dbh->prepare("select * from lectures");
	$rslt = $select->execute();
        if ($rslt) {
	    echo json_encode($select->fetchAll(PDO::FETCH_ASSOC));
	}
    }
});

$app->get('/lecture/:id', function ($id) use ($dbh) {
    $select = $dbh->prepare("select * from lectures where id = :id");
    $select->bindValue(':id', $id, PDO::PARAM_INT);
    $rslt = $select->execute();
    if ($rslt) {
	echo json_encode($select->fetch(PDO::FETCH_ASSOC));
    }
});

$app->post('/lecture', function() use ($dbh) {
    $rawParams = \Slim\Slim::getInstance()->request()->getBody();
    $params = json_decode($rawParams);
    $insert = $dbh->prepare("insert into lectures (title, description) values (:title, :description)");
    $insert->bindValue(':title', $params->title, PDO::PARAM_STR);
    $insert->bindValue(':description', $params->description, PDO::PARAM_STR);
    $insert->execute();
});

$app->post('/lecture/:id', function($id) use ($dbh) {
    $rawParams = \Slim\Slim::getInstance()->request()->getBody();
    $params = json_decode($rawParams);
    $query = $dbh->prepare("update lectures set title = :title, description = :description where id = :id");
    $query->bindValue(':title', $params->title, PDO::PARAM_STR);
    $query->bindValue(':description', $params->description, PDO::PARAM_STR);
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
});

$app->delete('/lecture/:id', function($id) use ($dbh) {
    $query = $dbh->prepare("delete from lectures where id = :id");
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
});

$app->map('/', function() {
    echo file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'index.html');
})->via("GET", "POST");

$app->run();