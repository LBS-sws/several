<?php

/**
 * Created by PhpStorm.
 * User: 沈超
 * Date: 2017/6/7 0007
 * Time: 上午 11:30
 */
class ImportController extends Controller
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
                'actions'=>array('index','view'),
                'expression'=>array('ImportController','allowReadOnly'),
            ),
            array('allow',
                'actions'=>array('edit','save'),
                'expression'=>array('ImportController','allowReadWrite'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }
    public static function allowReadWrite() {
        return Yii::app()->user->validFunction('BR02');
    }

    public static function allowReadOnly() {
        return Yii::app()->user->validFunction('BR03')||Yii::app()->user->validFunction('BR02')||Yii::app()->user->validFunction('BR01');
    }

    public function actionEdit()
    {
        $model = new ImportForm('new');
        $this->render('form',array('model'=>$model,));
    }

    public function actionSave()
    {
        if (isset($_POST['ImportForm'])) {
            $model = new ImportForm($_POST['ImportForm']['scenario']);
            $model->attributes = $_POST['ImportForm'];
            if ($model->validate()) {
                $model->saveData();
                Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Report submitted. Please go to Report Manager to retrieve the output.'));
                $this->redirect(Yii::app()->createUrl('import/edit'));
            } else {
                $message = CHtml::errorSummary($model);
                Dialog::message(Yii::t('dialog','Validation Message'), $message);
                $this->render('form',array('model'=>$model,));
            }
        }
    }

    public function actionIndex($pageNum=0){
        $model = new ImportList;
        if (isset($_POST['ImportList'])) {
            $model->attributes = $_POST['ImportList'];
        } else {
            $session = Yii::app()->session;
            if (isset($session['import_01']) && !empty($session['import_01'])) {
                $criteria = $session['import_01'];
                $model->setCriteria($criteria);
            }
        }
        $model->determinePageNum($pageNum);
        $model->retrieveDataByPage($model->pageNum);
        $this->render('index',array('model'=>$model));
    }

    public function actionView($index) {
        $uid = Yii::app()->user->id;
        $row = Yii::app()->db->createCommand()->select("file_url,handle_name,file_name")->from("sev_file")
            ->where("state in ('F','S') and id=:id and lcu = '$uid'", array(':id'=>$index))->queryRow();

        if ($row) {
            $file_url = $row["file_url"];

            $name = $row["handle_name"]=="追数导入"?"導入異常.xlsx":$row["file_name"];
            $ctype = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
            $filename=realpath($file_url); //文件名
            Header( "Content-type:  $ctype ");
            Header( "Accept-Ranges:  bytes ");
            Header( "Accept-Length: " .filesize($filename));
            header( "Content-Disposition:  attachment;  filename=$name");
            echo file_get_contents($filename);
            readfile($filename);
            Yii::app()->end();
        } else
            Yii::app()->end();
    }
}