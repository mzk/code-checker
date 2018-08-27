<?php

namespace CodeCheckers;

use Nette\Utils\Strings;

class LineLengthChecker
{

	public static function createLineLengthChecker($warningLineLength = 140, $errorLineLength = 210)
	{
		return function ($checker, $s) use ($warningLineLength, $errorLineLength) {
			if (!$checker->is('php') && !$checker->is('phpt')) {
				return;
			}

			$i = 0;
			foreach (explode("\n", $s) as $line) {
				$i++;
				$line = str_replace("\t", str_repeat(' ', 4), $line);
				$lineLength = Strings::length($line);
				$message = sprintf('Line %s have %d characters', Strings::truncate(Strings::trim($line), 30), $lineLength);

				if ($lineLength > $errorLineLength) {
					$checker->error($message, $i);
					continue;
				}
				if ($warningLineLength !== null && $lineLength > $warningLineLength) {
					$checker->warning($message, $i);
				}
			}
		};
	}
}
