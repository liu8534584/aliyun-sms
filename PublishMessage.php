<?php
require_once("./vendor/autoload.php");

use AliyunMNS\Client;
use AliyunMNS\Requests\PublishMessageRequest;
use AliyunMNS\Exception\MnsException;
use AliyunMNS\Model\BatchSmsAttributes;
use AliyunMNS\Model\MessageAttributes;

class PublishMessage
{
    private $accessId;
    private $accessKey;
    private $endPoint;
    private $client;
    private $topicName;
    private $topic;
    private $instance;

    public function __construct($accessId, $accessKey, $endPoint, $topicName)
    {
        $this->accessId = $accessId;
        $this->accessKey = $accessKey;
        $this->endPoint = $endPoint;
        $this->topicName = $topicName;
        $this->client = new Client($this->endPoint, $this->accessId, $this->accessKey);
        $this->topic = $this->client->getTopicRef($this->topicName);
    }

//    public function getTplInstance($sign, $templateId)
//    {
//        $key = $sign . $templateId;
//        if (!isset($this->instance[$key])) {
//            $this->instance[$key] = new BatchSmsAttributes($sign, $templateId);
//        }
//        return $this->instance[$key];
//    }

    public function send($sign, $templateId, $phone, $value)
    {
        $tempInstance = new BatchSmsAttributes($sign, $templateId);
        /**
         * 生成SMS消息属性
         */
        $tempInstance->addReceiver($phone, $value);
        $messageAttributes = new MessageAttributes(array($tempInstance));
        /**
         * 设置SMS消息体（必须）
         * 注：目前暂时不支持消息内容为空，需要指定消息内容，不为空即可。
         */
        $messageBody = "smsmessage";
        /**
         * 发布SMS消息
         */
        $request = new PublishMessageRequest($messageBody, $messageAttributes);
        try {
            $result = $this->topic->publishMessage($request);
            $res['success'] = $result->succeed;
            $res['messageId'] = $result->getMessageId();
            return $res;
        } catch (MnsException $e) {
            return false;
        }
    }
}

?>
