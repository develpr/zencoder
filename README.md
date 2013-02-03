Some info
---------
---------

Disclaimer/cop out 
------------------

This is my first bundle, it is still very much in active development, and I am very new to Laravel - please feel free to make any recommendations or if you see issues _please_ let me know. I needed the functionality this bundle provides which is why I created it, but I'm releasing it here because I'm interested in being part of the community at large, and hopefully giving something back to somebody. 

What it does
------------

This bundle provides a basic wrapper around the Zencoder API to allow easy integration with your video/audio heavy web application.

For those not familiar, Zencoder is a service that encodes/transcodes video and audio files. There is a seemingly exhaustive list of audio and video formats supported by the service.

This takes the already great API and makes it easy to use in your Laravel project. Some of the features that this bundle aims to offer include:

A simple ZencoderFile model that allows you to treat it as a file asset, to get an assets location, status, or other attributes about the file.

* Zencoder notification support - automatically creates an endpoint and sends the appropriate endpoint through the API to  zencoder, so your application will be notified automatically when an encoding job completes (useful Laravel Events are fired as well for "finished" or "failed" jobs)
* Basic S3 support (via Zencoder), as well as FTP account support for file inputs/outputs (S3 is HIGHLY recommended as it's a great service!).
* Simple setup and easy configuration, while leaving you the ability to easily create your own encoding settings as required
Well documented code so if (when!) this bundle doesn't quite meet your needs you can find your way around easily enough to change what you need.
* At the end of the day, Zencoder and their API get's most of the credit here, this really just a wrapper that makes using Zencoder a bit easier in Laravel.

*NOTE:* _This bundle won't do much without a Zencoder account. You can set the application in "test" mode and test your application without paying a dime, but again, you'll at least need to signup for an account. See Zencoder for more info._





Installation
------------
------------

**For _Zencoder_ documentation, check out [Zencoder's own docs/support section](https://app.zencoder.com/docs)**. The not-so-ironically named [Getting Started](https://app.zencoder.com/docs/guides/getting-started) is perhaps a good place to start and is highly recommended - after all, this bundle only offers a fraction of the features Zencoder's API offers, you may find something great in the docs!

Moving on...



Initial setup w/ Laravel
------------------------

After installing via `php artisan bundle:install zencoder` and running the zencoder migration(s) `php artisan migrate zencoder` you'll need to add a section to your /application/bundles.php file:

	'zencoder' => array(
		'hanldes' => 'zencoder',
		'auto' => true
	),
	
This allows the zencoder bundle to handle the http://www.your-site.com/**zencoder** route which is required for handling callbacks. At some point there may also be a simple report available somewhere along this route to enable you to see the status of an encoding job or to even view the progress as it happens (this is supported by Zencoder's API).


Setting up your local environment for callbacks
-----------------------------------------------

For local development or if for some other reason your application isn't yet web accessible, you'll want to take a look at Zencoder's thoughtful `zencoder_fetcher` utility (requires ability to install ruby gems), which [you can read more about here](https://app.zencoder.com/docs/guides/advanced-integration/getting-zencoder-notifications-while-developing-locally). Long story short, this is a command line utility written in Ruby that will fetch notifications from Zencoder directly, then POST them to your local environment. On my command line I run something like this:

	zencoder_fetcher --url http://tfs.com/zencoder/callback anotmyrealapikey31123k1l23lkj123b



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
---------------------


Simple testing
--------------

Actually encoding files with this bundle is simple in practice (in practice, in theory ;)). *You'll need to handle file uploads/validation/etc yourself*, but once you have the input file in the location specified in the config/api.php `'inputs'` you can simply call `Zencoder::create('filename.mov');` inside of your application. 

Inside of /application/routes.php file you can create a very simple route for testing:

	Route::get('zencodertest', function(){
		$file = Zencoder::create('testsound.wav');	
		var_dump($file);	
	});

In this example, you'd need to have the file `testsound.wav` in your `inputs` base directory. In my own application, that means my S3 bucket `s3://myapplication/raw/audio/testsound.wav` (`s3://myapplication/raw/audio/` would be my base_url defined in api.php).

`$file` above would be a ZencoderFile instance, and would contain some basic information about the file. At this point the information the file contains isn't so interesting, it's nothing you don't most likely already know at this point in the application's execution.


Potential basic usages
----------------------

Continuing with this `\Zencoder\Models\ZencoderFile` object which contains some not-particularly-useful information that you may or may not want: In my own application (I'm using Zencoder to encode audio) I'm doing _something_ like 

	//(*warning: sudoish code*)
	$newUserRecording->file(Zencoder::create('file_upload.wav'));

to create a relationship between a `ZencoderFile` and my more application specific model (in this case an audio file uploaded bye a user). I can then later go through and in my application retrieve the ZencoderFile from my model to out put to the user, something like

	//(*omg sudoish code again w/o Blade!*)
	<a href="<?php echo $notSoNewRecording->getZencoderFile()->output_path; ?>">install.rootkit.mp3</a>
	
Great stuff! You can also check out your database table (assuming you have `zencoder_fetcher` setup or are testing this on a publicly available domain!).

A small subset of the interesting things that will come back when a job is complete include:

* the output path that the file can be accessed at (assuming of course permission/paths are configured in this way on your side)
* file size (bytes)
* duration of the file (seconds)
* pixel width/height of file (assuming it's a video)
* file container (file type)
* encoding information

... and more (or less) to come!



Encoding settings
-----------------
-----------------

Honestly, there isn't much worth talking about here, only because the topic of Zencoder's encoding options is huge and best covered by [this here](https://app.zencoder.com/docs/api/encoding) and by using [Zencoder's API request] builder(https://app.zencoder.com/request_builder) to test out various configurations. It's also worth checking out the [recommended encoding settings](https://app.zencoder.com/docs/guides/encoding-settings) which might very well have some pre-defined encoding options worth reading over.

*One thing worth noting is that the `/application/bundles/zencoder/config/encoding.php` (let's call it the encoding config file) does not contain complete _outputs_ json string as you'll see in Zencoder docs, so you can't _QUITE_ just copy/paste from Zencoder examples - you'll want to exclude the notifications and filepath information.*

So, the "sample request" build in the [API Request Builder](https://app.zencoder.com/request_builder) might look like this...

	{
	  "test": true,
	  "input": "http://s3.amazonaws.com/zencodertesting/test.mo",
	  "output": [
	    {
	      "format": "3g2",
	      "video_codec": "theora",
	      "audio_codec": "vorbis",
	      "quality": 4,
	      "audio_quality": 3,
	      "speed": 1,
	      "audio_sample_rate": 23,
	      "audio_channels": 2,
	      "public": 1,
	      "notifications": [
	        {
	          "url": "http://mysite.com/zencoder/callback",
	          "format": "json",
	          "event": "output_finished"
	        },
	        "blahblah@afasdfads.com"
	      ],
	      "h264_reference_frames": 1,
	      "h264_profile": "main",
	      "h264_level": 1.3,
	      "tuning": "animation"
	    }
	  ]
	}
	
...but in the encoding config file you'll want to leave out the notification information, as well as anything really other then the actual _encoding relation options_ so you'll end up with...

	{
      "format": "3g2",
      "video_codec": "theora",
      "audio_codec": "vorbis",
      "quality": 4,
      "audio_quality": 3,
      "speed": 1,
      "audio_sample_rate": 23,
      "audio_channels": 2,  
      "h264_reference_frames": 1,
      "h264_profile": "main",
      "h264_level": 1.3,
      "tuning": "animation"
    }

... something like that! If you look closely, you'll see that a few things are missing, notifications, the public option (set in config), and of course all the input options. 

*At this point only a single output is supported, but it should be quite easy to add an unlimited number of outputs.*


Thumbnails
----------

Zencoder has a large number of powerful options for generating thumbnail images during a video encoding job. **only a small subset** of these options are actually easily configurable via this package, however it would be very easy to add your own options to the configuration. For the time being, all that is needed to enable thumbnails is that you need to encode a video and be sure that the `enabled` flag is set to true in the `thumbnails.php` config file.

For the time being the additional options are documented/commented in the config, so I'll leave it at that. Note that the default encoding container format is actually an mp3 and thumbnails are not generated for audio only files.




Events
------

An event is fired when Zencoder comes back with a notification. A different event is fired for a _finished_ job (`zencoder.finished`) then a _failed_ job (`zencoder.failed`). These events come with the ZencoderFile as well as the full notification object Zencoder sent to your application.





**Develping....**


