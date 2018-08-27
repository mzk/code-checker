<?php

namespace CodeCheckers;

use Nette\Utils\Strings;

class Html5Checker
{

	public static function createHtml5CheckerChecker()
	{
		return function ($checker, $s) {
			if (!$checker->is('latte')) {
				return;
			}

			if (Strings::match($s, '#<br\W*?\/>#')) {
				$checker->error('contains XHTML');
			}
		};
	}
}
