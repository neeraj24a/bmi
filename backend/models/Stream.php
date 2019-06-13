<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "stream".
 *
 * @property int $id
 * @property int $track
 * @property int $type
 * @property int $user
 * @property string $createdAt
 * @property string $updatedAt
 */
class Stream extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stream';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['track', 'type', 'createdAt', 'updatedAt'], 'required'],
            [['track', 'user'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['type'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'track' => 'Track',
            'type' => 'Type',
            'user' => 'User',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }
}
