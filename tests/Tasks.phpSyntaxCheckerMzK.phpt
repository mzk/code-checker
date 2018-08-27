<?php

declare(strict_types=1);

use Nette\CodeChecker\Result;
use Nette\CodeChecker\TasksMzk;
use Tester\Assert;

require __DIR__ . '/bootstrap.php';


test(function (): void {
	$result = new Result;
	$contents = '';
	TasksMzk::booleanValuesChecker($contents, $result);
	Assert::same([], $result->getMessages());
});

test(function (): void {
	$result = new Result;
	$contents = 'common:
	php:
		include_path: %libsDir%
		date.timezone: Europe/Prague
		auto_detect_line_endings: no
		# zlib.output_compression: yes';
	TasksMzk::booleanValuesChecker($contents, $result);
	Assert::count(3, $result->getMessages());
});

test(function (): void {
	$result = new Result;
	$contents = 'common:
	php:
		include_path: %libsDir%
		date.timezone: Europe/Prague
		auto_detect_line_endings: false
		# zlib.output_compression: true';
	TasksMzk::booleanValuesChecker($contents, $result);
	Assert::count(0, $result->getMessages());
});

test(function (): void {
	$result = new Result;
	$contents = '<p>lol<br></p>';
	TasksMzk::html5Checker($contents, $result);
	Assert::count(0, $result->getMessages());
});

test(function (): void {
	$result = new Result;
	$contents = 'lol<br><br/>';
	TasksMzk::html5Checker($contents, $result);
	Assert::count(1, $result->getMessages());
});

test(function (): void {
	$result = new Result;
	$contents = '@ORM\Table(name="foo")';
	TasksMzk::entityChecker($contents, $result);
	Assert::count(0, $result->getMessages());
});

test(function (): void {
	$result = new Result;
	$contents = '@ORM\Table()';
	TasksMzk::entityChecker($contents, $result);
	Assert::count(1, $result->getMessages());
});
