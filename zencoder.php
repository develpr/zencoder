<?php

namespace Zencoder;

use Laravel\Config;


/**
 * Zencoder Exceptions
 */
class ZencoderEncodingException extends \Exception {}
class ZencoderFileLocationException extends \Exception {}
class ZencoderFileFormatException extends \Exception {}
class ZencoderConnectionException extends \Exception {}

class Zencoder
{
	
	const STATUS_NEW = 1;
	const STATUS_PENDING = 2;
	const STATUS_FINISHED = 3;
	const STATUS_FAILED = 4;
	
	public static function create($input, $encodingScheme = 1)
	{

		$newZencoderFile = new Models\ZencoderFile();

		//$newZencoderFile->encoding_scheme = $encodingScheme;

		$newZencoderFile->status = 1;
		$newZencoderFile->original_filename = $input;

		if($newZencoderFile->run($encodingScheme))
		{
			$newZencoderFile->save();
		}

		return $newZencoderFile;

	}

}