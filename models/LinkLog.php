<?php

namespace app\models;

use yii\db\ActiveRecord;

class LinkLog extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%link_log}}';
    }

    public function rules()
    {
        return [
            [['link_id', 'ip'], 'required'],
            [['link_id'], 'integer'],
            [['ip'], 'string', 'max' => 45],
            [['user_agent'], 'string'],
        ];
    }

    public function getLink()
    {
        return $this->hasOne(Link::class, ['id' => 'link_id']);
    }
}
