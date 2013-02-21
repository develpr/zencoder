<?php namespace Develpr\Zencoder;

class ZencoderFile extends \Eloquent
{

	protected $request;
	protected $encodeWith;

	//Which table will be used to persist data?
	protected $table = 'zencoderfiles';

	// //todo: these aren't scoped right? Or are they?
	// const STATUS_NEW = 1;
	// const STATUS_PROCESSING = 2;
	// const STATUS_COMPLETED = 3;

	public function __construct($encodeWith = '')
	{
		parent::__construct();
		$this->encodeWith = $encodeWith;
	}

	/**
	 * Run the encoding job for the data in the model
	 */
	public function run()
	{
		try{
			//todo: catch file error
			$this->buildRequest();

			//todo: catch connection error
			$this->sendRequest();

		}catch(\Exception $e)
		{
			//do more
			return false;
		}

		return true;
	}

	/**
	 * Build up the request
	 * todo: validate data
	 * todo: error checking
	 */
	private function buildRequest()
	{
		//New blank request
		$request = new \stdClass();

		//Set test mode as configured in api config
		$request->test = \Config::get('zencoder::api.test');

		//Check which input format we want to use
		$inputToUse = \Config::get('zencoder::api.inputs.use');
		//Set the baseurl
		$this->input_path = \Config::get('zencoder::api.inputs.options.' . $inputToUse . '.base_url') . $this->original_filename;

		$request->input = $this->input_path;

		$encodeWith = 0;

		//todo: this is ugly, we should probably be doing this some other way
		if(!$this->encodeWith)
			$encodeWith = \Config::get('zencoder::api.default_encoding_profile');

		$encodingScheme = json_decode(\Config::get('zencoder::encoding.schemes.' . $encodeWith));
		
		//new filename
		//todo: need to find out how we should grab the file extension - add a config option?
		$this->encoded_filename = md5($this->original_filename . date("Y-m-d H:i:s")) . '.' . $encodingScheme->format;

		//Check which input format we want to use
		$outputToUse = \Config::get('zencoder::api.outputs.use');
		//Set the baseurl
		$outputBaseUrl = \Config::get('zencoder::api.outputs.options.' . $outputToUse . '.base_url');
		
		//todo: do we need to guess at or otherwise set the output_path here?
		
		$encodingScheme->base_url = $outputBaseUrl;
		$encodingScheme->filename = $this->encoded_filename;
		
		//Special check for S3 public permissions flag - this only really applies for S3 at this point
		if(strtolower($outputToUse) == 's3')
		{
			$public = \Config::get('zencoder::api.outputs.options.s3.public');
			if($public)
				$encodingScheme->public = 1;
		}
			
		
		$encodingScheme->notifications = array(

			array(
				"url" => \URL::to('/') . \Config::get('zencoder::api.notifications.url'),
				"format" => 'json'
			)
		);

		$email = \Config::get('zencoder::api.notifications.email');
		if($email)
		{
			$encodingScheme->notifications[] = $email;
		}


		$thumbnails = (boolean)\Config::get('zencoder::thumbnails.enabled');

		if($thumbnails)
		{
			$thumbnails = new \stdClass();
			$prefix = trim(\Config::get('zencoder::thumbnails.prefix'));
			if($prefix)
				$thumbnails->prefix = $prefix;

			$interval = \Config::get('zencoder::thumbnails.interval');
			$number = \Config::get('zencoder::thumbnails.number');

			if($interval)
				$thumbnails->interval = $interval;
			else if($number)
				$thumbnails->number = $number;

			//Check which output format we want to use
			$thumbnailOutputToUse = \Config::get('zencoder::thumbnails.output.use');
			//Set the baseurl
			$thumbnails->base_url = \Config::get('zencoder::thumbnails.output.options.' . $thumbnailOutputToUse . '.base_url');

			//Special check for S3 public permissions flag - this only really applies for S3 at this point
			if(strtolower($thumbnailOutputToUse) == 's3')
			{
				$public = \Config::get('zencoder::thumbnails.output.options.s3.public');
				if($public)
					$thumbnails->public = 1;
			}

			$encodingScheme->thumbnails = $thumbnails;

		}



		$request->output = array(
			$encodingScheme
		);

		$this->request = $request;

	}

	/**
	 * Send the request via cURL
	 * todo: validation, error handling, throwing the exceptions created
	 */
	private function sendRequest()
	{
		$apiEndpoint = \Config::get('zencoder::api.endpoint');
		$jsonRequest = json_encode($this->request);

		//Do curl request
		$ch = curl_init($apiEndpoint);

		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonRequest);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Accept: application/json',
			'Content-Type: application/json',
			'Content-Length: ' . strlen($jsonRequest),
			'Zencoder-Api-Key: ' . \Config::get('zencoder::api.api_key'))
		);

		$result = curl_exec($ch);
		$result = json_decode($result);

		if(isset($result->errors))
		{
			$errorMessage = '';
			foreach($result->errors as $error)
			{
				$errorMessage = $errorMessage . $error . ".   ";
			}
			Throw new ZencoderConnectionException($errorMessage);
		}

		$this->job_id = $result->id;

	}

}