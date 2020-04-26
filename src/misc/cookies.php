<?php

// Конфигурация (домен на котором запускаем)
$domain = 'localhost.localdomain';
$cookieName = 'test';

setcookie($cookieName, 'empty host', 3600+time(), '/');
setcookie($cookieName, 'host: ' . $domain, 3600+time(), '/', $domain);

?>
<!DOCTYPE html>
<html lang="ru_RU">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<title>Work with cookies</title>
		<script>

		/**
		 * Получаем кукис с именем name
		 */
		function getCookie( name ) {
			var start = document.cookie.indexOf( name + '=' );
			var len = start + name.length + 1;
			if ( ( !start ) && ( name != document.cookie.
				substring( 0, name.length ) ) ) {
				return null;
			}
			if ( start == -1 )
				return null;
			var end = document.cookie.indexOf( ';', len );
			if ( end == -1 ) end = document.cookie.length;
				return unescape( document.cookie.substring( len, end ) );
		}

		/**
		 * Устанавливаем печеньку name со значением value
		 * Опции времени пути, домена и секурности необязательны
		 */
		function setCookie( name, value, expires, path, domain, secure ) {
			var today = new Date();
			today.setTime( today.getTime() );
			if ( expires ) {
				expires = expires * 1000 * 60 * 60 * 24;
			}

			var expires_date = new Date( today.getTime() + (expires) );
			document.cookie = name + '=' + escape(value)
				+ ( ( expires ) ? ';expires=' + expires_date.toGMTString() : '' )
				+ ( ( path ) ? ';path=' + path : '' )
				+ ( ( domain ) ? ';domain=' + domain : '' )
				+ ( ( secure ) ? ';secure' : '' );
		}

		/**
		 * Удаляем кукис name c путем path на домене domain
		 * домен и путь необязательны
		 */
		function deleteCookie( name, path, domain ) {
			if ( getCookie( name ) ) {
				document.cookie = name + '='
					+ ( ( path ) ? ';path=' + path : '')
					+ ( ( domain ) ? ';domain=' + domain : '' )
					+ ';expires=' + new Date(0).toGMTString();
			}
		}

		function el(id) {
			return document.getElementById(id);
		}

		/**
		 * Конфигурация
		 */
		var cookieName = 'test';

		/**
		 * Код выполняемый после загрузки домента.
		 * Тут навешиваем обработчики на кнопки и элементы интерфейса.
		 */
		function onReady() {
			el('getCookie').addEventListener('click', function() {
				el('cookieValue').value = getCookie(cookieName);
			}, false);

			el('removeCookie').addEventListener('click', function() {
				var domain = el('cookieDomain').value || '';
				deleteCookie(cookieName, false, domain);
			}, false);

			el('addCookie').addEventListener('click', function() {
				var domain = el('cookieDomain').value || '';
				var value = el('cookieValue').value || Math.rand();

				setCookie(cookieName, value, 3600, false, domain);
			}, false);
		}

		// Обработчик на загрузку документа
		document.addEventListener('DOMContentLoaded', function() {
			document.removeEventListener( "DOMContentLoaded", arguments.callee, false);
			window.cookieName = '<?=$cookieName?>';
			onReady();
		}, false);
		</script>
	</head>
	<body>
		<p>Этот пример работает с кукисом <?=$cookieName?> на домене <?=$domain?></p>
		Домен (если не указывать, то не будет передаваться в функции создания/удаления): <input type="text" id="cookieDomain" />
		<br />
		Содержимое печеньки: <input type="text" id="cookieValue" />
		<br />
		<button id="getCookie">Прочитать печеньку</button>
		<button id="removeCookie">Удалить печеньку</button>
		<button id="addCookie">Поставить печеньку</button>
	</body>
</html>
