<?php
$this->pageTitle=Yii::app()->name . ' - searchGroup';
?>

<?php $form=$this->beginWidget('TbActiveForm', array(
    'id'=>'searchGroup-list',
    'enableClientValidation'=>true,
    'clientOptions'=>array('validateOnSubmit'=>true,),
    'layout'=>TbHtml::FORM_LAYOUT_INLINE,
)); ?>

<section class="content-header">
    <h1>
        <strong><?php echo Yii::t('app','Search Group'); ?></strong>
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
    <div class="box">
        <div class="box-body">
            <div style="display: inline-block" class="text-danger">
                <p>出现次数：集团编号出现的次数</p>
                <p>欠款次数：集团编号有欠款的次数</p>
            </div>
            <!--
            <div class="btn-group pull-right" role="group">
                <?php
                echo TbHtml::button('<span class="fa fa-download"></span> '.Yii::t('several','export'), array(
                    'submit'=>Yii::app()->createUrl('searchGroup/export'),
                ));
                ?>
            </div>
            -->
        </div>
    </div>
    <?php
    $search = array(
        'company_code',
    );

   $this->widget('ext.layout.ListPageWidget', array(
        'title'=>Yii::t('several','group list'),
        'model'=>$model,
        'viewhdr'=>'//searchGroup/_listhdr',
        'viewdtl'=>'//searchGroup/_listdtl',
        'gridsize'=>'24',
        'height'=>'600',
        'search'=>$search,
    ));
    ?>
</section>
<?php
echo $form->hiddenField($model,'pageNum');
echo $form->hiddenField($model,'totalRow');
echo $form->hiddenField($model,'orderField');
echo $form->hiddenField($model,'orderType');
?>
<?php $this->endWidget(); ?>

<?php
$js = Script::genTableRowClick();
Yii::app()->clientScript->registerScript('rowClick',$js,CClientScript::POS_READY);
?>

