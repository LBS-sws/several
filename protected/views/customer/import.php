<?php
$this->pageTitle=Yii::app()->name . ' - Customer Import';
?>
<style>
    #staffDiv .checkbox-inline{width: 100px;}
    .hind-bg{position: fixed;top:0px;left: 0px;right: 0px;bottom:0px;background: rgba(0,0,0,.3);z-index: 99999;overflow: hidden;display:none;padding:0px 10px;}
    .ajaxBool{position: absolute;top: 50%;left: 50%;width: 240px;height: 50px;line-height: 50px;margin-left:-120px;margin-top:-25px;background: #fff;border-radius: 5px;box-shadow:5px 5px 5px #777;overflow: hidden;text-align: center;}

</style>
<?php $form=$this->beginWidget('TbActiveForm', array(
'id'=>'customer-form',
'enableClientValidation'=>true,
'clientOptions'=>array('validateOnSubmit'=>true),
'layout'=>TbHtml::FORM_LAYOUT_HORIZONTAL,
    'htmlOptions'=>array('enctype' => 'multipart/form-data')
)); ?>

<section class="content-header">
	<h1>
		<strong><?php echo Yii::t('app','import several'); ?></strong>
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
        <?php echo TbHtml::button('<span class="fa fa-upload"></span> '.Yii::t('several','import'), array(
            'submit'=>Yii::app()->createUrl('customer/importSave'),'id'=>"formSubmit"));
        ?>
	</div>
	</div></div>

	<div class="box box-info">
		<div class="box-body">

            <div class="form-group">
                <?php echo $form->labelEx($model,'firm_id',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->dropDownList($model, 'firm_id',FirmForm::getFirmList(),
                        array('readonly'=>(false),'class'=>'form-control')
                    ); ?>
                </div>
            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model,'file',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-5">
                    <?php echo $form->fileField($model, 'file',
                        array('readonly'=>(false),'class'=>'form-control')
                    ); ?>
                </div>
            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model,'cover_bool',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->dropDownList($model, 'cover_bool',$model->getCoverType(),
                        array('readonly'=>(false),'class'=>'form-control')
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-5 col-sm-offset-2">
                    <p class="form-control-static text-danger">如果导入失败会下载一个excel，下载后请手动刷新页面</p>
                </div>
            </div>
		</div>
	</div>
</section>
<div class="hind-bg" id="hind_bg">
    <div class="ajaxBool">正在导入，请耐心等待&nbsp;<i class="fa fa-spinner fa-pulse"></i></div>
</div>
<?php
$js = "
$('#formSubmit').on('click',function(){
    $('#hind_bg').show();
});
";
Yii::app()->clientScript->registerScript('calcFunction',$js,CClientScript::POS_READY);

$js = Script::genReadonlyField();
Yii::app()->clientScript->registerScript('readonlyClass',$js,CClientScript::POS_READY);
?>

<?php $this->endWidget(); ?>

</div><!-- form -->

