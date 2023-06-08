<?php

namespace NetherTestSuite\Ki\Basic;
use Nether;
use PHPUnit;

class Test1 {

	use
	Nether\Ki\CallbackPackage;

};

class CallbackPackageTest
extends PHPUnit\Framework\TestCase {

	/** @test */
	public function
	BasicTest():
	void {

		$Test = new Test1;
		$Func1 = (fn()=> print('omg'));
		$Func2 = (fn()=> print('wtf'));
		$Count = NULL;
		$Output = NULL;

		// test we got the methods from the trait.

		$this->AssertTrue(method_exists($Test, 'Queue'));
		$this->AssertTrue(method_exists($Test, 'Flow'));

		// test adding items to the queue.

		$this->AssertEquals(0, $Test->QueueCount('Test'));

		$Test->Queue('Test', $Func1);
		$this->AssertEquals(1, $Test->QueueCount('Test'));

		$Test->Queue('Test', $Func2);
		$this->AssertEquals(2, $Test->QueueCount('Test'));

		// test flowing the queue.

		ob_start();
		$Count = $Test->Flow('Test');
		$Output = ob_get_clean();

		$this->AssertEquals(2, $Count);
		$this->AssertEquals('omgwtf', $Output);

		// test flowing something that does not exist.

		ob_start();
		$Count = $Test->Flow('Undef');
		$Output = ob_get_clean();

		$this->AssertEquals(0, $Count);
		$this->AssertEquals('', $Output);

		return;
	}

	/** @test */
	public function
	FlowInputTest():
	void {

		$Test = new Test1;
		$Func1 = (fn(...$Arg)=> print(count($Arg)));
		$Func2 = (fn(...$Arg)=> print($Arg['Key']));
		$DataArr = [ 'Key'=> 'Value' ];
		$DataObj = new class { public string $Key='Value'; };
		$Count = NULL;
		$Output = NULL;

		////////

		$Test->Queue('Test1', $Func1);
		$Test->Queue('Test2', $Func2);

		// test no arguments.

		ob_start();
		$Count = $Test->Flow('Test1');
		$Output = ob_get_clean();
		$this->AssertEquals(1, $Count);
		$this->AssertEquals('0', $Output);

		// test array argument.

		ob_start();
		$Count = $Test->Flow('Test1', $DataArr);
		$Output = ob_get_clean();
		$this->AssertEquals(1, $Count);
		$this->AssertEquals('1', $Output);

		ob_start();
		$Count = $Test->Flow('Test2', $DataArr);
		$Output = ob_get_clean();
		$this->AssertEquals(1, $Count);
		$this->AssertEquals('Value', $Output);

		// test object argument.

		ob_start();
		$Count = $Test->Flow('Test1', $DataObj);
		$Output = ob_get_clean();
		$this->AssertEquals(1, $Count);
		$this->AssertEquals('1', $Output);


		ob_start();
		$Count = $Test->Flow('Test2', $DataObj);
		$Output = ob_get_clean();
		$this->AssertEquals(1, $Count);
		$this->AssertEquals('Value', $Output);

		return;
	}

	/** @test */
	public function
	PersistTest():
	void {

		$Test = new Test1;
		$Func1 = (fn(...$Arg)=> print(count($Arg)));
		$Count1 = NULL;
		$Count2 = NULL;

		// test one will stick forever.
		// test two will vanish after one use.

		$Test->Queue('Test1', $Func1);
		$Test->Queue('Test2', $Func1, FALSE);

		ob_start();
		$Test->Flow('Test1');
		$Test->Flow('Test1');
		$Count1 = $Test->Flow('Test2');
		$Count2 = $Test->Flow('Test2');
		ob_end_clean();

		$this->AssertEquals(1, $Count1);
		$this->AssertEquals(0, $Count2);

		$this->AssertEquals(1, $Test->QueueCount('Test1'));
		$this->AssertEquals(0, $Test->QueueCount('Test2'));

		// test forcing a persist allowed event to vanish after use.

		ob_start();
		$Count1 = $Test->Flow('Test1', Persist: FALSE);
		$Count2 = $Test->Flow('Test1');
		ob_end_clean();

		$this->AssertEquals(1, $Count1);
		$this->AssertEquals(0, $Count2);
		$this->AssertEquals(0, $Test->QueueCount('Test1'));

		return;
	}

	/** @test */
	public function
	QueueSupportMethodTest():
	void {

		$Test = new Test1;
		$Func = (fn()=> 'lol');

		////////

		$this->AssertEquals(0, $Test->QueueCount('Test'));

		// test adding, counting, and getting.

		$Test->Queue('Test', $Func);
		$this->AssertEquals(1, $Test->QueueCount('Test'));
		$this->AssertIsArray($Test->QueueGet('Test'));
		$this->AssertCount(1, $Test->QueueGet('Test'));

		// test clearing, counting, and getting.

		$Test->QueueClear('Test');
		$this->AssertEquals(0, $Test->QueueCount('Test'));
		$this->AssertIsArray($Test->QueueGet('Test'));
		$this->AssertCount(0, $Test->QueueGet('Test'));

		return;
	}

};
