<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "track".
 *
 * @property int $id
 * @property string $uploadedBy
 * @property string $type
 * @property int $popularity
 * @property int $day
 * @property int $week
 * @property int $month
 * @property string $comment
 * @property string $name
 * @property string $artist
 * @property string $title
 * @property string $remixer
 * @property string $genre
 * @property string $subgenre
 * @property int $bpm
 * @property string $key
 * @property int $year
 * @property int $action
 * @property string $url
 * @property string $slug
 * @property string $search
 * @property string $rsearch
 * @property string $createdAt
 * @property int $isRadio
 * @property string $uploads
 * @property int $size
 * @property string $updatedAt
 */
class Track extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'track';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['popularity', 'day', 'week', 'month', 'bpm', 'year', 'action', 'isRadio', 'size'], 'integer'],
            [['slug'], 'required'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['uploads'], 'string'],
            [['uploadedBy', 'type', 'comment', 'name', 'artist', 'title', 'remixer', 'genre', 'subgenre', 'key', 'url'], 'string', 'max' => 255],
            [['slug', 'search', 'rsearch'], 'string', 'max' => 512],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uploadedBy' => 'Uploaded By',
            'type' => 'Type',
            'popularity' => 'Popularity',
            'day' => 'Day',
            'week' => 'Week',
            'month' => 'Month',
            'comment' => 'Comment',
            'name' => 'Name',
            'artist' => 'Artist',
            'title' => 'Title',
            'remixer' => 'Remixer',
            'genre' => 'Genre',
            'subgenre' => 'Subgenre',
            'bpm' => 'Bpm',
            'key' => 'Key',
            'year' => 'Year',
            'action' => 'Action',
            'url' => 'Url',
            'slug' => 'Slug',
            'search' => 'Search',
            'rsearch' => 'Rsearch',
            'createdAt' => 'Created At',
            'isRadio' => 'Is Radio',
            'uploads' => 'Uploads',
            'size' => 'Size',
            'updatedAt' => 'Updated At',
        ];
    }
}
