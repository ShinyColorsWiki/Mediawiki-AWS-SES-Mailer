# Mediawiki AWS SES Mailer
Mediawiki Alternative User Mailer that using AWS-SDK SES API
## Installation
1. `git clone https://github.com/ShinyColors/Mediawiki-AWS-SES-Mailer AwsSesMailer` on `/path/to/mediawiki/w/extensions`.
2. Run `composer install` on `/path/to/mediawiki/w/AwsSesMailer`
3. [SES Tutorial](https://aws.amazon.com/getting-started/tutorials/send-an-email/) 
    and set IAM.
4. Modify `LocalSettings.php`
## Configuration
```php
wfLoadExtension( 'AwsSesMailer' );
$wgAwsSesCredentials = [
	'key' => 'access key id',
	'secret' => 'secret access key',
	'token' => false
];
$wgAwsSesRegion = 'us-west-2'; # Your Region
```

## IAM
```json
{
    "Effect": "Allow",
    "Action": [
        "ses:*"
    ],
    "Resource": "*"
}
```