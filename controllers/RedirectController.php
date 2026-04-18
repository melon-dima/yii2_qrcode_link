<?php

namespace app\controllers;

use Yii;
use app\models\Link;
use app\models\LinkLog;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class RedirectController extends Controller
{
    public function actionIndex($code)
    {
        $link = Link::find()->where(['code' => $code])->one();
        if (!$link) {
            throw new NotFoundHttpException('Короткая ссылка не найдена.');
        }

        $link->incrementClicks();

        $log = new LinkLog();
        $log->link_id = $link->id;
        $log->ip = Yii::$app->request->getUserIP() ?: '0.0.0.0';
        $log->user_agent = Yii::$app->request->getUserAgent();
        $log->save();

        // Используем временный редирект, чтобы браузер не кэшировал короткую ссылку навсегда.
        // Так каждый переход проходит через бэкенд и попадает в статистику.
        $response = Yii::$app->response;
        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');

        return $this->redirect($link->original_url, 302);
    }
}
