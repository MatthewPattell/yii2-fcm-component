Firebase cloud messaging for Yii2
===========================
Very simple FCM component for yii2

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Add

```
"matthew-p/yii2-fcm-component": "^1.0"
```

to the require section of your `composer.json` file.

and

```
{
  "type": "git",
  "url": "https://github.com/MatthewPattell/yii2-fcm"
},
{
  "type": "git",
  "url": "https://github.com/MatthewPattell/php-fcm"
}
```

to the repositories section of your `composer.json` file.

Usage
-----

Once the extension is installed, simply use it in your code by:

In config main.php:
```php
...
'components' => [
    ...
    'fcm' => [
        'class'  => \MP\Fcm\FcmComponent::class,
        'apiKey' => 'sampleKey',
    ],
    ...
],
...
```

Use:
```php
// Send to topic
$message = [
    'topic' => 'sampleChannelID',
    'data'  => [
        'sample1' => 'test'
    ],
];

$notification = ['key' => 'samplePushMessageKey'];


Yii::$app->fcm->pushTopic($message, $notification);

// Send to device
$message = [
    'device' => 'sampleDeviceToken',
    'data'   => [
        'sample1' => 'test'
    ],
];
Yii::$app->fcm->pushDevice($message, $notification);
```

See $notification configuration in \MP\Fcm\FcmComponent::NOTIFICATION_DEFAULT

That's all. Check it.