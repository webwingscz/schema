<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\Schema;

use Nette;


/**
 * Schema validator.
 */
class Processor
{
	use Nette\SmartObject;

	/**
	 * Normalizes and validates data. Result is a clean completed data.
	 * @return mixed
	 * @throws ValidationException
	 */
	public function process(Schema $schema, $data, Context $context = null)
	{
		$context = $context ?: new Context;
		$data = $schema->normalize($data, $context);
		$this->throwsErrors($context);
		$data = $schema->complete($data, $context);
		$this->throwsErrors($context);
		return $data;
	}


	/**
	 * Normalizes and validates and merges multiple data. Result is a clean completed data.
	 * @return mixed
	 * @throws ValidationException
	 */
	public function processMultiple(Schema $schema, array $dataset, Context $context = null)
	{
		$context = $context ?: new Context;
		$flatten = null;
		$first = true;
		foreach ($dataset as $data) {
			$data = $schema->normalize($data, $context);
			$this->throwsErrors($context);
			$flatten = $first ? $data : $schema->merge($data, $flatten);
			$first = false;
		}
		$data = $schema->complete($flatten, $context);
		$this->throwsErrors($context);
		return $data;
	}


	private function throwsErrors(Context $context): void
	{
		$messages = [];
		foreach ($context->errors as $error) {
			$messages[] = $error->message;
		}
		if ($messages) {
			throw new ValidationException($messages[0], $messages);
		}
	}
}
