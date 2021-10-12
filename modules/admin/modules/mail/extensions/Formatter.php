<?php

namespace app\modules\admin\modules\mail\extensions;

use app\modules\admin\modules\mail\models\Email;

/**
 * Mail formatter
 *
 * @package app\modules\admin\modules\product\extensions
 * @author hiscaler <hiscaler@gmail.com>
 */
class Formatter extends \app\modules\admin\extensions\Formatter
{
    /**
     * 邮件类型
     *
     * @param $value
     * @return mixed|string|null
     */
    public function asEmailType($value)
    {
        $options = Email::typeOptions();
        return isset($options[$value]) ? $options[$value] : null;
    }

    /**
     * 邮件状态
     *
     * @param $value
     * @return mixed|string|null
     */
    public function asEmailStatus($value)
    {
        $options = Email::statusOptions();
        return isset($options[$value]) ? $options[$value] : null;
    }
}

