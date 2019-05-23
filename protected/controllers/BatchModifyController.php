<?php

/**
 * Created by PhpStorm.
 * User: 沈超
 * Date: 2017/6/7 0007
 * Time: 上午 11:30
 */
class BatchModifyController extends Controller
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
                'actions'=>array('edit','save','ajaxGetDetail'),
                'expression'=>array('BatchModifyController','allowReadWrite'),
            ),
/*            array('allow',
                'actions'=>array('index','view'),
                'expression'=>array('BatchModifyController','allowReadOnly'),
            ),*/
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }
    public static function allowReadWrite() {
        return Yii::app()->user->validRWFunction('CU03');
    }

    public function actionEdit()
    {
        $model = new BatchModifyForm('edit');
        $this->render('form',array('model'=>$model,));
    }

    public function actionSave()
    {
        if (isset($_POST['BatchModifyForm'])) {
            $model = new BatchModifyForm($_POST['BatchModifyForm']['scenario']);
            $model->attributes = $_POST['BatchModifyForm'];
            if ($model->validate()) {
                $model->saveData();
                Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Save Done'));
                $this->redirect(Yii::app()->createUrl('batchModify/edit'));
            } else {
                $message = CHtml::errorSummary($model);
                Dialog::message(Yii::t('dialog','Validation Message'), $message);
                $this->render('form',array('model'=>$model,));
            }
        }
    }

    public function actionAjaxGetDetail()
    {
        if(Yii::app()->request->isAjaxRequest) {//是否ajax请求
            $model = new BatchModifyForm();
            $group_id = $_POST['group_id'];
            $rs = $model->getCustomerDetail($group_id);
            echo CJSON::encode($rs);//Yii 的方法将数组处理成json数据
        }else{
            $this->redirect(Yii::app()->createUrl('batchModify/edit'));
        }
    }

}