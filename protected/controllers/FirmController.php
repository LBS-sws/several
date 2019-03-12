<?php

/**
 * Created by PhpStorm.
 * User: 沈超
 * Date: 2017/6/7 0007
 * Time: 上午 11:30
 */
class FirmController extends Controller
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
                'actions'=>array('edit','new','save','delete'),
                'expression'=>array('FirmController','allowReadWrite'),
            ),
            array('allow',
                'actions'=>array('index','view'),
                'expression'=>array('FirmController','allowReadOnly'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }
    public static function allowReadWrite() {
        return Yii::app()->user->validRWFunction('XR04');
    }

    public static function allowReadOnly() {
        return Yii::app()->user->validFunction('XR04');
    }

    public function actionIndex($pageNum=0){
        $model = new FirmList;
        if (isset($_POST['FirmList'])) {
            $model->attributes = $_POST['FirmList'];
        } else {
            $session = Yii::app()->session;
            if (isset($session['firm_01']) && !empty($session['firm_01'])) {
                $criteria = $session['firm_01'];
                $model->setCriteria($criteria);
            }
        }
        $model->determinePageNum($pageNum);
        $model->retrieveDataByPage($model->pageNum);
        $this->render('index',array('model'=>$model));
    }

    public function actionNew()
    {
        $model = new FirmForm('new');
        $this->render('form',array('model'=>$model,));
    }

    public function actionEdit($index)
    {
        $model = new FirmForm('edit');
        if (!$model->retrieveData($index)) {
            throw new CHttpException(404,'The requested page does not exist.');
        } else {
            $this->render('form',array('model'=>$model,));
        }
    }

    public function actionView($index)
    {
        $model = new FirmForm('view');
        if (!$model->retrieveData($index)) {
            throw new CHttpException(404,'The requested page does not exist.');
        } else {
            $this->render('form',array('model'=>$model,));
        }
    }

    public function actionSave()
    {
        if (isset($_POST['FirmForm'])) {
            $model = new FirmForm($_POST['FirmForm']['scenario']);
            $model->attributes = $_POST['FirmForm'];
            if ($model->validate()) {
                $model->saveData();
                Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Save Done'));
                $this->redirect(Yii::app()->createUrl('firm/edit',array('index'=>$model->id)));
            } else {
                $message = CHtml::errorSummary($model);
                Dialog::message(Yii::t('dialog','Validation Message'), $message);
                $this->render('form',array('model'=>$model,));
            }
        }
    }

    //刪除公司
    public function actionDelete(){
        $model = new FirmForm('delete');
        if (isset($_POST['FirmForm'])) {
            $model->attributes = $_POST['FirmForm'];
            if($model->validateDelete()){
                $model->saveData();
                Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Record Deleted'));
            }else{
                Dialog::message(Yii::t('dialog','Information'), "刪除失败");
                $this->redirect(Yii::app()->createUrl('firm/edit',array('index'=>$model->id)));
            }
        }
        $this->redirect(Yii::app()->createUrl('firm/index'));
    }

}