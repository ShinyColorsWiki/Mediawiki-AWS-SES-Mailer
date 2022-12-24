<?php

use Aws\Exception\AwsException;
use Aws\Ses\SesClient;

class AwsSesMailerHooks {
	/**
	 * Send a mail using AWS SES API
	 *
	 * @param array $headers
	 * @param array $to
	 * @param MailAddress $from
	 * @param string $subject
	 * @param string $body
	 * @return bool
	 * @throws Exception
	 */
	public static function onAlternateUserMailer(
		array $headers,
		array $to,
		MailAddress $from,
		$subject,
		$body
	) {
		global $wgAwsSesCredentials, $wgAwsSesRegion;

		if ( !( $wgAwsSesCredentials && $wgAwsSesRegion ) ) {
			return true;
		}

		$ses = new SesClient( [
			'version' => 'latest',
			'region'  => $wgAwsSesRegion,
			'credentials' => $wgAwsSesCredentials
		] );

		$param = [];

		foreach ( $to as $recipient ) {
			$param['Destination'] = [
				'ToAddresses' => (array)$recipient,
			];
		}

		$param['Source'] = $from;
		$param['ReplyToAddresses'] = [
			$headers['Return-Path']
		];
		$param['Message'] = [
			'Subject' => [
				'Data' => $subject,
				'Charset' => 'utf-8',
			],
			'Body' => [
				'Text' => [
					'Data' => $body,
					'Charset' => 'UTF-8',
				],
			],
		];

		try{
			$ses->sendEmail( $param );
		} catch ( AwsException $e ) {
			return $e->getMessage();
		}

		return false;
	}
}
