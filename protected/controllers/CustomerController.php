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
                'actions'=>array('import','importSave','test'),
                'expression'=>array('CustomerController','allowImport'),
            ),
            array('allow',
                'actions'=>array('edit','save','delete','fileupload','fileRemove'),
                'expression'=>array('CustomerController','allowReadWrite'),
            ),
            array('allow',
                'actions'=>array('index','view','fileDownload'),
                'expression'=>array('CustomerController','allowReadOnly'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    public static function allowImport() {
        return Yii::app()->user->validRWFunction('MR01');
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
        $model->attributes = $_POST['UploadExcelForm'];
        if (!$model->validate()) {
            $message = CHtml::errorSummary($model);
            Dialog::message(Yii::t('dialog','Validation Message'), $message);
            $this->render('import',array('model'=>$model,));
            return false;
        }
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
            //$loadExcel->clear();
            $model->loadSeveral($list);
            if(empty($model->error_list)){
                Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Save Done'));
                $this->redirect(Yii::app()->createUrl('customer/import'));
            }else{
                $model->exportExcel();
            }
        }else{
            $message = CHtml::errorSummary($model);
            Dialog::message(Yii::t('dialog','Validation Message'), $message);
            $this->render('form',array('model'=>$model));
            //$this->redirect(Yii::app()->createUrl('customer/import'));
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


    public function actionFileupload($doctype) {
        $model = new CustomerForm();
        if (isset($_POST['CustomerForm'])) {
            $model->attributes = $_POST['CustomerForm'];

            $id = ($_POST['CustomerForm']['scenario']=='new') ? 0 : $model->id;
            $docman = new DocMan($model->docType,$id,get_class($model));
            $docman->masterId = $model->docMasterId[strtolower($doctype)];
            if (isset($_FILES[$docman->inputName])) $docman->files = $_FILES[$docman->inputName];
            $docman->fileUpload();
            echo $docman->genTableFileList(false);
        } else {
            echo "NIL";
        }
    }

    public function actionFileRemove($doctype) {
        $model = new CustomerForm();
        if (isset($_POST['CustomerForm'])) {
            $model->attributes = $_POST['CustomerForm'];

            $docman = new DocMan($model->docType,$model->id,'CustomerForm');
            $docman->masterId = $model->docMasterId[strtolower($doctype)];
            $docman->fileRemove($model->removeFileId[strtolower($doctype)]);
            echo $docman->genTableFileList(false);
        } else {
            echo "NIL";
        }
    }

    public function actionFileDownload($mastId, $docId, $fileId, $doctype) {
        $sql = "select firm_id from sev_customer_firm where id = $docId";
        $row = Yii::app()->db->createCommand($sql)->queryRow();
        if ($row) {
            $firm_list = Yii::app()->user->firm_list();
            if (in_array($row['firm_id'],$firm_list)) {
                $docman = new DocMan($doctype,$docId,'CustomerForm');
                $docman->masterId = $mastId;
                $docman->fileDownload($fileId);
            } else {
                throw new CHttpException(404,'Access right not match.');
            }
        } else {
            throw new CHttpException(404,'Record not found.');
        }
    }

    public function actionTest(){
        $model = new FunctionForm();
        $model->refreshGroupAll();
        var_dump("end");
        Yii::app()->end();
    }
}