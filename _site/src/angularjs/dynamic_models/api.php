<?php
require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

$json = json_decode(file_get_contents(dirname(__FILE__) . '/json/data_source.json'));

// Сформируем базу данных

$cityDB = [];
$country2city = [];
$countryDB = [];

// Города
foreach ($json->cities as $city) {
    $cityDB[$city->id] = [
        'id' => $city->id,
        'name' => $city->name,
        'countryId' => $city->country_id
    ];

    if (!array_key_exists($city->country_id, $country2city)) {
        $country2city[$city->country_id] = [];
    }

    $country2city[$city->country_id][] = $city->id;
}

// страны
foreach ($json->countries as $country) {
    $countryDB[$country->id] = [
        'id' => $country->id,
        'name' => $country->name,
    ];
}

$app = new \Slim\Slim();
$app->get('/city/:id', function ($id) use ($cityDB, $country2city, $countryDB) {
    header('Content-type: application/json; charset=utf-8');
    sleep(2);
    $city = $cityDB[$id];
    if (array_key_exists($city['countryId'], $countryDB)) {
        $city['country_name'] = $countryDB[$city['countryId']]['name'];
    } else {
        $city['country_name'] = 'Unknown';
    }
    echo json_encode($city);
})->conditions(array('id' => '\d+'));

$app->get('/complete/(:text)', function($text = '') use ($cityDB, $country2city, $countryDB) {
    header('Content-type: application/json; charset=utf-8');
    $list = [];
    if ($text) {
        $text = mb_strtolower($text, 'utf-8');
        foreach ($cityDB as $city) {
            if (mb_strpos(mb_strtolower($city['name'], 'utf-8'), $text) !== false) {
                $list[] = [
                    'id' => $city['id'],
                    'name' => $city['name']
                ];
            }
        }
    }
    echo json_encode($list);
});

$app->run();