<?php


namespace app\commands;


use app\modules\admin\modules\mail\models\Addressee;
use GuzzleHttp\Client;

class TestController extends Controller
{
    public function actionIndex()
    {
        $model = new Addressee();
        $model->account = 'from@asda.com';
        $model->save(false);
    }
}