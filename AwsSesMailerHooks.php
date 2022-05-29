<?php

use \Aws\Ses\SesClient;
use \Aws\Exception\AwsException;

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

        if ( !( $wgAwsSesCredentials || $wgAwsSesRegion ) ) {
            return true;
        }

        $ses = new SesClient([
            'version' => 'latest',
            'region'  => $wgAwsSesRegion,
            'credentials' => $wgAwsSesCredentials
        ]);

        $param = array();

        foreach( $to as $recipient ) {
            $param['Destination'] = array(
                'ToAddresses' => (array) $recipient,
            );
        }

        $param['Source'] = $from;
        $param['ReplyToAddresses'] = array (
            $headers['Return-Path']
        );
        $param['Message'] = array(
            'Subject' => array(
                'Data' => $subject,
                'Charset' => 'utf-8',
            ),
            'Body' => array(
                'Text' => array(
                    'Data' => $body,
                    'Charset' => 'UTF-8',
                ),
            ),
        );



        try{
            $ses->sendEmail($param);
        } catch( AwsException $e) {
            return $e->getMessage();
        }

        return false;
    }
}
