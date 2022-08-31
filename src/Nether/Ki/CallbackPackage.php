<?php

namespace Nether\Ki;

trait CallbackPackage {

	protected array
	$KiQueue = [];

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	public function
	Queue(string $Key, callable $Func, bool $Persist=TRUE):
	static {

		if(!array_key_exists($Key, $this->KiQueue))
		$this->KiQueue[$Key] = [];

		$this->KiQueue[$Key][] = new Event($Func, $Persist);

		return $this;
	}

	public function
	Flow(string $Key, array|object $Argv=[], bool $Persist=TRUE):
	int {

		$Count = 0;
		$Iter = 0;
		$Event = NULL;

		////////

		if(!array_key_exists($Key, $this->KiQueue))
		return $Count;

		if(!is_array($Argv))
		$Argv = (array)$Argv;

		////////

		foreach($this->KiQueue[$Key] as $Iter => $Event) {
			/** @var Event $Event */

			$Event->Exec($Argv);
			$Count++;

			if(!$Persist || !$Event->Persist)
			unset($this->KiQueue[$Key][$Iter]);
		}

		return $Count;
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	public function
	QueueCount(string $Key):
	int {

		if(isset($this->KiQueue[$Key]))
		return count($this->KiQueue[$Key]);

		return 0;
	}

	public function
	QueueGet(string $Key):
	array {

		if(isset($this->KiQueue[$Key]))
		return $this->KiQueue[$Key];

		return [];
	}

	public function
	QueueClear(string $Key):
	static {

		if(isset($this->KiQueue[$Key]))
		unset($this->KiQueue[$Key]);

		return $this;
	}

}
