<?php

require __DIR__ . DIRECTORY_SEPARATOR . 'triangle.php';

function testForIsoscelesTriangle()
{
    echo "Test for [3, 4, 4]: ";
    if (triangle_type(3, 4, 4) == TRIANGLE_ISOSCELES) {
        echo "ok\n";
    } else {
        echo "fail\n";
    }
}

function main()
{
    testForIsoscelesTriangle();
}

main();