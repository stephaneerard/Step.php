<?php
require_once __DIR__ . '/StepExecuter.class.php';

class StepSerialExecuter extends StepExecuter
{

	protected $inBetween = null;

	public function inBetween(Closure $closure)
	{
		$this->inBetween = $closure;

		return $this;
	}

	protected function _execute()
	{
		$result = $this->args;
		$error = false;
		foreach($this->getStep() as $callable)
		{
			try
			{
				array_unshift($result, $error);
				$result = call_user_func_array($callable, $result);
				$error = null;
			}
			catch(Exception $e)
			{
				$error = $e;
				$result = $e;
			}
			
			if(!is_array($result)) $result = array($result);
			
			$this->executeInBetween($result, $error);
		}

		if($error instanceof Exception)
		{
			throw $error;
		}
		return $result;
	}
	
	private function executeInBetween($result, $error)
	{
		if(! is_callable($this->inBetween)) return;
		
		array_unshift($result, $error);
		call_user_func_array($this->inBetween, $result);
	}
}