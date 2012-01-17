<?php
namespace se\Step;

abstract class StepExecuter
{
	private $step = null;
	
	private $result = null;
	
	/**
	 * 
	 * @return Step
	 */
	protected function getStep()
	{
		return $this->step;
	}
	
	public function __construct(Step $step)
	{
		$this->step = $step;
	}
	
	public function execute()
	{
		$this->args = func_get_args();
		$this->result = $this->_execute();
		
		return $this;
	}
	
	public function result()
	{
		return $this->result;
	}
	
	abstract protected function _execute();
}