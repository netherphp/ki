Nether Ki
==============

[![nether.io](https://img.shields.io/badge/nether-ki-C661D2.svg)](http://nether.io/) [![Code Climate](https://codeclimate.com/github/netherphp/ki/badges/gpa.svg)](https://codeclimate.com/github/netherphp/ki) [![Build Status](https://travis-ci.org/netherphp/ki.svg)](https://travis-ci.org/netherphp/ki)  [![Packagist](https://img.shields.io/packagist/v/netherphp/ki.svg)](https://packagist.org/packages/netherphp/ki) [![Packagist](https://img.shields.io/packagist/dt/netherphp/ki.svg)](https://packagist.org/packages/netherphp/ki)

#### Basic Use

An event system handler. Queue callbacks to be executed when needed by other parts of the application. Can be used to trigger actions or filter things. Events are one time use by default, but you can also have them persist for multiple uses. This is not a true async thing like React - these filters will block, so they are safe for inline filtering.

	Nether\Ki::Queue(string EventName, callable Callback, bool Persist default false);
	
	Nether\Ki::Queue('my-first-event',function(){
		echo 'LOL EVENT LULZ';
		return;
	});

That will queue an event to happen the first time we call it.

	Nether\Ki::Flow(string EventName, array Args default null);

	Nether\Ki::Flow('my-first-event');
	
After the first Flow, that event will be removed from the queue and additional flows will not proc it until you requeue it. 

#### Rigging a Filter Event

To create a filter, you'll probably want to make a persistant event, with one of the few valid uses of pass-by-reference in PHP.

	Nether\Ki::Queue('app-hates-at-signs',function(&$input){
		if(is_string($input)) $input = str_replace('@','',$input);
	},true);

Then when you want to filter...

	Nether\Ki::Flow('app-hates-at-signs',[&$text]);
	
And that filter will continue to work for the duration of the app.
	

Install
-------

Use Composer.

	"require": { "netherphp/ki":"~1.0.0" }

