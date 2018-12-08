<?php

/**
 * Created by PhpStorm.
 * User: 沈超
 * Date: 2017/6/7 0007
 * Time: 上午 11:30
 */
class CustomerController extends Controller
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
                'actions'=>array('import','importSave'),
                'expression'=>array('CustomerController','allowImport'),
            ),
            array('allow',
                'actions'=>array('edit','new','save','delete'),
                'expression'=>array('CustomerController','allowReadWrite'),
            ),
            array('allow',
                'actions'=>array('index','view'),
                'expression'=>array('CustomerController','allowReadOnly'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    public static function allowImport() {
        return Yii::app()->user->validRWFunction('CU01');
    }

    public static function allowReadWrite() {
        return Yii::app()->user->validRWFunction('CU02');
    }

    public static function allowReadOnly() {
        return Yii::app()->user->validFunction('CU02')||Yii::app()->user->validFunction('CU01');
    }

    public function actionImport()
    {
        $model = new UploadExcelForm('new');
        $this->render('import',array('model'=>$model,));
    }
    public function actionImportSave(){
        $model = new UploadExcelForm();
        $img = CUploadedFile::getInstance($model,'file');
        $city = Yii::app()->user->city();
        $path =Yii::app()->basePath."/../upload/";
        if (!file_exists($path)){
            mkdir($path);
        }
        $path =Yii::app()->basePath."/../upload/excel/";
        if (!file_exists($path)){
            mkdir($path);
        }
        $path.=$city."/";
        if (!file_exists($path)){
            mkdir($path);
        }
        if(empty($img)){
            Dialog::message(Yii::t('dialog','Validation Message'), "文件不能为空");
            $this->redirect(Yii::app()->createUrl('customer/import'));
        }
        $url = "upload/excel/".$city."/".date("YmdHis").".".$img->getExtensionName();
        $model->file = $img->getName();
        if ($model->file) {
            $img->saveAs($url);
            $loadExcel = new LoadExcel($url);
            $list = $loadExcel->getExcelList();
            $model->loadSeveral($list);
            $this->redirect(Yii::app()->createUrl('customer/import'));
        }else{
            $message = CHtml::errorSummary($model);
            Dialog::message(Yii::t('dialog','Validation Message'), $message);
            $this->redirect(Yii::app()->createUrl('customer/import'));
        }
    }

    public function actionIndex($pageNum=0){
        $model = new CustomerList;
        if (isset($_POST['CustomerList'])) {
            $model->attributes = $_POST['CustomerList'];
        } else {
            $session = Yii::app()->session;
            if (isset($session['customer_01']) && !empty($session['customer_01'])) {
                $criteria = $session['customer_01'];
                $model->setCriteria($criteria);
            }
        }
        $model->determinePageNum($pageNum);
        $model->retrieveDataByPage($model->pageNum);
        $this->render('index',array('model'=>$model));
    }

    public function actionNew()
    {
        $model = new CustomerForm('new');
        $this->render('form',array('model'=>$model,));
    }

    public function actionEdit($index)
    {
        $model = new CustomerForm('edit');
        if (!$model->retrieveData($index)) {
            throw new CHttpException(404,'The requested page does not exist.');
        } else {
            $this->render('form',array('model'=>$model,));
        }
    }

    public function actionView($index)
    {
        $model = new CustomerForm('view');
        if (!$model->retrieveData($index)) {
            throw new CHttpException(404,'The requested page does not exist.');
        } else {
            $this->render('form',array('model'=>$model,));
        }
    }

    public function actionSave()
    {
        if (isset($_POST['CustomerForm'])) {
            $model = new CustomerForm($_POST['CustomerForm']['scenario']);
            $model->attributes = $_POST['CustomerForm'];
            if ($model->validate()) {
                $model->saveData();
                Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Save Done'));
                $this->redirect(Yii::app()->createUrl('customer/edit',array('index'=>$model->id)));
            } else {
                $message = CHtml::errorSummary($model);
                Dialog::message(Yii::t('dialog','Validation Message'), $message);
                $this->render('form',array('model'=>$model,));
            }
        }
    }

    //刪除公司
    public function actionDelete(){
        $model = new CustomerForm('delete');
        if (isset($_POST['CustomerForm'])) {
            $model->attributes = $_POST['CustomerForm'];
            if($model->validateDelete()){
                $model->saveData();
                Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Record Deleted'));
            }else{
                Dialog::message(Yii::t('dialog','Information'), "刪除失败");
                $this->redirect(Yii::app()->createUrl('customer/edit',array('index'=>$model->id)));
            }
        }
        $this->redirect(Yii::app()->createUrl('customer/index'));
    }

}