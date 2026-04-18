<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\helpers\Url;

class Link extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%link}}';
    }

    public function rules()
    {
        return [
            [['original_url'], 'required', 'message' => 'Введите URL'],
            [['original_url'], 'url', 'defaultScheme' => null, 'message' => 'Укажите полный URL с http:// или https://'],
            [['code'], 'string', 'max' => 10],
            [['code'], 'unique'],
            [['clicks'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'original_url' => 'Ссылка',
        ];
    }

    public function generateCode()
    {
        do {
            $code = $this->randomCode(6);
        } while (self::find()->where(['code' => $code])->exists());

        $this->code = $code;
    }

    private function randomCode($length = 6)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $chars[random_int(0, strlen($chars) - 1)];
        }

        return $code;
    }

    public function getShortUrl()
    {
        return Url::to(['/go/' . $this->code], true);
    }

    public function getLogs()
    {
        return $this->hasMany(LinkLog::class, ['link_id' => 'id']);
    }

    public function incrementClicks()
    {
        $this->updateCounters(['clicks' => 1]);
    }
}
