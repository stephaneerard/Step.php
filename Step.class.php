<?php

require __DIR__ . '/StepSerialExecuter.class.php';
require __DIR__ . '/StepParallelExecuter.class.php';

class Step implements ArrayAccess, IteratorAggregate
{
	protected $steps = array();

	public function __construct()
	{
	}

	public function setSteps($steps)
	{
		$this->steps = $steps;

		return $this;
	}

	public function serial()
	{
		return new StepSerialExecuter($this);
	}

	public function parallel()
	{
		return new StepParallelExecuter($this);
	}

	/**
	 * 
	 * @return Step
	 */
	public static function create()
	{
		$args = func_get_args();
		$step = new static();
		$step->setSteps($args);
		return function() use ($step){
			return $step;
		};
	}

	public function getIterator()
	{
		return new ArrayIterator($this->steps);
	}

	public function offsetExists($offset)
	{
		return isset($this->steps[(int) $offset]);
	}

	public function offsetGet($offset)
	{
		return $this->steps[(int) $offset];
	}

	public function offsetSet($offset, $value)
	{
		$this->steps[(int) $offset] = $value;

		return $this;
	}

	public function offsetUnset($offset)
	{
		unset($this->steps[(int) $offset]);
	}
}