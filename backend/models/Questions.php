<?php
namespace backend\models;
use Yii;
/**
 * This is the model class for table "stream".
 *
 * @property int $id
 * @property int $qtype
 * @property int $question
 * @property string $createdAt
 * @property string $updatedAt
 */
class Questions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'questions';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question', 'qtype', 'createdAt', 'updatedAt'], 'required'],
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
            'qtype' => 'Question Type',
            'question' => 'Question',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }
}
