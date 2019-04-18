<?php
$this->pageTitle=Yii::app()->name . ' - Customer';
?>

<?php $form=$this->beginWidget('TbActiveForm', array(
    'id'=>'customer-list',
    'enableClientValidation'=>true,
    'clientOptions'=>array('validateOnSubmit'=>true,),
    'layout'=>TbHtml::FORM_LAYOUT_INLINE,
)); ?>

<section class="content-header">
    <h1>
        <strong><?php echo Yii::t('app','Customer List'); ?></strong>
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
                <div style="padding: 5px;" class="text-danger">追數流程：經理先建立客戶關係 -> 會計記錄追數欠款情況（本頁面）</div>
                <?php
                //var_dump(Yii::app()->session['rw_func']);
/*                if (Yii::app()->user->validRWFunction('CU02'))
                    echo TbHtml::button('<span class="fa fa-file-o"></span> '.Yii::t('misc','Add'), array(
                        'submit'=>Yii::app()->createUrl('customer/new'),
                    ));*/
                ?>
            </div>
        </div></div>
    <?php
    $search = array(
        'firm_name',
        'client_code',
        'customer_name',
        'customer_year',
        'company_code',
        'curr',
    );
    $search_add_html="";
    $modelName = get_class($model);
    $yearList = UploadExcelForm::getYear();
    $yearList[""]="全部";
    $search_add_html .= TbHtml::dropDownList($modelName.'[searchYear]',$model->searchYear,$yearList,
        array('size'=>15,'placeholder'=>Yii::t('misc','Start Date'),"class"=>"form-control","id"=>"start_time"));
    $search_add_html .= TbHtml::dropDownList($modelName.'[searchArrears]',$model->searchArrears,$model->getArrearsList(),
        array('size'=>15,'placeholder'=>Yii::t('misc','Start Date'),"class"=>"form-control","id"=>"start_time"));

   $this->widget('ext.layout.ListPageWidget', array(
        'title'=>Yii::t('app','Customer List'),
        'model'=>$model,
        'viewhdr'=>'//customer/_listhdr',
        'viewdtl'=>'//customer/_listdtl',
        'gridsize'=>'24',
        'height'=>'600',
       'search_add_html'=>$search_add_html,
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

