<?php

namespace app\controllers;

use Yii;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use app\models\Link;
use app\models\LinkForm;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class SiteController extends Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $model = new LinkForm();

        return $this->render('index', [
            'model' => $model,
        ]);
    }

    public function actionCreate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!Yii::$app->request->isPost) {
            return [
                'success' => false,
                'message' => 'Метод не поддерживается',
            ];
        }

        $form = new LinkForm();
        $form->url = Yii::$app->request->post('url');

        if (!$form->validate()) {
            return [
                'success' => false,
                'message' => $form->getFirstError('url'),
            ];
        }

        if (!$form->checkAvailability()) {
            return [
                'success' => false,
                'message' => 'URL недоступен',
            ];
        }

        $link = Link::find()->where(['original_url' => $form->url])->one();
        if (!$link) {
            $link = new Link();
            $link->original_url = $form->url;
            $link->generateCode();
            $link->clicks = 0;

            if (!$link->save()) {
                return [
                    'success' => false,
                    'message' => 'Ошибка сохранения в базе данных: ' . implode(', ', $link->getFirstErrors()),
                ];
            }
        }

        try {
            $shortUrl = $link->getShortUrl();
            $qrBase64 = $this->generateQrBase64($shortUrl);
        } catch (\Throwable $e) {
            Yii::error($e->getMessage(), __METHOD__);

            return [
                'success' => false,
                'message' => 'Не удалось сгенерировать QR-код',
            ];
        }

        return [
            'success' => true,
            'short_url' => $shortUrl,
            'qr_image' => $qrBase64,
            'code' => $link->code,
            'clicks' => $link->clicks,
            'stats_url' => Url::to(['/stats/' . $link->code], true),
        ];
    }

    public function actionStats($code)
    {
        $link = Link::find()->where(['code' => $code])->one();
        if (!$link) {
            throw new NotFoundHttpException('Ссылка не найдена');
        }

        $logs = $link->getLogs()->orderBy(['visited_at' => SORT_DESC])->all();
        $shortUrl = $link->getShortUrl();
        $qrBase64 = $this->generateQrBase64($shortUrl);

        return $this->render('stats', [
            'link' => $link,
            'logs' => $logs,
            'qrBase64' => $qrBase64,
        ]);
    }

    private function generateQrBase64($url)
    {
        $qrCode = QrCode::create($url)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->setSize(300)
            ->setMargin(10);

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        return 'data:image/png;base64,' . base64_encode($result->getString());
    }
}
