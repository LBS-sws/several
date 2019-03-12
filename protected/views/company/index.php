<?php
$this->pageTitle=Yii::app()->name . ' - Company';
?>

<?php $form=$this->beginWidget('TbActiveForm', array(
    'id'=>'company-list',
    'enableClientValidation'=>true,
    'clientOptions'=>array('validateOnSubmit'=>true,),
    'layout'=>TbHtml::FORM_LAYOUT_INLINE,
)); ?>

<section class="content-header">
    <h1>
        <strong><?php echo Yii::t('app','client company'); ?></strong>
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
            <div style="padding: 5px;" class="text-danger">本頁面的數據方便系統識別LBS有幾個客戶公司，導入追數的excel系統會自動添加公司，也可手動添加</div>
            <div class="btn-group" role="group">
                <?php
                //var_dump(Yii::app()->session['rw_func']);
                if (Yii::app()->user->validRWFunction('XR02'))
                    echo TbHtml::button('<span class="fa fa-file-o"></span> '.Yii::t('misc','Add'), array(
                        'submit'=>Yii::app()->createUrl('company/new'),
                    ));
                ?>
            </div>
        </div>
    </div>
    <?php
    $search = array(
        'client_code',
        'customer_name',
    );
/*    $search_add_html="";
    $modelName = get_class($model);
    $search_add_html .= TbHtml::dropDownList($modelName.'[searchArrears]',$model->searchArrears,$model->getArrearsList(),
        array('size'=>15,'placeholder'=>Yii::t('misc','Start Date'),"class"=>"form-control","id"=>"start_time"));*/

   $this->widget('ext.layout.ListPageWidget', array(
        'title'=>Yii::t('app','client company'),
        'model'=>$model,
        'viewhdr'=>'//company/_listhdr',
        'viewdtl'=>'//company/_listdtl',
        'gridsize'=>'24',
        'height'=>'600',
       //'search_add_html'=>$search_add_html,
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

