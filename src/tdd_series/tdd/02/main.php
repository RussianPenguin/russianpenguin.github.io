<?php

require __DIR__ . DIRECTORY_SEPARATOR . 'triangle.php';

function main()
{
    // проинициализируем переменные
    $a = $b = $c = 0;

    // получим длины сторон со стандартного ввода
    $num = fscanf(STDIN, "%d %d %d\n", $a, $b, $c);
    // если мы смогли считать длины трех сторон, то вызовем нашу функцию и покажем результат
    if ($num == 3)
    {
        switch (triangle_type($a, $b, $c))
        {
            case TRIANGLE_BAD:
                echo "Это не треугольник\n";
                break;
            case TRIANGLE_EQUILATERAL:
                echo "Это равносторонний треугольник\n";
                break;
            case TRIANGLE_ISOSCELES:
                echo "Это равнобедренный треугольник\n";
                break;
            case TRIANGLE_RIGHT:
                echo "Это прямоугольный треугольник\n";
                break;
            case TRIANGLE_SIDED:
                echo "Это разносторонний треугольник\n";
                break;
        }
    }
}

main();