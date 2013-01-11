<?php

/**
 * 	@link https://app.zencoder.com/docs/guides/encoding-settings
 */
return array(

	/**
	 *	Any number of outputs configurations can be predefined for use by the encoder
	 *
     *  PLEASE NOTE THAT WE ARE NOT SUPPLYING A FULL "OUTPUT" JSON STRING HERE AS YOU'LL SEE IN THE ZENCODER EXAMPLES,
     *  WE ARE OMMITTING THINGS SUCH AS NOTIFICATIONS HERE AS THOSE ARE ADDED AUTOMATICALLY LATER BY THE BUNDLE. ONLY
     *  THE ACTUAL FILE ENCODING SETTINGS SHOULD BE DEFINED BELOW!
     *
	 * 	Provided by Zencoder
	 *  @link https://app.zencoder.com/docs/guides/encoding-settings
	 */
	'schemes' => array(

        //Simple audio encoding for MP3
		1 => '{
			"audio_bitrate": 128,
			"skip_video": true,
			"format": "mp3",
			"audio_codec": "mp3",
			"audio_channels": 2
			}
		',

		//Video for mobile devices
		2 => '{
			"audio_bitrate": 128,
			"audio_sample_rate": 44100,
			"height": 480,
			"width": 640,
			"max_frame_rate": 30,
			"video_bitrate": 1500,
			"h264_level": 3,
			"format": "mp4",
			}
		',

	),
);