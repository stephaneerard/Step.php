<?php
require_once __DIR__ . '/Step.class.php';

$step = Step::create(
	function($e, $hello, $you, $i, $love, $you){
		return array(array($hello, $you, $i, $love, $you));
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
);


$step()
->serial()
->execute('hello', 'you', 'i', 'love', 'you');


//////////////////////////////////////////////////////////////////


$function = function(){
	$args = func_get_args();
	$args = array_slice($args, 1);
	foreach($args as $arg)
	{
		$arg();
	}
	
	return $args;
};

$echo_eol = function(){
	echo PHP_EOL;
};

$echo_ws = function(){
	echo ' ';
};

$step = Step::create(
	$function,
	$function,
	$function,
	$function
);

$doThis = function() use ($echo_ws){
	echo 'doing this';
	$echo_ws();
};

$thenThat = function() use ($echo_ws){
	echo 'then that';
	$echo_ws();
};

$followedByThis = function() use ($echo_ws){
	echo 'followed by this';
	$echo_ws();
};

$andThat = function() use ($echo_ws){
	echo 'and that';
	$echo_ws();
};

$finallyDoThat = function() use ($echo_ws){
	echo 'finally do that';
	$echo_ws();
};

$echo_eol();


$step()
->serial()
->inBetween($echo_eol)
->execute($doThis, $thenThat, $followedByThis, $andThat, $finallyDoThat);