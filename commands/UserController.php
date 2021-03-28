<?php


namespace app\commands;

use app\models\ProgrammingLanguage;
use app\models\User;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\BaseConsole;

class UserController extends Controller {

    public function actionCreate() {
        $model = new User();
        $model->registration_method = User::REGISTRATION_BY_CLI;

        $this->getAttribute($model, 'first_name');
        $this->getAttribute($model, 'last_name');
        $this->getAttribute($model, 'pesel');
        $this->getAttribute($model, 'email');

        foreach (ProgrammingLanguage::find()->all() as $language) {
            echo 'Select ' . $language->id . ' for ' . $language->name . "\n";
        }
        $selectedLang = BaseConsole::input('Select existing language, enter name of new one or just press enter for next step: ');
        while ($selectedLang !== "") {
            $model->programmingLanguageList[] = $selectedLang;
            $selectedLang = BaseConsole::input('Select existing language, enter name of new one or just press enter for next step: ');
        }

        if ($model->validate() && $model->save()) {
            return ExitCode::OK;
        }
        return ExitCode::UNSPECIFIED_ERROR;
    }

    private function getAttribute(User $model, $attribute) {
        $model->$attribute = BaseConsole::input('Enter ' . $model->getAttributeLabel($attribute) .': ');
        while (!$model->validate($attribute)) {
            if (isset($model->errors[$attribute][0])) {
                echo $model->errors[$attribute][0] . "\n";
            }
            $model->$attribute = BaseConsole::input('Enter ' . $model->getAttributeLabel($attribute) .': ');
        }
    }

}