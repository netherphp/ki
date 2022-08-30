<?php

namespace Nether\Ki;

class Event {

	public mixed
	$Func;

	public bool
	$Persist;

	public function
	__Construct(callable $Func, bool $Persist) {

		$this->Func = $Func;
		$this->Persist = $Persist;

		return;
	}

	public function
	Exec(array $Argv):
	mixed {

		return ($this->Func)($Argv);
	}

}
