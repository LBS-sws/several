<?php

/**
 * Created by PhpStorm.
 * User: 沈超
 * Date: 2017/6/7 0007
 * Time: 上午 11:30
 */
class SearchFirmController extends Controller
{

    public function filters()
    {
        return array(
            'enforceSessionExpiration',
            'enforceNoConcurrentLogin',
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',
                'actions'=>array('index','view','export'),
                'expression'=>array('SearchFirmController','allowReadOnly'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }
    public static function allowReadWrite() {
        return Yii::app()->user->validRWFunction('BC03');
    }

    public static function allowReadOnly() {
        return Yii::app()->user->validFunction('BC03');
    }

    public function actionIndex($pageNum=0){
        $model = new SearchFirmList;
        if (isset($_POST['SearchFirmList'])) {
            $model->attributes = $_POST['SearchFirmList'];
        } else {
            $session = Yii::app()->session;
            if (isset($session['searchFirm_01']) && !empty($session['searchFirm_01'])) {
                $criteria = $session['searchFirm_01'];
                $model->setCriteria($criteria);
            }
        }
        $model->determinePageNum($pageNum);
        $model->retrieveDataByPage($model->pageNum);
        $this->render('index',array('model'=>$model));
    }

    public function actionView($index,$year='year')
    {
        $model = new SearchFirmForm('view');
        $model->customer_year = $year;
        if (!$model->retrieveData($index)) {
            throw new CHttpException(404,'The requested page does not exist.');
        } else {
            $this->render('form',array('model'=>$model,));
        }
    }
}