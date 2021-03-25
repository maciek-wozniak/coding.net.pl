<?php

namespace app\models;

use DateTime;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $pesel
 * @property string $email
 * @property string $birthday
 * @property int $status
 * @property int $registration_method
 * @property string $created_at
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

    public $programmingLanguageList;

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
            [['first_name', 'last_name', 'email', 'birthday', 'pesel'], 'required'],
            [['first_name', 'last_name', 'email'], 'string', 'max' => 255],
            [['pesel'], 'string', 'min' => 11, 'max' => 11],
            [['birthday'], 'date'],
            [['programmingLanguageList'], 'safe'],
            [['email'], 'email'],
            [['email'], 'unique'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],
            ['registration_method', 'in', 'range' => [self::REGISTRATION_BY_CLI, self::REGISTRATION_BY_API, self::REGISTRATION_BY_UI]],
            ['pesel', 'match', 'pattern' => '/[0-9]{11}/'],
            ['pesel', 'validatePesel']
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
            'pesel' => 'Pesel',
            'email' => 'Email',
            'birthday' => 'Date of birth',
            'status' => 'Status',
            'registration_method' => 'Registration method',
            'programmingLanguageList' => 'Programming languages',
            'created_at' => 'Created At',
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

    public function validatePesel($attribute) {
        $sum = 0;
        $multiplier = [1, 3, 7, 9, 1, 3, 7, 9, 1, 3, 0];
        for ($i = 0; $i < strlen($this->pesel) - 1; $i++) {
            $sum += $multiplier[$i] * $this->pesel[$i];
        }

        if (+$this->pesel[strlen($this->pesel) - 1] !== (10 - ($sum % 10)) % 10) {
            $this->addError($attribute, 'Pesel is invalid.');
        }
    }

    public function beforeSave($insert) {
        if ($insert && $this->isUnder18()) {
            $this->status = self::STATUS_INACTIVE;
        }

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes) {
        if ($insert && !$this->isUnder18()) {
            $this->sendWelcomeMail();
        }
        $this->saveUserLanguages();

        parent::afterSave($insert, $changedAttributes);
    }

    private function saveUserLanguages() {
        $oldIds = ArrayHelper::map($this->programmingLanguages, 'id', 'name');
        if ($this->programmingLanguageList) {
            foreach ($this->programmingLanguageList as $id) {
                if (is_numeric($id) && ($lang = ProgrammingLanguage::findOne($id))) {
                    if (key_exists($id, $oldIds)) {
                        unset($oldIds[$id]);
                    }
                    else {
                        $this->link('programmingLanguages', $lang);
                    }
                }
                else {
                    if ($lang = ProgrammingLanguage::findOne(['name' => $id])) {
                        $this->link('programmingLanguages', $lang);
                    }
                    else {
                        $newLang = new ProgrammingLanguage();
                        $newLang->name = $id;
                        $newLang->save();
                        $this->link('programmingLanguages', $newLang);
                    }
                }
            }
        }
        foreach ($oldIds as $id => $key) {
            UserLanguage::deleteAll(['user_id' => $this->id, 'language_id' => $id]);
        }
    }

    public function afterFind() {
        $this->programmingLanguageList = ArrayHelper::map($this->programmingLanguages, 'id', 'id');
        parent::afterFind();
    }

    private function sendWelcomeMail() {
        Yii::$app
            ->mailer
            ->compose(
                ['html' => 'hello'],
                ['user' => $this]
            )
            ->setFrom([Yii::$app->params['senderEmail'] => 'Codingtoday.idhosting.pl mailer'])
            ->setTo($this->email)
            ->setSubject('Hello mail!')
            ->send();
    }

    public function isUnder18(): bool {
        return $this->getUserAge() < 18;
    }

    public function getUserAge(): int {
        return (new DateTime())->diff(new DateTime($this->birthday))->y;
    }

    public function getFullName(): string {
        return $this->first_name . ' ' . $this->last_name;
    }
}
