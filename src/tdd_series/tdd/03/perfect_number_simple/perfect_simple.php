<?php

function isPerfect($number)
{
    // Получить делители
    $factors = [];
    $factors[] = 1;
    $factors[] = $number;

    for ($i = 2; $i < sqrt($number) + 1; $i++) // примечание [1]
    {
        if ($number % $i == 0)
        {
            $factors[] = $i;
            if (intdiv($number, $i) != $i) // примечание [2}
            {
                $factors[] = $number / $i;
            }
        }
    }

    // Вычислить сумму делителей
    $sum = 0;

    foreach ($factors as $i)
    {
        $sum += $i;
    }

    // Проверить, является ли число совершенным
    return $sum - $number == $number;
}

$number = 0;
fscanf(STDIN, "%d\n", $number);

if (isPerfect($number))
{
    echo "{$number} is perfect number\n";
}