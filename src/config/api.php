<?php

return array(

	/**
	 * Test mode - this will pass in 'test':true and limit outputs to 5 seconds
	 */
	'test' => true,

	/**
	 * Where is Zencoder API located (url)
	 */
	'endpoint' => 'https://app.zencoder.com/api/v2/jobs',

	/**
	 * API key can be a 'Full Access API Key' or 'Integration-Only API Keys'
	 * At some point when the zencoder bundle is a bit more mature support for
	 * read-only api keys may have use, but for the time being it provides no
	 * function.
	 *
	 * see: https://app.zencoder.com/api
	 */
	'api_key' => 'your-full-key',


	// json encoding only at this point, xml would be possible in the future if needed
	'container' => 'json',

	//Default encoding profile to use
	'default_encoding_profile' => 1,

	/**
	 *   OUTPUTS
	 *
	 * Where do you want the files put
	 */
	'outputs' => array(

		//Which storage method should be used?
		'use' => 's3',

		'options' => array(

			// ftp://user:password@host.com/filepath/to/output/
			'ftp' => array(
				'base_url' => 'ftp://username:password@host/and/path/to/'
			),

			// If you're using S3, you'll want to be sure of proper permissions
			// @link https://app.zencoder.com/docs/guides/getting-started/working-with-s3
			's3' => array(
				'base_url' => 's3://your-bucket/',
				'public' => true,
                //CAREFUL - setting public to true will make all finished and encoded files public by default
				'public' => false
			),

			//todo: needs additional testing
			'cloudfiles' => array(
				//Can add region here, such as uk ('cf+uk://....')
				'base_url' => 'cf://username:api_key@container/'
			)

		),

	),

	/**
	 *    INPUTS
	 *
	 * Where are the files coming from?
	 */
	'inputs' => array(
		//Which storage option should be used to retrieve original file?
		'use' => 's3',

		'options' => array(

			// ftp://user:password@host.com/filepath/to/input/
			'ftp' => array(),

			// If you're using S3, you'll want to be sure of proper permissions
			// @link https://app.zencoder.com/docs/guides/getting-started/working-with-s3
			's3' => array(
				'base_url' => 's3://your-bucket/',
			),

			//todo: needs additional testing
			'cloudfiles' => array(
				//Can add region here, such as uk ('cf+uk://....')
				'base_url' => 'cf://username:api_key@container/'
			)

		),

	),

	'notifications' => array(

		//Optionally include an email address if you'd like an email dispatched when a file has been encoded
		'email' => '',

		//Don't change this unless you know what you're doing!
		//Callback URL to be notified when encoding is complete
		'url' => '/zencoder/callback',

	),
);