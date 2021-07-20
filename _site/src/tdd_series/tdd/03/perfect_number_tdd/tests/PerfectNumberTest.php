<?php
namespace PerfectNumberTDD;

use PHPUnit_Framework_TestCase;

/**
 * Набор тестовых случаев для проверки класса PerfectNumber
 */
class PerfectNumberTest extends PHPUnit_Framework_TestCase
{
    /**
     * Проверяем, что делителями числа 1 является только единица.
     */
    public function testFactorsFor1()
    {
        $expected = [1];
        $p = new PerfectNumber(1);
        $this->assertEquals($expected, $p->getFactors(), "", 0.0, 10, true);
    }

    /**
     * Проверяем, что ноль не является делителем.
     */
    public function testZeroIsNotFactor()
    {
        // Не имеет значения, какое число мы будем использовать
        $p = new PerfectNumber(42);
        $this->assertFalse($p->isFactor(0));
    }

    /**
     * Берем несколько числе и проверяем, что класс верно определяет делится ли число на какое-то другоей. Или нет.
     */
    public function testIsFactor()
    {
        $p1 = new PerfectNumber(10);
        $this->assertTrue($p1->isFactor(1));

        $p2 = new PerfectNumber(25);
        $this->assertTrue($p2->isFactor(5));

        $p3 = new PerfectNumber(25);
        $this->assertFalse($p3->isFactor(6));
    }

    /**
     * Вручную добавляем делители числа и проверяем, что список делителей изменился.
     */
    public function testAddFactors()
    {
        $p = new PerfectNumber(20);

        $this->invokeMethod($p, 'addFactor', [2]);
        $this->invokeMethod($p, 'addFactor', [4]);
        $this->invokeMethod($p, 'addFactor', [5]);
        $this->invokeMethod($p, 'addFactor', [10]);

        $expected = [1, 2, 4, 5, 10, 20];
        $this->assertEquals($expected, $p->getFactors(), "", 0.0, 10, true);
    }

    /**
     * Проверим правильность подсчета делителей для 6.
     * Обратите внимание, что делители не возвращаются в упорядоченном массиве.
     * И нам нужно сравнивать массивы безотносительно позиций элементов в них.
     * Таким образом Массив [1, 2] будет равен массиву [2, 1]. Так как в них содержатся одинаковые элементы.
     */
    public function testFactorsFor6()
    {
        $expected = [1, 2, 3, 6];
        $p = new PerfectNumber(6);
        $this->assertEquals($expected, $p->getFactors(), "", 0.0, 10, true);
    }

    /**
     * Протестируем совершенство числа вручную добавив все делители
     */
    public function testIsPerfectCreatedByHands()
    {
        $p = new PerfectNumber(6);

        $this->invokeMethod($p, 'addFactor', [2]);
        $this->invokeMethod($p, 'addFactor', [3]);
        $this->invokeMethod($p, 'addFactor', [6]);

        $this->assertTrue($p->isPerfect());
    }

    /**
     * Проверим, что число 6 совершенное, а 7 - нет.
     */
    public function testIsPerfect()
    {
        $p = new PerfectNumber(6);
        $this->assertTrue($p->isPerfect());

        $p = new PerfectNumber(7);
        $this->assertFalse($p->isPerfect());
    }

    /**
     * Вызов protected/private метода класса.
     *
     * @param object &$object    Объект, метод которого будет вызываться.
     * @param string $methodName Имя метода для вызова
     * @param array  $parameters Массив параметров метода.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
