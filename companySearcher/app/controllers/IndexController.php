<?php
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class IndexController extends MainController
{

	public function indexAction()
	{
        $message = '';
        if($this->request->get('avatar')){
            $message = 'Avatar uploaded';
        }
		$this->view->companies = $this->getAll()["hits"]["hits"];
        $this->view->message = $message;

		$this->assets
            ->addCss('bower_components/dist/css/bootstrap.min.css');

	}

    public function avatarAction()
    {
        $this->assets
            ->addCss('bower_components/dist/css/bootstrap.min.css');
    }

    public function uploadAvatarAction()
    {
        if ($this->request->hasFiles() == true) {
            $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
            $channel = $connection->channel();

            $channel->queue_declare('avatarUpload', false, false, false, false);

                //Print the real file names and their sizes
                foreach ($this->request->getUploadedFiles() as $file){
                    $imagedata = file_get_contents($file->getTempName());
                    $base64 = base64_encode($imagedata);

                    $msg = new AMQPMessage($base64);

                    $channel->basic_publish($msg, '', 'avatarUpload');
                }
            $channel->close();
            $connection->close();
        }
        return header("location:/?avatar=true");
    }

	public function syncAction()
	{
		$companies = Company::find(['limit' => 10000]);
		$this->syncComapnyData($companies);
		var_dump('MySql -> elasticSearch synced!');
		$response = new \Phalcon\Http\Response();
		return $response->redirect();
	}

}
