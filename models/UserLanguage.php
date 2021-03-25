<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_language".
 *
 * @property int $id
 * @property int $user_id
 * @property int $language_id
 *
 * @property User $user
 * @property ProgrammingLanguage $language
 */
class UserLanguage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_language';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'language_id'], 'required'],
            [['user_id', 'language_id'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['language_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProgrammingLanguage::className(), 'targetAttribute' => ['language_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'language_id' => 'Language ID',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Gets query for [[Language]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(ProgrammingLanguage::className(), ['id' => 'language_id']);
    }
}
