<?php

/**
 * Не треугольник
 */
define('TRIANGLE_BAD', 0);
/**
 * Равносторонний треугольник
 */
define('TRIANGLE_EQUILATERAL', 1);
/**
 * Прямоугольный треугольник
 */
define('TRIANGLE_RIGHT', 2);
/**
 * Равнобедренный треугольник
 */
define('TRIANGLE_ISOSCELES', 3);
/**
 * Разносторонний треугольник
 */
define('TRIANGLE_SIDED', 4);

/**
 * По длинам сторон $a, $b и $c возвращает тип треугольника.
 * Если стороны не являются целочисленными, то выбрасывает исключение.
 *
 * @param $a
 * @param $b
 * @param $c
 * @return int
 * @throws Exception
 */
function triangle_type($a, $b, $c)
{
    // Вполне ожидаемо, что нецелочисленные значения должны приводить к исключительной ситуации.
    if (!is_int($a) or !is_int($b) or !is_int($c))
    {
        throw new \Exception('Invalid triangle definition');
    }

	$max = null;
	$min1 = null;
	$min2 = null;

	if (($a+$b)>$c and ($a+$c)>$b and ($b+$c)>$a)
	{
		if (($a>$b) and ($a>$c))
		{
			$max = $a;
			$min1 = $b;
			$min2 = $c;
		}
		else if (($b>$c) and ($b>$a))
		{
			$max = $b;
			$min1 = $a;
			$min2 = $c;
		}
		else
		{
			$max = $c;
			$min1 = $a;
			$min2 = $b;
		}

		if (pow($max, 2) == pow($min1, 2) + pow($min2, 2))
		{
			return TRIANGLE_RIGHT;
		}
        else if (($max==$min1) and ($max==$min2))
        {
            return TRIANGLE_EQUILATERAL;
        }
		else if (($max==$min1) or ($max==$min2) or ($min1==$min2))
		{
			return TRIANGLE_ISOSCELES;
		}
		else
		{
			return TRIANGLE_SIDED;
		}
	}
	else
	{
		return TRIANGLE_BAD;
	}
}
