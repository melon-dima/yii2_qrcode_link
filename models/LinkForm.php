<?php

namespace app\models;

use yii\base\Model;

class LinkForm extends Model
{
    public $url;

    public function rules()
    {
        return [
            [['url'], 'required', 'message' => 'Введите URL'],
            [['url'], 'url', 'defaultScheme' => null, 'message' => 'Укажите полный URL с http:// или https://'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'url' => 'Ссылка',
        ];
    }

    public function checkAvailability()
    {
        if (!function_exists('curl_init')) {
            return false;
        }

        $ch = curl_init($this->url);
        curl_setopt_array($ch, [
            CURLOPT_NOBODY => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_CONNECTTIMEOUT => 7,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 5,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; ShortLinkBot/1.0)',
        ]);
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error || $httpCode === 0 || $httpCode >= 400) {
            return false;
        }

        return true;
    }
}
