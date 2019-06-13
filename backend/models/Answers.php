<?php
namespace backend\models;
use Yii;
/**
 * This is the model class for table "stream".
 *
 * @property int $id
 * @property int $track
 * @property int $question
 * @property int $user
 * @property int $answer
 * @property string $createdAt
 * @property string $updatedAt
 */
class Answers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'answers';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question', 'answer', 'user', 'track', 'createdAt', 'updatedAt'], 'required'],
            [['question', 'track', 'user'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
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
            'question' => 'Question',
            'answer' => 'Answer',
            'user' => 'User',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }
}
