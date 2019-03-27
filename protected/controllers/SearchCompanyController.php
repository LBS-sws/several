<?php

/**
 * Created by PhpStorm.
 * User: 沈超
 * Date: 2017/6/7 0007
 * Time: 上午 11:30
 */
class SearchCompanyController extends Controller
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
                'actions'=>array('index','view','export','fileDownload'),
                'expression'=>array('SearchCompanyController','allowReadOnly'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }
    public static function allowReadWrite() {
        return Yii::app()->user->validRWFunction('BC02');
    }

    public static function allowReadOnly() {
        return Yii::app()->user->validFunction('BC02');
    }

    public function actionIndex($pageNum=0){
        $model = new SearchCompanyList;
        if (isset($_POST['SearchCompanyList'])) {
            $model->attributes = $_POST['SearchCompanyList'];
        } else {
            $session = Yii::app()->session;
            if (isset($session['searchCompany_01']) && !empty($session['searchCompany_01'])) {
                $criteria = $session['searchCompany_01'];
                $model->setCriteria($criteria);
            }
        }
        $model->determinePageNum($pageNum);
        $model->retrieveDataByPage($model->pageNum);
        $this->render('index',array('model'=>$model));
    }

    public function actionView($index,$year='year')
    {
        $model = new SearchCompanyForm('view');
        $model->customer_year = $year;
        if (!$model->retrieveData($index)) {
            throw new CHttpException(404,'The requested page does not exist.');
        } else {
            $this->render('form',array('model'=>$model,));
        }
    }


    public function actionFileDownload($mastId, $docId, $fileId, $doctype) {
        $sql = "select firm_id from sev_customer_firm where id = $docId";
        $row = Yii::app()->db->createCommand($sql)->queryRow();
        if ($row) {
            $docman = new DocMan($doctype,$docId,'CustomerForm');
            $docman->masterId = $mastId;
            $docman->fileDownload($fileId);
        } else {
            throw new CHttpException(404,'Record not found.');
        }
    }
}