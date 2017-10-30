# MQNotificationBundle [![Build Status](https://travis-ci.org/fintem/MQNotificationBundle.svg?branch=master)](https://travis-ci.org/fintem/MQNotificationBundle) [![Coverage Status](https://coveralls.io/repos/github/fintem/MQNotificationBundle/badge.svg?branch=master)](https://coveralls.io/github/fintem/MQNotificationBundle?branch=master)

Installation
============

#### Download the bundle and dependencies

```bash
$ composer require fintem/mq-notification-bundle
```

#### Enable the bundle by adding it to the list of registered bundles

```php
<?php
// ...
        $bundles = [
            // ...
            new OldSound\RabbitMqBundle\OldSoundRabbitMqBundle(),
            new Fintem\MQNotificationBundle\MQNotificationBundle(),
        ];

// ...
}
```

Configuration
============

#### Add the old_sound_rabbit_mq section in your configuration file and describe connection. More info [here.](https://github.com/php-amqplib/RabbitMqBundle)

#### MQNotificationBundle config:
```yaml
mq_notification:
    mq_connection_name: default # connection name described under old_sound_rabbit_mq
    service_name: your_app_name # custom your application name
```

Usage
============

#### Dispatch event to push notification using symfony event-dispatcher
```php
<?php

use Fintem\MQNotificationBundle\Event\NotifyEvent;

$notifyOnTerminate = false; // push notification on kernel/console terminate/exception
$event = new NotifyEvent('test_message', ['some' => 'data'], $notifyOnTerminate);
$this->dispatcher->dispatch(NotifyEvent::NAME, $event);
```

#### Run a consumer on service or application where you want to get notification
```bash
$ app/console rabbitmq:consumer notification
```

#### When a consumer receives notification, it dispatch two NotificationReceivedEvent events in order:
* notification_received
* notification_received.message_name `e.g. notification_received.user_created`

##### Create a listener/subscriber to listen those events
```php
<?php

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Fintem\MQNotificationBundle\Event\NotificationReceivedEvent;

class NotificationSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            'notification_received' => ['onNotificationReceived', 0],
            'notification_received.user_created' => ['onUserCreated', 0],
        ];
    }
    
    public function onNotificationReceived(NotificationReceivedEvent $event)
    {
    }
    
    public function onUserCreated(NotificationReceivedEvent $event)
    {
        // $user = $event->getData();
    }
}
```

##### Register listener/subscriber in service container and tag them with the ``mq_notification.event_listener`` and ``mq_notification.event_subscriber`` tags