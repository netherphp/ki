<?php

namespace Nether\Ki;

trait CallbackPackage {

	protected array
	$KiEvents = [];

	public function
	Queue(string $Key, callable $Func, bool $Persist=FALSE):
	static {

		if(!array_key_exists($Key, $this->KiEvents))
		$this->KiEvents[$Key] = array();

		$this->KiEvents[$Key][] = new Event($Func, $Persist);

		return $this;
	}

	public function
	Flow(string $Key, array $Argv=[], bool $Persist=FALSE):
	int {

		$Count = 0;
		$Iter = 0;
		$Event = NULL;

		////////

		if(!array_key_exists($Key, $this->KiEvents))
		return $Count;

		if(!is_array($Argv))
		$Argv = (array)$Argv;

		////////

		foreach($this->KiEvents[$Key] as $Iter => $Event) {
			/** @var Event $Event */

			$Event->Exec($Argv);
			$Count++;

			if(!$Persist && !$Event->Persist)
			unset(self::$Callbacks[$Key][$Iter]);
		}

		return $Count;
	}

}
