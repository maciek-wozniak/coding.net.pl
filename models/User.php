<?php

namespace app\models;

use DateTime;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $birthday
 * @property int $status
 * @property int $registration_method
 * @property string $createad_at
 *
 * @property UserLanguage[] $userLanguages
 * @property ProgrammingLanguage[] $programmingLanguages
 */
class User extends \yii\db\ActiveRecord
{

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    const REGISTRATION_BY_CLI = 1;
    const REGISTRATION_BY_API = 2;
    const REGISTRATION_BY_UI = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'email', 'birthday'], 'required'],
            [['first_name', 'last_name', 'email'], 'string', 'max' => 255],
            [['birthday'], 'date'],
            [['email'], 'email'],
            [['email'], 'unique'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],
            ['registration_method', 'in', 'range' => [self::REGISTRATION_BY_CLI, self::REGISTRATION_BY_API, self::REGISTRATION_BY_UI]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => new Expression('NOW()'),
                'updatedAtAttribute' => false,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'birthday' => 'Date of birth',
            'status' => 'Status',
            'registration_method' => 'Registration method',
            'createad_at' => 'Createad At',
        ];
    }

    /**
     * Gets query for [[UserLanguages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserLanguages()
    {
        return $this->hasMany(UserLanguage::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[ProgrammingLanguages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProgrammingLanguages()
    {
        return $this->hasMany(ProgrammingLanguage::className(), ['id' => 'language_id'])->viaTable('user_language', ['user_id' => 'id']);
    }

    public function isUnder18() {
        return (new DateTime())->diff(new DateTime($this->birthday))->y < 18;
    }
}
