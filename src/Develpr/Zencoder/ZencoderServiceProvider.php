<?php namespace Develpr\Zencoder;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class ZencoderServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('develpr/zencoder'); #could be set to: $this->package('zencoder')

		//Include routes
		include __DIR__ . '/routes.php';

		AliasLoader::getInstance(array(
			'Zencoder' 						=> 	'Develpr\Zencoder\Zencoder',
			#'ZencoderEncodingException' 	=>  'Develpr\Zencoder\ZencoderEncodingException',
			#'ZencoderFileLocationException' =>  'Develpr\Zencoder\ZencoderFileLocationException',
			#'ZencoderFileFormatException' 	=>  'Develpr\Zencoder\ZencoderFileFormatException',
			#'ZencoderConnectionException' 	=>  'Develpr\Zencoder\ZencoderConnectionException'
		));
	}

	/**
	 * Register the {{full_package}} service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
