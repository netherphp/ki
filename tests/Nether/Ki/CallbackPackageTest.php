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

		$this->AssertTrue(method_exists($Test, 'Queue'));
		$this->AssertTrue(method_exists($Test, 'Flow'));

		$Test->Queue('Test', $Func1);
		$this->AssertEquals(1, $Test->QueueCount('Test'));

		$Test->Queue('Test', $Func2);
		$this->AssertEquals(2, $Test->QueueCount('Test'));

		ob_start();
		$Count = $Test->Flow('Test');
		$Output = ob_get_clean();

		$this->AssertEquals(2, $Count);
		$this->AssertEquals('omgwtf', $Output);

		return;
	}

};
