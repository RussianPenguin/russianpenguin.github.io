<!DOCTYPE html>
<html lang="ru_RU">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<title>location.pathname sample</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<script language="javascript">
			$(function() {
				$('#changeLocation').click(function() {
					var pathname = $('#pathname').val();
					console.log('pathname: ' + pathname);
					window.location.pathname = pathname;
				});

				$('#changeLocationHref').click(function() {
					var pathname = $('#pathname').val();
					console.log('pathname: ' + pathname);
					window.location.href = pathname;
				});

				$('#changeLocationWithEncode').click(function() {
					var pathname = $('#pathname').val();
					console.log('pathname: ' + pathname);
					var pathnamePos = pathname.indexOf('#');
					if (pathnamePos != -1) {
						var left = pathname.substring(0, pathnamePos);
						var right = pathname.substring(pathnamePos+1);
						console.log(left, right);
						pathname = left + '%23' + right
					}
					console.log('real pathname: ' + pathname);
					window.location.pathname = pathname;
				});
			});
		</script>
	</head>
	<body>
		<p>Пример показывает особенности обработки location.pathname в разных браузерах.</p>
		<input id="pathname" type="text" />
		<button id="changeLocation">Изменить location.pathname без перекодирования # (Правильно обрабатывается в Chrome)</button>
		<button id="changeLocationWithEncode">Изменить location.pathname перекодировав # в %23 (Правильное поведение для Firefox)</button>
		<button id="changeLocationHref">Изменить location.href</button>
	</body>
</html>
