<?php

/*
 * --------------------------------------------------------------------------
 * Register some namespaces.
 * --------------------------------------------------------------------------
 */
Autoloader::namespaces(array(
	'Zencoder' => __DIR__ . DS
));

/*
 * --------------------------------------------------------------------------
 * Set the global alias.
 * --------------------------------------------------------------------------
 */
Autoloader::alias('Zencoder\\Zencoder', 'Zencoder');
Autoloader::alias('Zencoder\\ZencoderFileLocationException', 'ZencoderFileLocationException');
Autoloader::alias('Zencoder\\ZencoderEncodingException', 'ZencoderEncodingException');
Autoloader::alias('Zencoder\\ZencoderFileFormatException', 'ZencoderFileFormatException');
Autoloader::alias('Zencoder\\ZencoderConnectionException', 'ZencoderConnectionException');
