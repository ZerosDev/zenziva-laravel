<?php

namespace ZerosDev\ZenzivaLaravel;

use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class Zenziva
{
	protected $config = [];
	protected $messages = [];
	protected $is_failed = true;

	public function __construct()
	{
		$this->config['userkey'] = config('zenziva.userkey', '');
		$this->config['passkey'] = config('zenziva.passkey', '');
		$this->messages['reguler'] = [];
		$this->messages['otp'] = [];
	}

	public function reguler($to, $message)
	{
		if( count($this->messages['otp']) > 0 ) {
			return $this;
		}

		$this->messages['reguler'] = [
			'to'	=> $to,
			'message'	=> $message
		];

		return $this;
	}

	public function otp($to, $otpCode)
	{
		if( count($this->messages['reguler']) > 0 ) {
			return $this;
		}

		$this->messages['otp'] = [
			'to'	=> $to,
			'otp_code'	=> $otpCode
		];

		return $this;
	}

	public function send()
	{
		$client = new Client;

		$reguler = $this->messages['reguler'];
		$otp = $this->messages['otp'];

		if( count($reguler) > 0 )
		{
			$request = $client->post('https://gsm.zenziva.net/api/sendsms/', [
				'headers'	=> [
					'User-Agent: Mozilla/5.0 (compatible; ZerosDev/Zenziva; +https://github.com/ZerosDev/Zenziva',
					'Content-Type: x-www-form-urlencoded',
					'Accept: application/json'
				],
				'body'		=> [
					'userkey'	=> $this->userkey,
					'passkey'	=> $this->passkey,
					'nohp'		=> $reguler['to'],
					'pesan'		=> $reguler['message'],
				]
			]);
		}
		elseif( count($otp) > 0 )
		{
			$request = $client->post('https://gsm.zenziva.net/api/sendOTP/', [
				'headers'	=> [
					'User-Agent: Mozilla/5.0 (compatible; ZerosDev/Zenziva; +https://github.com/ZerosDev/Zenziva',
					'Content-Type: x-www-form-urlencoded',
					'Accept: application/json'
				],
				'body'		=> [
					'userkey'	=> $this->userkey,
					'passkey'	=> $this->passkey,
					'nohp'		=> $otp['to'],
					'kode_otp'	=> $otp['otp_code'],
				]
			]);
		}

		dd($request);

		return $this->is_failed;
	}
}