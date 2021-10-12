<?php

namespace app\commands;

use app\modules\admin\modules\mail\models\Addressee;
use app\modules\admin\modules\mail\models\Email;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use yii\helpers\Console;

class MailController extends Controller
{
    private $host = '';
    private $port = 25;
    private $username = '';
    private $password = '';
    private $subject = '';  // title
    private $addressFrom = '';
    private $addressTo = '';
    private $body = '';  // message body
    public $marubox = '';

    public function actionIndex()
    {
        $transport = (new Swift_SmtpTransport('smtp.exmail.qq.com', 25))->setUsername('service@ahotfashion.com')->setPassword('YIjia102102@');

        $mailer = new Swift_Mailer($transport);

        $imap_stream = imap_open('{imap.exmail.qq.com:143}INBOX', 'service@ahotfashion.com', 'YIjia102102@');
        $msg_num = imap_num_msg($imap_stream);
        var_dump($msg_num);
        $t = imap_fetch_overview($imap_stream, 32);
        var_dump($t);
        var_dump($t[0]->answered);
        die;
        $mailBody = imap_fetchbody($imap_stream, 1, 0);
        var_dump($mailBody);

        $mailBody = imap_fetchbody($imap_stream, 1, 0);
        var_dump(utf8_encode($mailBody));

        $mailBody = imap_fetchbody($imap_stream, 1, 2);
        var_dump($mailBody);

        $mailBody = imap_fetchbody($imap_stream, 1, 3);
        var_dump($mailBody);

        $mailBody = imap_fetchbody($imap_stream, 1, 4);
        var_dump("是");


//        $message = (new Swift_Message('Test Subject'))
//            ->setFrom('service@ahotfashion.com')
//            ->setReplyTo('13122995191@163.com')
//            ->setTo('13122995191@163.com')
//            ->setBody('Here is the message itself');
//        $result = $mailer->send($message);
//        var_dump($result);
    }

    public function actionCheck()
    {

        /* 账户密码：
         * service@engravingift.uk   Pww8866    旺旺店铺邮箱
         *
         * service@ahotfashion.com - YIjia102102@   董鹏测试用邮箱
         *
         * service@mynamejewelry.shop   YIjia102102   邮箱
         *
         * service@jearlover.com - YIjia102102@   邮箱
         *
         * service@fridayjewelry.com   YIjia102102  佳欣店铺邮箱
         *
         * service@melodynecklace.com   Tx123456  唐雄店铺邮箱
         *
         * service@myshinyjewelry.com   YIjia102102.
        */
        $db = \Yii::$app->db;

        $account = 'service@myshinyjewelry.com';
        $pwd = 'YIjia102102.';

        $imap_stream = imap_open('{imap.exmail.qq.com:143}INBOX', $account, $pwd);
        $this->marubox = $imap_stream;
        $array = []; // 记录

        $msg_num = imap_search($imap_stream, 'UNSEEN');
        Console::stdout(">>>邮箱内共有【" . count($msg_num) . "】封未读邮件." . PHP_EOL);
        $countNotSeen = 0;
        $countTemplate1 = 0;
        $countTemplate2 = 0;
        $countNone = 0;

        foreach ($msg_num as $i) {

            $data = imap_fetch_overview($imap_stream, $i)[0];
//            $mailBody = imap_fetchbody($imap_stream, 1, 1);
            //gift.uk> wrote:


            $seen = $data->seen;
            if ($seen) {
                Console::stdout('Seen' . PHP_EOL);
                continue;
            }


            $countNotSeen++;
//            var_dump($i);
            $answered = $data->answered;
            $from = $this->get_header_info(imap_headerinfo($imap_stream, $i));
            $from = isset($from['from']) ? $from['from'] : '';
            $subject = base64_decode($data->subject);
            if (stripos($from, '@shopify.com') !== false) {
                continue;
            }
            if (stripos($from, '@exmail.weixin.qq.com') !== false) {
                continue;
            }
            Console::stdout(">>>客户邮箱:【" . $from . "】" . PHP_EOL);
            Console::stdout(">>>主题:【" . $subject . "】" . PHP_EOL);
            Console::stdout(">>>邮件id: $i" . PHP_EOL);
            if ($answered) {
                Console::stdout(">>>" . $from . " already reply, pass!" . PHP_EOL);
                continue;
            }

            // @todo save the keywords and template to database
            $flag = true;
//            $mailBody = imap_fetchbody($imap_stream, $i, 1);
            $mailBody = $this->get_body($i);

            $filterWord = 'wrote:';

            $flag2 = stripos($mailBody, $filterWord);
            if (!$flag2) {
                Console::stdout(">>> 没有找到$filterWord" . PHP_EOL);
            }
            $mailBody = explode($filterWord, $mailBody)[0];

            // @todo need to be refactored with table keyword
            if (stripos($mailBody, 'when') || stripos($mailBody, 'where is my parcel') || stripos($mailBody, 'where is my order') || stripos($mailBody, 'when will you ship') || stripos($mailBody, 'has been sent')) {
                $countTemplate1++;
                Console::stdout(">>>使用模板一" . PHP_EOL);
                $template = 'Dear Customer,

Thanks for your order. 

Your parcel was picked it up by the express already, but the tracking number only can be updated to you more later because it is high period for the delivery due to the festival, we will update you very soon once we have the information. We are sorry if the gift is late to you, but acutually this gift is suitable for any kind of Festival for your lover, so we hope you can still keep this meaningful gift and we will appreciate for your kind support with 30% discount code for your next order. Thanks again. 

Best regards
Customer Service Team';
            } else {
                if (stripos($mailBody, 'tracking number') || stripos($mailBody, 'how to track') || stripos($mailBody, 'when it will be arrived') || stripos($mailBody, 'arriving time') || stripos($mailBody, 'ETA') || stripos($mailBody, 'delivery time') || stripos($mailBody, 'update') || stripos($mailBody, 'shipped') || stripos($mailBody, 'status') || stripos($mailBody, 'been sent') || stripos($mailBody, 'arrive') || stripos($mailBody, 'delivery') || stripos($mailBody, 'how long')) {
                    //  update，shipped，status，been sent，
                    //arrive，delivery，how long
                    $countTemplate2++;
                    Console::stdout(">>>使用模板二" . PHP_EOL);

                    $template = 'Dear Customer, 
Thanks for your order. 
If you have received the tracking number sent by our shop, you can follow up the movements through https://t.17track.net/en. The shipping time is around 7-12 days if everything is smooth but depends on your location also. 
Thanks for your patience. 

Best regards
Customer Service Team';
                } else {
                    Console::stdout(">>>未检测到关键词" . PHP_EOL);
                    $countNone++;
                    $template = '';
                    $flag = false;
                }
            }

            // 发送邮件
            if ($flag) {

                $model = Addressee::findOne(['account' => $from]);
                if ($model) {
                    if (time() - $model->created_at < 864000) {
                        // 如果是同名用户，并且距离上次发送邮件时间不足十天，不发送邮件
                        // @todo 并且模板相同
                        continue;
                    } else {
                        // 同名用户 并且 距离上次发送邮件时间超过十天，更新该用户创建时间，并发送邮件
                        $model->created_at = time();
                        $model->save(false);
                    }

                }
                Console::stdout(">>>发送邮件" . PHP_EOL);
                $array[] = $i;

                $transport = (new Swift_SmtpTransport('smtp.exmail.qq.com', 25))->setUsername($account)->setPassword($pwd);

                $mailer = new Swift_Mailer($transport);
                $message = (new Swift_Message('Re:' . $subject))
                    ->setFrom($account)
//                    ->setReplyTo('13122995191@163.com')
                    ->setTo($from)
                    ->setBody($template);
                $result = $mailer->send($message);
                if ($result) {
                    Console::stdout(">>>发送成功!" . PHP_EOL);

                    imap_setflag_full($imap_stream, $i, '\\Seen');
                    imap_setflag_full($imap_stream, $i, '\\Answered');

                    // 存储已自动回复邮件的客户信息
                    $model = new Addressee();
                    $model->account = $from;
                    $model->save(false);
                } else {
                    Console::stdout("Failed" . PHP_EOL);
                }
            }

        }
        Console::stdout(">>>邮箱【" . $account . "】未读邮件共【" . $countNotSeen . "】封" . ", 使用模板一的邮件共【" . $countTemplate1 . "】封" . ", 使用模板二的邮件共【" . $countTemplate2 . "】封" . PHP_EOL);
        imap_close($imap_stream);
    }

    /**
     * @param $mail_header
     * @return array|string
     */
    public function get_header_info($mail_header)
    {
        $sender = $mail_header->from[0];
        $sender_replyto = isset($mail_header->to[0]) ? $mail_header->to[0] : false;
        if ($sender_replyto == false) {
            return '';
        }
        if (strtolower($sender->mailbox) != 'mailer-daemon' && strtolower($sender->mailbox) != 'postmaster') {
            $mail_details = array(
                'from' => strtolower($sender->mailbox) . '@' . $sender->host,
                'fromName' => $this->_decode_mime_str($sender->personal),
                'toOth' => strtolower($sender_replyto->mailbox) . '@' . $sender_replyto->host,
                'toNameOth' => $this->_decode_mime_str($sender_replyto->personal),
                'subject' => $this->_decode_mime_str($mail_header->subject),
                'to' => strtolower($this->_decode_mime_str($mail_header->toaddress))
            );
        }
        return $mail_details;
    }

    /**
     * @param $string
     * @param string $charset
     * @return string
     */
    private function _decode_mime_str($string, $charset = "UTF-8")
    {
        $newString = '';
        $elements = imap_mime_header_decode($string);
        for ($i = 0; $i < count($elements); $i++) {
            if ($elements[$i]->charset == 'default') $elements[$i]->charset = 'iso-8859-1';
            $newString .= iconv($elements[$i]->charset, $charset, $elements[$i]->text);
        }
        return $newString;
    }

    /**
     * 读取邮件主体
     */
    public function get_body($mid)
    {
        if (!$this->marubox) return false;
        $body = $this->_get_part($this->marubox, $mid, "TEXT/HTML");
        if ($body == "") $body = $this->_get_part($this->marubox, $mid, "TEXT/PLAIN");
        if ($body == "") return "";
        $encode = mb_detect_encoding($body, array("ASCII", 'UTF-8', "GB2312", "GBK", 'BIG5'));
        return iconv($encode, "UTF-8", $body);
//        return mb_convert_encoding($body, $encode,"UTF-8");
    }

    private function _get_part($stream, $msg_number, $mime_type, $structure = false, $part_number = false)
    {
        if (!$structure) $structure = imap_fetchstructure($stream, $msg_number);
        if ($structure) {
            if ($mime_type == $this->_get_mime_type($structure)) {
                if (!$part_number) {
                    $part_number = "1";
                }
                $text = imap_fetchbody($stream, $msg_number, $part_number);
                //file_put_contents('D:/project/www/b/'.$msg_number.'.txt', $text);
                if ($structure->encoding == 3) {
                    return imap_base64($text);
                } else if ($structure->encoding == 4) {
                    return imap_qprint($text);
                } else {
                    return $text;
                }
            }
            if ($structure->type == 1) /* multipart */ {
                while (list($index, $sub_structure) = $this->func_new_each($structure->parts)) {
                    $prefix = false;
                    if ($part_number) {
                        $prefix = $part_number . '.';
                    }
                    $data = $this->_get_part($stream, $msg_number, $mime_type, $sub_structure, $prefix . ($index + 1));
                    if ($data) {
                        return $data;
                    }
                }
            }
        }
        return false;
    }

    private function _get_mime_type(&$structure)
    {
        $primary_mime_type = array("TEXT", "MULTIPART", "MESSAGE", "APPLICATION", "AUDIO", "IMAGE", "VIDEO", "OTHER");

        if ($structure->subtype) {
            return $primary_mime_type[(int)$structure->type] . '/' . $structure->subtype;
        }
        return "TEXT/PLAIN";
    }

    public function func_new_each(&$array)
    {
        $res = array();
        $key = key($array);
        if ($key !== null) {
            next($array);
            $res[1] = $res['value'] = $array[$key];
            $res[0] = $res['key'] = $key;
        } else {
            $res = false;
        }
        return $res;
    }
}