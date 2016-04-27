<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
include_once("php_img.php");
class ImageProcessor{


    public function base64ToJpeg($base64_string)
    {

        $filename_path = md5(time().uniqid()).".jpg";
        $decoded=base64_decode($base64_string);
        $fullPath = "uploads/tmp/".$filename_path;
        file_put_contents($fullPath,$decoded);

        $this->thumbPhoto($fullPath);
    }

    private function thumbPhoto($fullPath)
    {
        $thumbnail = "uploads/tmp/thumb_".end(explode('/', $fullPath));
        $wthumb = 150;
        $hthumb = 150;
        img_thumb($fullPath, $thumbnail, $wthumb, $hthumb, "jpg");
    }
}

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();
$imageProcessor = new ImageProcessor();
$channel->queue_declare('avatarUpload', false, false, false, false);

$callback = function($msg) use($imageProcessor) {
	$imageProcessor->base64ToJpeg($msg->body);
  echo " Received photo \n";

};

$channel->basic_consume('avatarUpload', '', false, true, false, false, $callback);

while(count($channel->callbacks)) {
    $channel->wait();
}