<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\Schema;

use Nette;


/**
 * @internal
 */
final class Context
{
	use Nette\SmartObject;

	public $path = [];

	public $errors = [];

	public $dynamics = [];


	public function addError($message, $hint = null)
	{
		$pathStr = " '" . implode(' › ', $this->path) . "'";
		$message = str_replace(' %path%', $this->path ? $pathStr : '', $message);
		$this->errors[] = (object) ['message' => $message, 'path' => $this->path, 'hint' => $hint];
	}
}
