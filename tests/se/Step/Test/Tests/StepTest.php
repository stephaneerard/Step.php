<?php
namespace se\Step\Test\Tests;

use se\Step\Step;

class StepTest extends \PHPUnit_Framework_TestCase
{

	public function testStepping()
	{
		$step = $this->getStep();

		$step->setSteps(array(
			
		function(){
			$args = func_get_args();
			$args = array_slice($args, 1);
			return array($args);
		},
		function($e, $words){
			if($e) throw $e;
			foreach($words as &$word){
				$word = ucfirst($word);
			}
			return array($words);
		},
		function($e, $words){
			if($e) throw $e;
			foreach($words as $word){
				echo $word . ' ';
			}
		}
		));

		$this->expectOutputString('Hello You I Love You ');

		$result = $step
		->serial()
		->execute('hello', 'you', 'i', 'love', 'you')
		->result();
	}

	public function testCreateReturnsAClosureWhichReturnsAStep()
	{
		$step = Step::create();
		$this->assertInstanceOf('Closure', $step);
		$this->assertInstanceOf('se\Step\Step', $step());
	}

	public function testCallingSerialReturnsSerialExecuter()
	{
		$step = $this->getStep();

		$serial = $step->serial();

		$this->assertInstanceOf('se\Step\StepSerialExecuter', $serial);
	}

	public function testCallingParallelReturnsParallelExecuter()
	{
		$step = $this->getStep();

		$serial = $step->parallel();

		$this->assertInstanceOf('se\Step\StepParallelExecuter', $serial);
	}

	public function testGetIterator()
	{
		$array = array(
		function(){
		},
		function(){
		}
		);

		$step = $this->getStep();
		$step->setSteps($array);

		$it = $step->getIterator();

		foreach($it as $i => $el)
		{
			$this->assertSame($array[$i], $el);
		}

		$this->assertTrue($step->offsetExists(0));
		$this->assertSame($step->offsetGet(0), $array[0]);

		$lambda = function(){
		};
		$step[2] = $lambda;
		$this->assertSame($step->offsetGet(2), $lambda);

		unset($step[2]);
		$this->assertFalse($step->offsetExists(2));
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testThrowingException()
	{
		$step = $this->getStep();

		$step->setSteps(array(
			
		function(){
			throw new \InvalidArgumentException();
		},
		function($e, $words){
			if($e) throw $e;
			foreach($words as &$word){
				$word = ucfirst($word);
			}
			return array($words);
		},
		function($e, $words){
			if($e) throw $e;
			foreach($words as $word){
				echo $word . ' ';
			}
		}));

		$step->serial()->execute();
	}

	public function testAfterEach()
	{
		$i = 0;
		$step = $this->getStep();

		$step->setSteps(array(
			
		function(){
		},
		function(){
		},
		function(){
		}))
		;

		$step
		->serial()
		->afterEach(function()use(&$i){
			$i++;
		})
		->execute()
		;
		
		$this->assertEquals(3, $i);
	}




	protected function getStep()
	{
		return new Step();
	}
}