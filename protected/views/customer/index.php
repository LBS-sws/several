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
    .update-row{position: relative}
    .update-row>td{position: relative}

    .table-responsive>div:last-child{position: relative;overflow-x: hidden;width: 100%;}
    #tblData{table-layout:fixed;position: relative;left: 0px;top:0px;}
    #tblData thead>th{min-width: 30px;}
    #tblData td{word-break: break-all;}

    .scroll_table{position: fixed;bottom: 0px;overflow: hidden;padding: 0px 30px;margin-left: -15px;z-index: 99}
    .scroll_table>div{overflow-x: auto;overflow-y: hidden;}
    .scroll_table>div>div{width: 200%;height: 1px;}
    @media (min-width: 768px){
        .scroll_table{width: 750px;}
        .modal-dialog {
            width: 730px;
        }
        .modal-dialog>div{padding-right: 10px;}
    }
    @media (min-width: 992px){
        .scroll_table{width: 970px;}
    }
    @media (min-width: 1200px){
        .scroll_table{width: 1170px;}
    }
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
        //'firm_name',
        'client_code',
        'customer_name',
        'company_code',
        //'curr',
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
       //'search_add_html'=>$search_add_html,
        'search'=>$search,
    ));
    ?>
</section>
<div class="scroll_table" id="scroll_table">
    <div class="scroll_box" id="scroll_box">
        <div class="scroll_span" id="scroll_span"></div>
    </div>
</div>
<style>
</style>
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
const CUSTOMER = ['payment','remark','acca_username','acca_phone','acca_discount','acca_lang','acca_fun','status_type','remarkHtml','acca_fax','refer_code','usual_date','head_worker','other_worker','advance_name','listing_name','listing_email','listing_fax','new_month','lbs_month','other_month'];

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
                $('.usual_date').datepicker({autoclose: true, format: 'yyyy-mm-dd',language: 'zh_cn',endDate:new Date()});
            }else{
                $('#update_window .modal-content').html('權限不足');
            }
        }
    });
});

$('body').delegate('input.refer_code','keyup',function(){
    var refer_code=$(this).val();
    if(refer_code == 6 || refer_code==8){
        $('select.on_off').val(0);
    }else{
        $('select.on_off').val(1);
    }
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
                $.each(CUSTOMER, function(i, n) {
                    if(trObject.children('td.'+n).length>0){
                        trObject.children('td.'+n).text(d['updateWindow['+n+']']);
                    }
                });
                
                trObject.children('td.status_type').text('".Yii::t("code","y")."');
                trObject.children('td.acca_lang').text($('#updateWindow_acca_lang>option:selected').text());
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
        $.each(CUSTOMER, function(i, n) {
            if($('#updateWindow_'+n).length>0){
                $('#updateWindow_'+n).val(fromAge['updateWindow['+n+']']);
            }
        });
    }
});

$('.changeTableTop').on('click',function(){
    var myOpen = $(this).data('oc');
    var firm_id = $(this).data('firm');
    console.log(myOpen);
    console.log(firm_id);
    if(myOpen == 'close'){
        $('th[data-firm=\"'+firm_id+'\"]').data('oc','open');
        $('*[data-firm=\"'+firm_id+'\"]:not(.notSum)').show();
    }else{
        $('th[data-firm=\"'+firm_id+'\"]').data('oc','close');
        $('*[data-firm=\"'+firm_id+'\"]:not(.notSum)').hide();
    }
});
$(function(){
    $('#scroll_span').width($('#tblData').width());
    $(window).scroll(function(){
        var top = $('#tblData').height()+$('#tblData').offset().top;
        if(top<$(window).scrollTop()+$(window).height()){
            $('#scroll_table').css({
                'position':'absolute',
                'top':top+'px',
                'bottom':'auto'
            });
        }else{
            $('#scroll_table').css({
                'position':'fixed',
                'top':'auto',
                'bottom':'0px'
            });
        }
    });
    $('#scroll_box').scroll(function(){
        $('#tblData').parent('div').scrollLeft($(this).scrollLeft());
        //var leftNum = (-1)*$(this).scrollLeft()-2;
        //$('#tblData').css('left',leftNum+'px');
    }).trigger('scroll');
});
";
Yii::app()->clientScript->registerScript('calcFunction',$js,CClientScript::POS_READY);
$js = Script::genTableRowClick();
Yii::app()->clientScript->registerScript('rowClick',$js,CClientScript::POS_READY);
?>

