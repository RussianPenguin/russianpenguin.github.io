<?php
include './ns1.php';
include "./Redex.php";

function func1($a, $b, $c)
{
	return $a * $b + $c;
}

class foo
{
	function bar($a, $b, $c)
	{
		return $a * $b + $c;
	}

	static function static_bar($a, $b, $c)
	{
		return $a * $b + $c;
	}
}

$foo = new foo();

echo "Function test: ";
$func = Redex::create('func1', 1);
$func = $func(2);
echo $func(3),"\n";

echo "Lambda test: ";
$func = Redex::create(function ($a, $b, $c) {return $a * $b + $c;}, 1);
$func = $func(2);
echo $func(3), "\n";

echo "Class method test: ";
$func = Redex::create(array($foo, 'bar'), 1);
$func = $func(2);
echo $func(3), "\n";

echo "Static method test: ";
$func = Redex::create("foo::static_bar", 1);
$func = $func(2);
echo $func(3), "\n";

echo "Namespace function test: ";
$func = Redex::create('ns1\ns_func', 1);
$func = $func(2);
echo $func(3),"\n";

echo "Variable argument list test: ";
$func = Redex::create('func1');
$func = $func(1);
echo $func(2,3),"\n";

echo "Variable argument list test: ";
$func = Redex::create('func1');
echo $func(1,2,3),"\n";

