<?php
abstract class StepExecuter
{
	private $step = null;
	
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
		$this->_execute();
	}
	
	abstract protected function _execute();
}