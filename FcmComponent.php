<?php
/**
 * Created by PhpStorm.
 * User: Yarmaliuk Mikhail
 * Date: 25.01.2018
 * Time: 21:07
 */

namespace MP\Fcm;

use paragraph1\phpFCM\Message;
use paragraph1\phpFCM\Recipient\Device;
use paragraph1\phpFCM\Recipient\Topic;
use Psr\Http\Message\ResponseInterface;
use understeam\fcm\Client;

/**
 * Class    FcmComponent
 * @package MP\Fcm
 * @author  Yarmaliuk Mikhail
 * @version 1.0
 */
class FcmComponent extends Client
{
    const NOTIFICATION_DEFAULT = [
        'key'        => 'default',
        'title'      => '',
        'body'       => '',
        'badge'      => 0,
        'priority'   => Message::PRIORITY_NORMAL,
        'timeToLife' => '300s',
    ];

    const TARGET_TOPIC  = 'topic';
    const TARGET_DEVICE = 'device';

    /**
     * Send push notification to topic
     *
     * @param array $message
     * @param array $notification
     *
     * @return ResponseInterface
     */
    public function pushTopic(array $message, array $notification = self::NOTIFICATION_DEFAULT): ResponseInterface
    {
        return $this->sendPush($message, $notification, self::TARGET_TOPIC);
    }

    /**
     * Send push notification to device
     *
     * @param array $message
     * @param array $notification
     *
     * @return ResponseInterface
     */
    public function pushDevice(array $message, array $notification = self::NOTIFICATION_DEFAULT): ResponseInterface
    {
        return $this->sendPush($message, $notification, self::TARGET_DEVICE);
    }

    /**
     * Send push notifications
     *
     * @param array  $message
     * @param array  $notification
     * @param string $type
     *
     * @return ResponseInterface
     */
    private function sendPush(array $message, array $notification = [], string $type): ResponseInterface
    {
        $notification = array_merge(self::NOTIFICATION_DEFAULT, $notification);

        $note = $this->createNotification($notification['title'], $notification['body']);

        if (!empty($notification['color'])) {
            $note->setColor($notification['color']);
        }

        if (!empty($notification['icon'])) {
            $note->setIcon($notification['icon']);
        }

        if (!empty($notification['sound'])) {
            $note->setSound($notification['sound']);
        }

        $note->setBadge($notification['badge']);

        $target = NULL;

        switch ($type) {
            case self::TARGET_TOPIC:
                $target = new Topic($message['topic']);
            break;

            case self::TARGET_DEVICE:
                $target = new Device($message['token']);
            break;
        }

        $msg = $this->createMessage();
        $msg->addRecipient($target)
            ->setNotification($note)
            ->setPriority($notification['priority'])
            ->setData($message['data'] ?? []);

        if (!empty($message['contentAvailable']) && $message['contentAvailable']) {
            $msg->setContentAvailable();
        }

        if ($collapseKey = $message['key'] ?? $notification['key']) {
            $msg->setCollapseKey($message['key'] ?? $notification['key']);
        }

        return $this->send($msg);
    }
}