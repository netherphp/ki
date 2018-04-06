<?php

namespace Nether;
use \Nether;

////////////////
////////////////

class Ki {

	static $Queue = array();
	/*//
	@type array
	a singleton array holding the current queue of event handlers.
	//*/

	static $Log = array();
	/*//
	@type array
	holds a log of all the ki that has flown as they were. must be enabled
	in the configuration else not used.
	//*/

	public $Call = null;
	/*//
	@type callable
	this is the callback that should be executed when this event item is
	triggered.
	//*/

	public $Persist = false;
	/*//
	@type bool
	mark if this event item should be kept in the queue after it is used.
	the default is that items in the queue are removd after they are used
	once. with this true they will stick around for each occurance of the
	event that happens throughout the entire application.
	//*/

	////////////////
	////////////////

	public function __construct($call,$persist=false) {
		if(!is_callable($call))
		throw new \Exception('specified value not callable');

		$this->Call = $call;
		$this->Persist = $persist;
		$this->Alias = md5(microtime().rand(1,1000));

		return;
	}

	////////////////
	////////////////

	public function Exec($argv) {
	/*//
	@argv array Argv
	@return mixed
	run the callable associated with this ki event.
	//*/

		return call_user_func_array($this->Call,$argv);
	}

	////////////////
	////////////////

	static function Log($key,$ki,$argv) {
		if(!Nether\Option::Get('nether-ki-log'))
		return;

		if($ki) {
			$types = [];
			foreach($argv as $arg) $type[] = gettype($arg);

			self::$Log[] = sprintf(
				'%s %s(%s)',
				$key,
				$ki->Alias,
				((count($types))?(join(',',$types)):('void'))
			);
		} else {
			self::$Log[] = "{$key}";
		}
	}

	////////////////
	////////////////

	static function
	Flow($Key, $Argv=NULL, $Persist=FALSE) {
	/*//
	@update 2018-04-06
	@argv string Key, array Argv, Bool Persist default FALSE
	@return int
	flow all the ki events for the specified Key. returns a count of how
	many events were executed. if persist is true the we will not delete any
	ki which has been marked for removal after one use.
	//*/

		$Count = 0;
		$Iter = 0;
		$Ki = NULL;

		////////

		self::Log($Key,NULL,NULL);

		if(!array_key_exists($Key,self::$Queue))
		return 0;

		if(!is_array($Argv) && !is_object($Argv))
		$Argv = array($Argv);

		////////

		$Count = 0;

		foreach(self::$Queue[$Key] as $Iter => $Ki) {
			self::Log($Key,$Ki,$Argv);

			$Ki->Exec($Argv);

			if(!$Persist && !$Ki->Persist)
			unset(self::$Queue[$Key][$Iter]);

			++$Count;
		}

		////////

		return $Count;
	}

	static function Queue($key,$call,$persist=false) {
	/*//
	@argv string Key, callable Func, bool Persist
	add a handler to the queue of ki events.
	//*/

		if(!array_key_exists($key,self::$Queue))
		self::$Queue[$key] = array();

		self::$Queue[$key][] = new self($call,$persist);
		return;
	}

}
