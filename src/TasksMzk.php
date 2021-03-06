<?php

declare(strict_types=1);

namespace Nette\CodeChecker;

use Nette;
use Nette\Utils\Strings;


class TasksMzk
{
	public static function booleanValuesChecker(string &$contents, Result $result): void
	{
		$lines = explode("\n", $contents);
		foreach ($lines as &$line) {
			if (preg_match('~^(.*):( )?(yes|on|no|off)$~i', $line)) {
				$message = sprintf('Boolean values should be true/false: %s', $line);
				$result->error($message);
				$line = preg_replace('~:( )?(yes|on)$~i', ': true', $line);
				$line = preg_replace('~:( )?(no|off)$~i', ': false', $line);
			}
		}

		$implode = implode("\n", $lines);
		if ($contents !== $implode) {
			$result->fix($implode);
		}
	}

	public static function html5Checker(string $contents, Result $result): void {
		if (Strings::match($contents, '#<br\W*?\/>#')) {
			$result->error('contains XHTML');
		}
	}

	public static function entityChecker(string &$contents, Result $result): void {
		if (Strings::contains($contents, '@ORM\Entity') && !Strings::contains($contents, '@ORM\Entity(')) {
			$result->fix('Missing Entity`()`');
			$contents = str_replace('@ORM\Entity', '@ORM\Entity()', $contents);
		}

		if (Strings::contains($contents, '@' . 'ORM\Table()')) {
			$result->fix('Missing `name="table_name"`');
			$contents = str_replace('@' . 'ORM\Table()', '@ORM\Table(name="")', $contents);
		}

		if (Strings::contains($contents, '@' .'ORM\Table') && !Strings::contains($contents, '@' . 'ORM\Table(')) {
			$result->fix('Missing `name="table_name"`');
			$contents = str_replace('@' .'ORM\Table', '@ORM\Table(name="")', $contents);
		}
	}
}
