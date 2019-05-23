<?php
$this->pageTitle=Yii::app()->name . ' - searchFirm';
?>

<?php $form=$this->beginWidget('TbActiveForm', array(
    'id'=>'searchFirm-list',
    'enableClientValidation'=>true,
    'clientOptions'=>array('validateOnSubmit'=>true,),
    'layout'=>TbHtml::FORM_LAYOUT_INLINE,
)); ?>
<style>
    .s_close .glyphicon-minus:before{content:"\002b"}
</style>
<section class="content-header">
    <h1>
        <strong><?php echo Yii::t('app','Search Firm'); ?></strong>
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
            <p>出现次数：LBS公司出现的次数</p>
            <p>待收款次数：LBS公司待收款的次数</p>
            <p>注意：LBS细公司只统计显示的数据（搜索结果后的数据）</p>
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
        'firm_name'
    );

   $this->widget('ext.layout.ListPageWidget', array(
        'title'=>Yii::t('several','Firm List'),
        'model'=>$model,
        'viewhdr'=>'//searchFirm/_listhdr',
        'viewdtl'=>'//searchFirm/_listdtl',
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
$js="
resetTable();

    function resetTable(){
        var html = '';
        var listObj = {};
        $('#tblData>tbody>tr').each(function(index,tr){
            $(tr).children('td:not(:first)').each(function(key,td){
                var tdText = $(td).text();
                if(tdText == 'LBS总公司'){
                    $(tr).addClass('bigFirm');
                    return false;
                }else{
                    $(tr).addClass('smallFirm');
                    if(isNaN(tdText)){
                        listObj[key] = tdText;
                    }else{
                        if(isNaN(listObj[key])){
                            listObj[key] = 0;
                        }
                        listObj[key] += parseFloat(tdText);
                    }
                }
            });
        });
        $.each(listObj,function(index,obj){
            obj = index==0?'LBS细公司':obj;
            html+='<td>'+obj+'</td>';
        });
        $('#tblData>tbody').append('<tr class=\'toggle_tr text-primary\'><td><span class=\'glyphicon glyphicon-minus\'></span></td>'+html+'</tr>');
    }
    
    $('#tblData').delegate('.toggle_tr','click',function(){
        if($('.smallFirm:first').is(':hidden')){
            $(this).removeClass('s_close').addClass('s_open');
            $('.smallFirm').slideDown(0);
        }else{
            $(this).removeClass('s_open').addClass('s_close');
            $('.smallFirm').slideUp(0);
        }
    });

";
Yii::app()->clientScript->registerScript('calcFunction',$js,CClientScript::POS_READY);
$js = Script::genTableRowClick();
Yii::app()->clientScript->registerScript('rowClick',$js,CClientScript::POS_READY);
?>

