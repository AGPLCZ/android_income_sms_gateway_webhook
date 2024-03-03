# Incoming SMS to URL forwarder
Upload SMS messages to your database, and view the messages on the web page.

> [!IMPORTANT]  
> - Version 2.2.4  new -> "fromName": "%fromName%",
> - Version 2.2.3 -> apk from fdroid is not compatible with PHP files

## How to use APP SMS PHP - config.php
Set the config
$conn = new mysqli("server", "username", "pass", "database_name");

### Create database tables
CREATE TABLE `sms_messages`
MYSQL -> Run SQL query(s) -> sms_messages.sql

### PHP files SMS
upload files sms/
index.php
sms.php
config.php
sms_messages.sql

## How to use APP
1. Set sender phone "number" or "name" or any SMS use "*"
2. URL https://yourpage.com/sms/sms.php
3. Every incoming SMS will be sent immediately to the provided URL.

> [!NOTE]
> If the response code is not 2XX or the request ended with a connection error, the app will try to send again up to 10 times.
>Minimum first retry will be after 10 seconds, later wait time will increase exponentially.
>If the phone is not connected to the internet, the app will wait for the connection before the next attempt.
>If at least one Forwarding config is created and all needed permissions granted - you should see F icon in the status bar, means the app is listening for the SMS.

### Request info
HTTP method: POST  
Content-type: application/json; charset=utf-8  

Sample payload:  
```json
{
     "from": "%from%",
     "fromName": "%fromName%",
     "text": "%text%",
     "sentStamp": "%sentStamp%",
     "receivedStamp": "%receivedStamp%",
     "sim": "%sim%"
}
```

Available placeholders:
%from%
%fromName%
%text%
%sentStamp%
%receivedStamp%
%sim%

### Request example
Use this curl sample request to prepare your backend code
```bash
curl -X 'POST' 'https://yourwebsite.com/path' \
     -H 'content-type: application/json; charset=utf-8' \
     -d $'{"from":"1234567890","text":"Test"}'
```

### Process Payload in PHP scripts

Since $_POST is an array from the url-econded payload, you need to get the raw payload. To do so use file_get_contents:
```php
$payload = file_get_contents('php://input');
$decoded = json_decode($payload, true);
```

## Screenshots
<img alt="Incoming SMS Webhook Gateway screenshot 1" src="https://raw.githubusercontent.com/bogkonstantin/android_income_sms_gateway_webhook/master/fastlane/metadata/android/en-US/images/phoneScreenshots/1.png" width="30%"/> <img alt="Incoming SMS Webhook Gateway screenshot 2" src="https://raw.githubusercontent.com/bogkonstantin/android_income_sms_gateway_webhook/master/fastlane/metadata/android/en-US/images/phoneScreenshots/2.png" width="30%"/> <img alt="Incoming SMS Webhook Gateway screenshot 3" src="https://raw.githubusercontent.com/bogkonstantin/android_income_sms_gateway_webhook/master/fastlane/metadata/android/en-US/images/phoneScreenshots/3.png" width="30%"/>

## Developers
https://github.com/bogkonstantin/android_income_sms_gateway_webhook
https://github.com/scottmconway/android_income_sms_gateway_webhook

## Download apk

Download apk from [release page](https://github.com/scottmconway/android_income_sms_gateway_webhook/releases)

Or download it from F-Droid

[<img src="https://fdroid.gitlab.io/artwork/badge/get-it-on.png"
     alt="Get it on F-Droid"
     height="80">](https://f-droid.org/packages/tech.bogomolov.incomingsmsgateway/)
