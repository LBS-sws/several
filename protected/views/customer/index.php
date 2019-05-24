<?php
$this->pageTitle=Yii::app()->name . ' - Customer';
?>

<?php $form=$this->beginWidget('TbActiveForm', array(
    'id'=>'customer-list',
    'enableClientValidation'=>true,
    'clientOptions'=>array('validateOnSubmit'=>true,),
    'layout'=>TbHtml::FORM_LAYOUT_INLINE,
)); ?>
<style>
    .table-responsive>div:last-child{overflow-x: auto;width: 100%;}
    #tblData{table-layout:fixed;}
    #tblData thead>th{min-width: 30px;}
    #tblData td{word-break: break-all;}
</style>

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
                <div style="padding: 5px;" class="text-danger">追數流程：經理先建立客戶關係 -> 會計記錄追數欠款情況（本頁面）<br>注意：欠款信息只顯示欠款月份</div>
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
        'company_code',
        'curr',
    );
    $search_add_html="";
    $modelName = get_class($model);
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
//$action_url = Yii::app()->createUrl('customer/updateSave');
$js = "
$('.update-row a').on('click',function(event){
    event.stopPropagation();
});

$('.update-row').on('click',function(){
    if($('#update_window').length<=0){
        var html ='<form class=\"modal fade form-horizontal\" id=\"update_window\"><div class=\"modal-dialog\"><div class=\"modal-content\">加載中</div></div></form>';
        $('body').append(html);
    }
    var id = $(this).data('id');
    $('#update_window').modal('show');
    $.ajax({
        type: 'post',
        url: '".Yii::app()->createUrl('customer/update')."',
        data: {id:id},
        dataType: 'json',
        success: function(data){
            if(data.status==1){
                $('#update_window .modal-content').html(data.html);
            }else{
                $('#update_window .modal-content').html('權限不足');
            }
        }
    });
});

$('body').delegate('#update_window','submit',function(){
    var ajaxBool = $(this).data('ajax');
    if(ajaxBool == 1){
        return false;
    }else{
        $(this).data('ajax',1);
    }
    var d = {};
    var t = $('#update_window').serializeArray();
    $.each(t, function() {
      d[this.name] = this.value;
    });
    localStorage.fromAgo = JSON.stringify(d);
    $.ajax({
        type: 'post',
        url: '".Yii::app()->createUrl('customer/updateSave')."',
        data: d,
        dataType: 'json',
        success: function(data){
            var trObject = $('.update-row[data-id=\"'+d['updateWindow[id]']+'\"]');
            $('#update_window').data('ajax',0);
            if($('#hint_window').length<=0){
                var html ='<form class=\"modal fade\" id=\"hint_window\"><div class=\"modal-dialog\"><div class=\"modal-content\">加載中</div></div></form>';
                $('body').append(html);
            }
            if(data.status==1){
                trObject.children('td.payment').text(d['updateWindow[payment]']);
                trObject.children('td.acca_username').text(d['updateWindow[acca_username]']);
                trObject.children('td.acca_phone').text(d['updateWindow[acca_phone]']);
                trObject.children('td.acca_lang').text($('#updateWindow_acca_lang>option:selected').text());
                trObject.children('td.acca_fun').text(d['updateWindow[acca_fun]']);
                trObject.children('td.curr').text(d['updateWindow[curr]']);
                trObject.children('td.status_type').text('".Yii::t("code","y")."');
                trObject.children('td.remarkHtml').html(data.remarkHtml);
                $('#update_window').modal('hide');
            }
            $('#hint_window .modal-content').html(data.html);
            $('#hint_window').modal('show');
            $('.update-row[data-id=\"'+d['id']+'\"]').text(11);
            return false;
        }
    });
    return false;
});

$('body').delegate('#btn-ago','click',function(){
    var fromAge = localStorage.fromAgo;
    if(fromAge != '' && fromAge != undefined){
        fromAge = JSON.parse(fromAge);
        $('#updateWindow_payment').val(fromAge['updateWindow[payment]']);
        $('#updateWindow_acca_username').val(fromAge['updateWindow[acca_username]']);
        $('#updateWindow_acca_phone').val(fromAge['updateWindow[acca_phone]']);
        $('#updateWindow_acca_lang').val(fromAge['updateWindow[acca_lang]']);
        $('#updateWindow_acca_fun').val(fromAge['updateWindow[acca_fun]']);
        $('#updateWindow_curr').val(fromAge['updateWindow[curr]']);
        $('#updateWindow_acca_discount').val(fromAge['updateWindow[acca_discount]']);
        $('#updateWindow_remark').val(fromAge['updateWindow[remark]']);
    }
});

";
Yii::app()->clientScript->registerScript('calcFunction',$js,CClientScript::POS_READY);
$js = Script::genTableRowClick();
Yii::app()->clientScript->registerScript('rowClick',$js,CClientScript::POS_READY);
?>

