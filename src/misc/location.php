<?php

/**
 * Получает элемент $name из массива $arr, если элемент не найден, то вернет $default
 * Экранирование не выполняет.
 *
 * @param array $arr
 * @param string $name
 * @param mixed [optional] $default
 * @return mixed
 */
function getEl(array $arr, $name, $default = null) {
	if (array_key_exists($name, $arr)) {
		return $arr[$name];
	} else {
		return $default;
	}
}

switch (getEl($_GET, 'location')) {
	case 'with200': {
		header('location: ?data', true, 200);
		exit();
	}
	case 'default': {
		header('location: ?data');
		exit();
	}
	case 'data': {
		echo 'some data';
		exit();
	}
	default:
		break;
}

?>
<!DOCTYPE html>
<html lang="ru_RU">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<title>location and ajax samples</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<script language="javascript">
			$(function() {
				$('#locationWith200').click(function() {
					$.get('?location=with200', function(data, status, request) {
						console.log(request.getResponseHeader('location'))
					})
				});
				$('#location').click(function() {
					$.get('?location=default', function(data, status, request) {
						console.log(request.getResponseHeader('location'))
					})
				});
			});
		</script>
	</head>
	<body>
		<p>Пример показывает, как перехватить хидер location, который может отдавать серверный скрипт.</p>
		<button id="locationWith200">Получаем страницу с редиректом и кодом 200</button>
		<button id="location">Получаем страницу с редиректом и дефолтным кодом (302 для php)</button>
	</body>
</html>
