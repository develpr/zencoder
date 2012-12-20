Installation
------------
------------

**For _Zencoder_ documentation, check out [Zencoder's own docs/support section](https://app.zencoder.com/docs)**. The not-so-ironically named [Getting Started](https://app.zencoder.com/docs/guides/getting-started) is perhaps a good place to start and is highly recommended - after all, this bundle only offers a fraction of the features Zencoder's API offers, you may find something great in the docs!

Moving on...



Initial setup w/ Laravel
------------------------

After installing via `php artisan bundle:install zencoder` you'll need to add a section to your application/bundles.php file:

	'zencoder' => array(
		'hanldes' => 'zencoder',
		'auto' => true
	),
	
This allows the zencoder bundle to handle the http://www.your-site.com/**zencoder** route which is required for handling callbacks. At some point there may also be a simple report available somewhere along this route to enable you to see the status of an encoding job or to even view the progress as it happens (this is supported by Zencoder's API).



Initial API configuration
-------------------------

In the `bundles/zencoder/config/` directory you'll need to edit the `api.php` config file, which contains the most basic configuration options for Zencoder, including perhaps most importantly the `'api_key'` value. This needs to be set to communicate with Zencoder. You can [read more about the pricing and sign up](http://zencoder.com/en/pricing) options, or you can just [sign up directly for a test account](https://app.zencoder.com/signup). Once you've created your account you'll need to sign in and visit the [API](https://app.zencoder.com/api) page, which will contain various API keys. For the purposes of this guide I'd recommend simply using a full access API key as it will give full unrestricted access to all functions on your account, however the integration-only api key would work as well. If you are doing anything other then testing then you'll more then likely want to read more about the different options at (Zencodre.com)[http://www.zencoder.com].



Configuring file input and output locations
-------------------------------------------

Zencoder can handle file [inputs](https://app.zencoder.com/docs/api/encoding/job/input) and outputs in a number of ways, but this bundle currently only supports S3 and FTP transfers (HTTP will be added soon), S3 being the most tested and most highly recommended option. See the comments in the api.php file for more info, but essentially you'll need to require either a full path to an FTP directory where input (files to be encoded) will be stored, or the S3 path where you'll be storing files to be processed. Note that this is the "base url", not the location of an actual file but the path to the container. You'll need to make sure you setup permissions and any security features needed yourself, and if you're using S3 you may want to check out Zencoder's [docs on the subject](https://app.zencoder.com/docs/guides/getting-started/working-with-s3).

I personally had some small issues with permissions when setting up S3 buckets, if you want your finished output files to be publicly available on S3 be sure to set the `'public'` key to boolean `true`;

There are many other configuration options available in api.php, but each is documented in the api.php so please give it a read.



Testing and basic use
---------------------


Actually encoding files with this bundle is simple in practice (in practice in theory ;)). *You'll need to handle file uploads/validation/etc yourself*, but once you have the input file in the location specified in the config/api.php `'inputs'` you can simply call `Zencoder::create('filename.mov');` inside of your application. This will return a `\Zencoder\Models\ZencoderFile` object which contains some not-particularly-useful information that you may or may not want. In my own application (I'm using Zencoder to encode audio) I'm doing something like:

``





