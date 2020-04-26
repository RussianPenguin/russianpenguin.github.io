<?php

require __DIR__ . DIRECTORY_SEPARATOR . "triangle.php";

/**
 * @var array Набор тестовых случаев.
 */
$tests = [
    [ // разносторонний треугольнки
        'values' => [[2, 4, 5], [10, 11, 12]],
        'type' => TRIANGLE_SIDED,
    ],
    [ // равносторонний треугольник
        'values' => [[10, 10, 10], [1, 1, 1], [1000, 1000, 1000]],
        'type' => TRIANGLE_EQUILATERAL,
    ],
    [ // равнобедренный треугольник (несколько случаев)
        'values' => [[2, 2, 3], [4, 4, 5]],
        'type' => TRIANGLE_ISOSCELES,
    ],
    [ // равнобедренный треугольник (перестановки)
        'values' => [[3, 3, 4], [4, 3, 3], [3, 4, 3]],
        'type' => TRIANGLE_ISOSCELES,
    ],
    [ // одна из сторон равна нулю
        'values' => [[0, 1, 2]],
        'type' => TRIANGLE_BAD,
    ],
    [ // одна из сторон меньше нуля
        'values' => [[-1, 1, 2]],
        'type' => TRIANGLE_BAD,
    ],
    [ // сумма длин двух сторон равна третьей
        'values' => [[1, 2, 3], [3, 10, 13]],
        'type' => TRIANGLE_BAD,
    ],
    [ // сумма длин двух сторон равна третьей (перестановки)
        'values' => [[3, 2, 1], [1, 2, 3], [2, 3, 1]],
        'type' => TRIANGLE_BAD,
    ],
    [ // сумма длин двух сторон меньше третьей
        'values' => [[3, 2, 10], [2, 4, 7]],
        'type' => TRIANGLE_BAD,
    ],
    [ // сумма длин двух сторон меньше третьей (перестановки)
        'values' => [[3, 2, 10], [2, 3, 10], [10, 2, 3], [10, 3, 2]],
        'type' => TRIANGLE_BAD,
    ],
    [ // длины всех сторон равны нулю
        'values' => [[0, 0, 0]],
        'type' => TRIANGLE_BAD,
    ],
    [ // длины сторон не выражаются целым числом (ожидаем исключение)
        'values' => [[2.5, 2.5, 2.5]],
        'exception' => true,
    ],
    [ // заданы только несколько сторон (ожидаем ошибку)
        'values' => [[1, 2]],
        'error' => true,
    ]
];

/**
 * Метод используется внутри колбеков для того чтобы получить\установить флаг, который означает появление ошибки.
 * @return bool|mixed
 */
function errorHasOccured()
{
    static $error = false;
    if (func_num_args() == 1) {
        $error = func_get_arg(0);
    }
    return $error;

}

/**
 * Метод используется внутри колбеков для того, чтобы установить\получить значение флага наличия исключения.
 * @return bool|mixed
 */
function exceptionHasOccured()
{
    static $error = false;
    if (func_num_args() == 1) {
        $error = func_get_arg(0);
    }
    return $error;
}

/**
 * Тестируем определенный тест-кейс $test.
 * Кейс представляет из себя массив из четырех элементов
 * [
 *   'values' => [],
 *   'type' => TRIANGLE_BAD|TRIANGLE_ISOSCELES|TRIANGLE_EQUILATERAL|TRIANGLE_SIDED,
 *   'error' => true|false,
 *   'exception' => true|false,
 * ]
 *
 * values - несколько наборов аргументов функциии, которые должны приводить к одному и тому же результату.
 * Например [[3, 2, 10], [2, 3, 10], [10, 2, 3], [10, 3, 2]].
 * type - ожидаемое значение типа треугольника в случае корректного завершения функции.
 * Если ожидаемое завершение ошибка или исключение, то можно не указывать.
 * error - если установлено в true, то при работе функции ожидается ошибка.
 * exception - если установлено в true, то функция на заданных аргументах должна бросить исключение.
 *
 * @param $test
 */
function test($test)
{
    // Перед запуском тестов установим обработчик ошибок для того,
    // чтобы тесты могли проверить наличие ошибки при выполнении
    set_error_handler(function() {
        errorHasOccured(true);
    });

    // Проинициализируем возвращаемое значение.
    $res = null;

    // Перебирая все значения values из теста $test проверяем выполнение кейса для каждого из них
    foreach ($test['values'] as $value) {
        // обнуляем признаки появление ошибки и исключения
        errorHasOccured(false);
        exceptionHasOccured(false);

        echo "Run test with values [" . join(', ', $value) . "] ";

        // основной код, который отвечает за выполнение тестируемой функции triangle_type()
        try {
            $res = call_user_func_array('triangle_type', $value);
        } catch (\Exception $e) {
            // в случае появления исключения установим флаг наличия исключения
            exceptionHasOccured(true);
        }

        // проверяем все тестовые случаи
        if (array_key_exists('type', $test) and $res == $test['type']) {
            // если результат есть и он равен ожидаемому, то тест пройден
            echo "ok\n";
        } else if (array_key_exists('error', $test) and $test['error'] == errorHasOccured()) {
            // если тест ожидает возникновения ошибки и она есть, то он пройден
            echo "ok\n";
        } else if (array_key_exists('exception', $test) and $test['exception'] == exceptionHasOccured()) {
            // если тест ожидает появления исключения и оно есть, то он пройден
            echo "ok\n";
        } else {
            // во всех остальных случаях тест не пройден
            echo "fail\n";
        }
    }

    // после всех манипуляций надо посстановить обработчик ошибок в до прежнего состояния
    restore_error_handler();
}

/**
 * Функция запуска тестов. На вход функции подается набор тестовых сценариев $tests
 * @param $tests
 */
function main($tests) {
    foreach ($tests as $test) {
        if (isset($test['name'])) {
            echo "Run test {$test['name']}\n";
        }
        test($test);
    }
}

main($tests);