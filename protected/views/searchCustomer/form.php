<?php
if (empty($model->id)&&$model->scenario == "edit"){
    $this->redirect(Yii::app()->createUrl('searchCustomer/index'));
}
$this->pageTitle=Yii::app()->name . ' - searchCustomer Form';
?>
<?php $form=$this->beginWidget('TbActiveForm', array(
    'id'=>'searchCustomer-form',
    'enableClientValidation'=>true,
    'clientOptions'=>array('validateOnSubmit'=>true),
    'layout'=>TbHtml::FORM_LAYOUT_HORIZONTAL,
    'htmlOptions'=>array('enctype' => 'multipart/form-data')
)); ?>

<section class="content-header">
    <h1>
        <strong><?php echo Yii::t('app','Search Customer'); ?></strong>
    </h1>
    <!--
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Layout</a></li>
            <li class="active">Top Navigation</li>
        </ol>
    -->
</section>

<section class="content">
    <div class="box"><div class="box-body">
            <div class="btn-group" role="group">
                <?php echo TbHtml::button('<span class="fa fa-reply"></span> '.Yii::t('misc','Back'), array(
                    'submit'=>Yii::app()->createUrl('searchCustomer/index')));
                ?>
            </div>
        </div></div>

    <div class="box box-info">
        <div class="box-body">
            <?php echo $form->hiddenField($model, 'scenario'); ?>
            <?php echo $form->hiddenField($model, 'id'); ?>

            <legend><?php echo Yii::t("several","clients info"); ?></legend>
            <div class="form-group">
                <?php echo $form->labelEx($model,'client_code',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-5">
                    <?php echo $form->textField($model, 'client_code',
                        array('readonly'=>(true))
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'customer_name',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-5">
                    <?php echo $form->textField($model, 'customer_name',
                        array('readonly'=>(true))
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'company_code',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-5">
                    <?php echo $form->textField($model, 'company_code',
                        array('readonly'=>(true))
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'salesman_id',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo TbHtml::textField("salesman_id",StaffForm::getStaffNameToId($model->salesman_id),array('readonly'=>(true))) ?>
                </div>
                <?php echo $form->labelEx($model,'phone',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo TbHtml::textField("phone",StaffForm::getStaffPhoneToId($model->staff_id),array('readonly'=>(true))) ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'staff_id',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo TbHtml::textField("staff_id",StaffForm::getStaffNameToId($model->staff_id),array('readonly'=>(true))) ?>
                </div>
                <?php echo $form->labelEx($model,'phone',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo TbHtml::textField("phone",StaffForm::getStaffPhoneToId($model->staff_id),array('readonly'=>(true))) ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'lud',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->textField($model, 'lud',
                        array('readonly'=>(true))
                    ); ?>
                </div>
            </div>
            <legend>&nbsp;</legend>
            <div class="form-group">
                <?php echo $form->labelEx($model,'acca_username',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->textField($model, 'acca_username',
                        array('readonly'=>(true))
                    ); ?>
                </div>
                <?php echo $form->labelEx($model,'acca_phone',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->textField($model, 'acca_phone',
                        array('readonly'=>(true))
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'acca_lang',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->dropDownList($model, 'acca_lang',FunctionForm::getAllLang(),
                        array('readonly'=>(true))
                    ); ?>
                </div>
                <?php echo $form->labelEx($model,'acca_discount',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->textField($model, 'acca_discount',
                        array('readonly'=>(true))
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'acca_fun',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->textField($model, 'acca_fun',
                        array('readonly'=>(true))
                    ); ?>
                </div>
                <?php echo $form->labelEx($model,'payment',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->textField($model, 'payment',
                        array('readonly'=>(true))
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'on_off',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->dropDownList($model, 'on_off',FunctionForm::getServiceList(),
                        array('readonly'=>(true))
                    ); ?>
                </div>
                <?php echo $form->labelEx($model,'pay_type',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->dropDownList($model, 'pay_type',FunctionForm::getPayList(),
                        array('readonly'=>(true))
                    ); ?>
                </div>
            </div>
            <legend><?php echo Yii::t("several","arrears info"); ?></legend>
            <?php

            $html = $model->printInfoBody();
            echo $html;

            ?>
        </div>
    </div>
</section>

<?php
$this->renderPartial('//site/removedialog');
?>
<?php
$js = "
";
Yii::app()->clientScript->registerScript('calcFunction',$js,CClientScript::POS_READY);
$js = Script::genDeleteData(Yii::app()->createUrl('staff/delete'));
Yii::app()->clientScript->registerScript('deleteRecord',$js,CClientScript::POS_READY);

$js = Script::genReadonlyField();
Yii::app()->clientScript->registerScript('readonlyClass',$js,CClientScript::POS_READY);
?>

<?php $this->endWidget(); ?>

</div><!-- form -->

