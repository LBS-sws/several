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
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }
    public static function allowReadWrite() {
        return Yii::app()->user->validRWFunction('MR03');
    }

    public static function allowReadOnly() {
        return Yii::app()->user->validFunction('MR03');
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
        $row = Yii::app()->db->createCommand()->select("file_url")->from("sev_file")
            ->where("state='F' and id=:id and lcu = '$uid'", array(':id'=>$index))->queryRow();

        if ($row) {
            $file_url = $row["file_url"];

            $name = "導入異常";
            $ctype = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
            $filename=realpath($file_url); //文件名
            Header( "Content-type:  $ctype ");
            Header( "Accept-Ranges:  bytes ");
            Header( "Accept-Length: " .filesize($filename));
            header( "Content-Disposition:  attachment;  filename= {$name}.xlsx");
            echo file_get_contents($filename);
            readfile($filename);
            Yii::app()->end();
        } else
            Yii::app()->end();
    }
}