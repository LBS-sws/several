<?php

/**
 * Created by PhpStorm.
 * User: 沈超
 * Date: 2017/6/7 0007
 * Time: 上午 11:30
 */
class GroupController extends Controller
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
                'actions'=>array('edit','new','save','delete','import','sysgen'),
                'expression'=>array('GroupController','allowReadWrite'),
            ),
            array('allow',
                'actions'=>array('index','view'),
                'expression'=>array('GroupController','allowReadOnly'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }
    public static function allowReadWrite() {
        return Yii::app()->user->validRWFunction('XR01');
    }

    public static function allowReadOnly() {
        return Yii::app()->user->validFunction('XR01');
    }

    public function actionImport(){
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
            $this->redirect(Yii::app()->createUrl('group/index'));
        }
        $url = "upload/excel/".$city."/".date("YmdHis").".".$img->getExtensionName();
        $model->file = $img->getName();
        if ($model->file) {
            $img->saveAs($url);
            $loadExcel = new LoadExcel($url);
            $list = $loadExcel->getExcelList();
            $model->loadGroup($list);
            $this->redirect(Yii::app()->createUrl('group/index'));
        }else{
            $message = CHtml::errorSummary($model);
            Dialog::message(Yii::t('dialog','Validation Message'), $message);
            $this->redirect(Yii::app()->createUrl('group/index'));
        }
    }

    public function actionIndex($pageNum=0){
        $model = new GroupList;
        if (isset($_POST['GroupList'])) {
            $model->attributes = $_POST['GroupList'];
        } else {
            $session = Yii::app()->session;
            if (isset($session['group_01']) && !empty($session['group_01'])) {
                $criteria = $session['group_01'];
                $model->setCriteria($criteria);
            }
        }
        $model->determinePageNum($pageNum);
        $model->retrieveDataByPage($model->pageNum);
        $this->render('index',array('model'=>$model));
    }

    public function actionNew()
    {
        $model = new GroupForm('new');
        $this->render('form',array('model'=>$model,));
    }

    public function actionEdit($index)
    {
        $model = new GroupForm('edit');
        if (!$model->retrieveData($index)) {
            throw new CHttpException(404,'The requested page does not exist.');
        } else {
            $this->render('form',array('model'=>$model,));
        }
    }

    public function actionView($index)
    {
        $model = new GroupForm('view');
        if (!$model->retrieveData($index)) {
            throw new CHttpException(404,'The requested page does not exist.');
        } else {
            $this->render('form',array('model'=>$model,));
        }
    }

    public function actionSave()
    {
        if (isset($_POST['GroupForm'])) {
            $model = new GroupForm($_POST['GroupForm']['scenario']);
            $model->attributes = $_POST['GroupForm'];
            if ($model->validate()) {
                $model->saveData();
                Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Save Done'));
                $this->redirect(Yii::app()->createUrl('group/edit',array('index'=>$model->id)));
            } else {
                $message = CHtml::errorSummary($model);
                Dialog::message(Yii::t('dialog','Validation Message'), $message);
                $this->render('form',array('model'=>$model,));
            }
        }
    }

    //刪除公司
    public function actionDelete(){
        $model = new GroupForm('delete');
        if (isset($_POST['GroupForm'])) {
            $model->attributes = $_POST['GroupForm'];
            if($model->validateDelete()){
                $model->saveData();
                Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Record Deleted'));
            }else{
                Dialog::message(Yii::t('dialog','Information'), "刪除失败");
                $this->redirect(Yii::app()->createUrl('group/edit',array('index'=>$model->id)));
            }
        }
        $this->redirect(Yii::app()->createUrl('group/index'));
    }

    //刪除公司
    public function actionSysgen(){
        if(Yii::app()->request->isAjaxRequest) {//是否ajax请求
            $model = new GroupForm();
            $rs = $model->getSysgen();
            echo CJSON::encode($rs);//Yii 的方法将数组处理成json数据
        }else{
            $this->redirect(Yii::app()->createUrl('group/index'));
        }
    }

}