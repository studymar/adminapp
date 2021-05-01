<?php

 namespace app\controllers;

use Yii;
use yii\web\Controller;

class ContentController extends Controller
{

    public function actionImpressum()
    {
        return $this->render('impressum', [
        ]);
    }

    public function actionDatenschutz()
    {
        return $this->render('datenschutz', [
        ]);
    }
    
}



