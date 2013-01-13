<?php

namespace Develpr\Zencoder;

/**
 * This controller is intended to providing supporting controller routes for the Zencoder bundle/package.
 */
class ZencoderController extends \Controller
{

	/**
	 *	Callback function - the zencoder api config will set this method by default as the method that the Zencoder service
	 *  will respond to when a notification is fired. Currently it only uses a small fraction of the data that Zencoder v2 notifications
	 *  supply, but it can be easily expanded on in the future.
	 */
	public static function callback()
	{
		$notification = json_decode(trim(file_get_contents('php://input')));

		//todo: catch the case that nothing comes back, or a malformted notification
		$zencoderFile = ZencoderFile::where('job_id', '=', $notification->job->id)->first();

		//todo: shouldn't have to do! figure out why this isn't getting set
		$zencoderFile->exists = true;


		//Did the job fail!?
		if($notification->job->state == 'failed')
		{
			//OH NO!
			$zencoderFile->status = Zencoder::STATUS_FAILED;
			$responses = Event::fire('zencoder.failed', array($zencoderFile, $notification));

		}
		else if($notification->job->state == 'finished')
		{
			$zencoderFile->status = Zencoder::STATUS_FINISHED;
			$zencoderFile->output_path = $notification->output->url;
			$zencoderFile->duration = $notification->output->duration_in_ms;
			$zencoderFile->output_size = $notification->output->file_size_in_bytes;
			$responses = Event::fire('zencoder.finished', array($zencoderFile, $notification));
		}

		$zencoderFile->save();

		//All done here, nothing more to do!
		return;
	}

}