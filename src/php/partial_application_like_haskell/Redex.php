<?php

/**
 * "Partial Application" in php (like haskell http://www.haskell.org/haskellwiki/Partial_application)
 * 
 * Examples:
 * function foo($a, $b){return $a + $b}
 * $func = Redex('foo');
 * $func = $func(1); // Will return instance of Redex
 * $func_result = $func(2); //Return result of foo(1, 2)
 *
 * See more on GitHub ()
 *
 * @author Maksim Zubkov <penguin@russianpenguin.ru>
 */
class Redex
{
	/**
	 * @var mixed Function or Redex instance
	 */
	private $lambda = null;
	
	/**
	 * @var array Portion of arguments
	 */
	private $arguments = array();

	/**
	 * Hide constuctor for use only inside this class
	 *
	 * @param mixed $lambda Function, method or instance of Redex (this is currying function)
	 * @param array $arguments List of arguments
	 */
	private function __construct($lambda, $arguments = array())
	{
		$this->lambda = $lambda;
		$this->arguments = $arguments;
	}

	/**
	 * Factory method for create Redex instance.
	 * Take function and part of arguments.
	 * Argument list may have variable length.
	 * 
	 * @param mixed $function
	 * @param mixed [optional]
	 * @return Redex Instance of "Partial application"
	 */
	static function create($function)
	{
		if (func_num_args() < 1)
		{
			throw new Exception("Incorrect argument list");
		}
		if ( ! is_callable(func_get_arg(0)))
		{
			throw new Exception("First argument should be callable");
		}
		$args = func_get_args();
		$function = array_shift($args);
		return new self($function, $args);
	}

	/**
	 * Call function.
	 * If count of arguments is not full method create
	 * instance or Redex for self and return them.
	 * Invoke argument list.
	 * Return instance of Redex or result of function (if available)
	 *
	 * Example:
	 * function foo($a, $b){return $a + $b}
	 * $func = Redex('foo');
	 * $func = $func(1); // Will return instance of Redex
	 * $func_result = $func(2); //Return result of foo(1, 2)
	 *
	 * @param mixed [optional]
	 * @return mixed
	 */
	function __invoke()
	{
		$arguments = func_get_args();
		$total_arguments_count = 
			$this->getArgumentsCount()
			+ count($arguments);
		if ($total_arguments_count == $this->getParametrsCount())
		{
			return call_user_func_array($this->lambda,
				array_merge($this->arguments, $arguments));
		}
		else
		{
			return new self($this, $arguments);
		}
	}

	/**
	 * Get exists arguments count.
	 * If lambda is instance of Redex function return count of agruments recursive
	 *
	 * @return int
	 */
	protected function getArgumentsCount()
	{
		if ($this->lambda instanceof self)
		{
			return count($this->arguments)
				+ $this->lambda->getArgumentsCount();
		}
		else
		{
			return count($this->arguments);
		}
	}

	/**
	 * Get parametrs count for lambda function
	 * Use reflection for get count
	 *
	 * @return int
	 */
	protected function getParametrsCount()
	{
		if ($this->lambda instanceof self)
		{
			return $this->lambda->getParametrsCount();
		}
		else
		{
			if (is_array($this->lambda))
			{
				list($class, $method) = $this->lambda;
				$reflection = new ReflectionMethod($class, $method);
			}
			else if ((gettype($this->lambda) == 'string') AND strpos($this->lambda, "::"))
			{
				list($class, $method) = explode('::', $this->lambda);
				$reflection = new ReflectionMethod($class, $method);
			}
			else
			{
				$reflection = new ReflectionFunction($this->lambda);
			}
			return $reflection->getNumberOfParameters();
		}
	}
}
