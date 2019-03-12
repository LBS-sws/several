<?php

/**
 * Created by PhpStorm.
 * User: 沈超
 * Date: 2017/6/7 0007
 * Time: 上午 11:30
 */
class ClientsController extends Controller
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
                'actions'=>array('edit','new','save','delete','ajaxCompanyList'),
                'expression'=>array('ClientsController','allowReadWrite'),
            ),
            array('allow',
                'actions'=>array('index','view'),
                'expression'=>array('ClientsController','allowReadOnly'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }
    public static function allowReadWrite() {
        return Yii::app()->user->validRWFunction('MR02');
    }

    public static function allowReadOnly() {
        return Yii::app()->user->validFunction('MR02');
    }

    public function actionIndex($pageNum=0){
        $model = new ClientsList;
        if (isset($_POST['ClientsList'])) {
            $model->attributes = $_POST['ClientsList'];
        } else {
            $session = Yii::app()->session;
            if (isset($session['clients_01']) && !empty($session['clients_01'])) {
                $criteria = $session['clients_01'];
                $model->setCriteria($criteria);
            }
        }
        $model->determinePageNum($pageNum);
        $model->retrieveDataByPage($model->pageNum);
        $this->render('index',array('model'=>$model));
    }

    public function actionNew()
    {
        $model = new ClientsForm('new');
        $this->render('form',array('model'=>$model,));
    }

    public function actionEdit($index)
    {
        $model = new ClientsForm('edit');
        if (!$model->retrieveData($index)) {
            throw new CHttpException(404,'The requested page does not exist.');
        } else {
            $this->render('form',array('model'=>$model,));
        }
    }

    public function actionView($index)
    {
        $model = new ClientsForm('view');
        if (!$model->retrieveData($index)) {
            throw new CHttpException(404,'The requested page does not exist.');
        } else {
            $this->render('form',array('model'=>$model,));
        }
    }

    public function actionSave()
    {
        if (isset($_POST['ClientsForm'])) {
            $model = new ClientsForm($_POST['ClientsForm']['scenario']);
            $model->attributes = $_POST['ClientsForm'];
            if ($model->validate()) {
                $model->saveData();
                Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Save Done'));
                $this->redirect(Yii::app()->createUrl('clients/edit',array('index'=>$model->id)));
            } else {
                $message = CHtml::errorSummary($model);
                Dialog::message(Yii::t('dialog','Validation Message'), $message);
                $this->render('form',array('model'=>$model,));
            }
        }
    }

    //刪除公司
    public function actionDelete(){
        $model = new ClientsForm('delete');
        if (isset($_POST['ClientsForm'])) {
            $model->attributes = $_POST['ClientsForm'];
            if($model->validateDelete()){
                $model->saveData();
                Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Record Deleted'));
            }else{
                Dialog::message(Yii::t('dialog','Information'), "刪除失败");
                $this->redirect(Yii::app()->createUrl('clients/edit',array('index'=>$model->id)));
            }
        }
        $this->redirect(Yii::app()->createUrl('clients/index'));
    }

    //刪除公司
    public function actionAjaxCompanyList(){
        if(Yii::app()->request->isAjaxRequest) {//是否ajax请求
            $company_id = $_POST["company_id"];
            $model = new ClientsForm();
            $rs = $model->ajaxCompanyList($company_id);
            echo CJSON::encode($rs);//Yii 的方法将数组处理成json数据
        }else{
            $this->redirect(Yii::app()->createUrl('clients/index'));
        }
    }
}