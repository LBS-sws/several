<?php

/**
 * Created by PhpStorm.
 * User: 沈超
 * Date: 2017/6/7 0007
 * Time: 上午 11:30
 */
class SearchCustomerController extends Controller
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
                'expression'=>array('SearchCustomerController','allowReadOnly'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }
    public static function allowReadWrite() {
        return Yii::app()->user->validRWFunction('BC05');
    }

    public static function allowReadOnly() {
        return Yii::app()->user->validFunction('BC05');
    }

    public function actionIndex($pageNum=0){
        $model = new SearchCustomerList;
        if (isset($_POST['SearchCustomerList'])) {
            $model->attributes = $_POST['SearchCustomerList'];
        } else {
            $session = Yii::app()->session;
            if (isset($session['searchCustomer_01']) && !empty($session['searchCustomer_01'])) {
                $criteria = $session['searchCustomer_01'];
                $model->setCriteria($criteria);
            }
        }
        $model->determinePageNum($pageNum);
        $model->retrieveDataByPage($model->pageNum);
        $this->render('index',array('model'=>$model));
    }

    public function actionView($index)
    {
        $model = new SearchCustomerForm('view');
        if (!$model->retrieveData($index)) {
            throw new CHttpException(404,'The requested page does not exist.');
        } else {
            $this->render('form',array('model'=>$model,));
        }
    }
}