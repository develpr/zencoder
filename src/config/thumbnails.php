<?php

/**
 * Please know that I've picked a few options out in this config below that seem useful to me, and I think
 * may be useful to a larger number of users. BUT, this is only a very small subset of the many features/options
 * that Zencoder supports. For example, you can specify thumbnails to be created at particular times in a video,
 * or rather then at specific time intervals you can specify the total number of screenshots to be taken.
 *
 * @link https://app.zencoder.com/docs/api/encoding/thumbnails
 */
return array(

	/**
	 * Do you want thumbnails to be automatically generated?
	 */
	'enabled' => true,

	//Optional - the label
	'prefix' => '',

	//How many SECONDS between creating thumbnails? If you'd prefer to use a number of thumbnails (instead of every x
	//seconds, y thumbnails evenly spaced) set interval to false or 0 as it will be checked first.
	'interval' => 60,

	//If you prefer, you can set a number of thumbnails to be captured - this number of thumbnails will happen
	//evenly throughout the video - this should be an integer value.
	'number' => false,


	/**
	 *   OUTPUT
	 *
	 * Where are the thumbnails going?
	 */
	'output' => array(

		//Which storage method should be used?
		'use' => 's3',

		'options' => array(

			// ftp://user:password@host.com/filepath/to/output/
			'ftp' => array(
				'base_url' => 'ftp://username:password@host/and/path/too/'
			),

			// If you're using S3, you'll want to be sure of proper permissions
			// @link https://app.zencoder.com/docs/guides/getting-started/working-with-s3
			's3' => array(
				'base_url' => 's3://your-bucket/',
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

);