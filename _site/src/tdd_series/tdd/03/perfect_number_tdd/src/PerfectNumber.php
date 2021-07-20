<?php

namespace PerfectNumberTDD;

class PerfectNumber
{
    /**
     * @var integer число
     */
    private $number;
    /**
     * @var array Список делителей числа
     */
    private $factors;

    /**
     * PerfectNumber constructor.
     * Инициализиреуем класс числом $number для которого надо узнать, является ли оно совершенным.
     * В процессе инициализации добавляем список делителей числа следующие три числа:
     * - 1 (число всегда делится на 1)
     * - $number (число всегда делится само на себя)
     * @param $number
     */
    public function __construct($number)
    {
        $this->number = $number;
        $this->factors = [];

        $this->addFactor(1);
        $this->addFactor($number);
        $this->calculateFactors();
    }

    /**
     * Возвращает булево значение, которое сигнализирует о том, является ли число совершенным.
     * @return bool
     */
    public function isPerfect()
    {
        return $this->sumOfFactors() - $this->number == $this->number;
    }

    /**
     * ДОбавляет делитель $factor к списку уже исзвестных делителей числа.
     * Одновременно с этим добавляет и делитель $this->number / $i (Если он отличен от нуля)
     * @param $factor
     */
    protected function addFactor($factor)
    {
        if ($this->isFactor($factor)) {
            // Это не самая удачная строка кода.
            // Она призвана оставить в массиве $this->factors только уникальные, отличные от нуля значения.
            $this->factors = array_unique( // оставляем только уникальные значения
                array_merge( // Объединение двух массивов
                    array_filter([$factor, intdiv($this->number, $factor)]), // отфильтрованняй массив делителей
                    $this->factors // предыдущее содержимое массива делителей
                )
            );
        }
    }

    /**
     * Возвращает список делителей числа.
     * @return array
     */
    public function getFactors()
    {
        return $this->factors;
    }

    /**
     * Вычисляет список делителей числа.
     */
    protected function calculateFactors()
    {
        for ($i = 2; $i < sqrt($this->number) + 1; $i++) {
            $this->addFactor($i);
        }
    }

    /**
     * Возвращает сумму делителей числа.
     * @return integer
     */
    protected function sumOfFactors()
    {
        return array_reduce($this->factors, function ($carry, $item) {
            return $carry + $item;
        }, 0);
    }

    /**
     * Проверяет является ли $factor делителем числа $this->number.
     * @param $factor
     * @return bool
     */
    public function isFactor($factor)
    {
        if ($factor > 0) {
            return $this->number % $factor == 0;
        } else {
            return false;
        }
    }
}
