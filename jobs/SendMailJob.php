<?php

namespace app\jobs;

use app\modules\admin\modules\mail\models\Addresser;
use app\modules\admin\modules\mail\models\Template;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use yii\helpers\Console;
use yii\queue\JobInterface;

class SendMailJob extends Job implements JobInterface
{
    public $addressee;
    public $addressee_name;
    public $addresser_id;
    public $template_id;

    public function execute($queue)
    {
        // Send email with job params
        $addresserModel = Addresser::findOne(['id' => $this->addresser_id]);
        $templateModel = Template::findOne(['id' => $this->template_id]);
        $this->info("addresser_id: ". $addresserModel->id .  " || " . "addressee_id: ". $this->addressee . " || " . $templateModel->body);
        Console::stdout("addresser_id: ". $addresserModel->id .  " || " . "addressee_id: ". $this->addressee . " || " . $templateModel->body);

        // Send Email
        $transport = (new Swift_SmtpTransport('smtp.exmail.qq.com', 25))->setUsername($addresserModel->account)->setPassword(base64_decode($addresserModel->pwd));

        $mailer = new Swift_Mailer($transport);

        $message = (new Swift_Message($templateModel->subject))
            ->setFrom($addresserModel->account)
            ->setTo($this->addressee)
            ->setBody($templateModel->body);
        $result = $mailer->send($message);
        if ($result) {
            $this->info("Success: Addresser: " . $addresserModel->account . " Addressee: " . $this->addressee . PHP_EOL);
        } else {
            $this->error("Failed: Addresser: " . $addresserModel->account . " Addressee: " . $this->addressee . PHP_EOL);
            $this->error(var_export($result) . PHP_EOL);

        }
    }
}