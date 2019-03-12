<?php

/**
 * Created by PhpStorm.
 * User: 沈超
 * Date: 2017/6/7 0007
 * Time: 上午 11:30
 */
class CompanyController extends Controller
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
                'expression'=>array('CompanyController','allowReadWrite'),
            ),
            array('allow',
                'actions'=>array('index','view'),
                'expression'=>array('CompanyController','allowReadOnly'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }
    public static function allowReadWrite() {
        return Yii::app()->user->validRWFunction('XR02');
    }

    public static function allowReadOnly() {
        return Yii::app()->user->validFunction('XR02');
    }

    public function actionIndex($pageNum=0){
        $model = new CompanyList;
        if (isset($_POST['CompanyList'])) {
            $model->attributes = $_POST['CompanyList'];
        } else {
            $session = Yii::app()->session;
            if (isset($session['company_01']) && !empty($session['company_01'])) {
                $criteria = $session['company_01'];
                $model->setCriteria($criteria);
            }
        }
        $model->determinePageNum($pageNum);
        $model->retrieveDataByPage($model->pageNum);
        $this->render('index',array('model'=>$model));
    }

    public function actionNew()
    {
        $model = new CompanyForm('new');
        $this->render('form',array('model'=>$model,));
    }

    public function actionEdit($index)
    {
        $model = new CompanyForm('edit');
        if (!$model->retrieveData($index)) {
            throw new CHttpException(404,'The requested page does not exist.');
        } else {
            $this->render('form',array('model'=>$model,));
        }
    }

    public function actionView($index)
    {
        $model = new CompanyForm('view');
        if (!$model->retrieveData($index)) {
            throw new CHttpException(404,'The requested page does not exist.');
        } else {
            $this->render('form',array('model'=>$model,));
        }
    }

    public function actionSave()
    {
        if (isset($_POST['CompanyForm'])) {
            $model = new CompanyForm($_POST['CompanyForm']['scenario']);
            $model->attributes = $_POST['CompanyForm'];
            if ($model->validate()) {
                $model->saveData();
                Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Save Done'));
                $this->redirect(Yii::app()->createUrl('company/edit',array('index'=>$model->id)));
            } else {
                $message = CHtml::errorSummary($model);
                Dialog::message(Yii::t('dialog','Validation Message'), $message);
                $this->render('form',array('model'=>$model,));
            }
        }
    }

    //刪除公司
    public function actionDelete(){
        $model = new CompanyForm('delete');
        if (isset($_POST['CompanyForm'])) {
            $model->attributes = $_POST['CompanyForm'];
            if($model->validateDelete()){
                $model->saveData();
                Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Record Deleted'));
            }else{
                Dialog::message(Yii::t('dialog','Information'), "刪除失败");
                $this->redirect(Yii::app()->createUrl('company/edit',array('index'=>$model->id)));
            }
        }
        $this->redirect(Yii::app()->createUrl('company/index'));
    }

}