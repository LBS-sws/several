/*
 $('#example').DataTable({
 language: {
 "sProcessing": "处理中...",
 "sLengthMenu": "显示_MENU_ 项结果",
 "sZeroRecords": "没有匹配结果",
 "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
 "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
 "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
 "sInfoPostFix": "",
 "sSearch": "搜索:",
 "sUrl": "",
 "sEmptyTable": "表中数据为空",
 "sLoadingRecords": "载入中...",
 "sInfoThousands": ",",
 "oPaginate": {
 "sFirst": "首页",
 "sPrevious": "上页",
 "sNext": "下页",
 "sLast": "末页"
 },
 "oAria": {
 "sSortAscending": ": 以升序排列此列",
 "sSortDescending": ": 以降序排列此列"
 }
 }
 });
 */
var dataTableLanguage={
    "zh-cn":{
        "sProcessing": "处理中...",
        "sLengthMenu": "显示 &nbsp;_MENU_ &nbsp;项结果",
        "sZeroRecords": "没有匹配结果",
        "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
        "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
        "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
        "sInfoPostFix": "",
        "sSearch": "搜索:",
        "sUrl": "",
        "sEmptyTable": "表中数据为空",
        "sLoadingRecords": "载入中...",
        "sInfoThousands": ",",
        "oPaginate": {
            "sFirst": "首页",
            "sPrevious": "上页",
            "sNext": "下页",
            "sLast": "末页"
        },
        "oAria": {
            "sSortAscending": ": 以升序排列此列",
            "sSortDescending": ": 以降序排列此列"
        }
    },
    "zh-tw":{
        "sProcessing": "處理中...",
        "sLengthMenu": "顯示 &nbsp;_MENU_ &nbsp;項結果",
        "sZeroRecords": "沒有匹配結果",
        "sInfo": "顯示第 _START_ 至 _END_ 項結果，共 _TOTAL_ 項",
        "sInfoEmpty": "顯示第 0 至 0 項結果，共 0 項",
        "sInfoFiltered": "(由 _MAX_ 項結果過濾)",
        "sInfoPostFix": "",
        "sSearch": "搜索:",
        "sUrl": "",
        "sEmptyTable": "表中數據為空",
        "sLoadingRecords": "載入中...",
        "sInfoThousands": ",",
        "oPaginate": {
            "sFirst": "首頁",
            "sPrevious": "上頁",
            "sNext": "下頁",
            "sLast": "末頁"
        },
        "oAria": {
            "sSortAscending": ": 以升序排列此列",
            "sSortDescending": ": 以降序排列此列"
        }
    },
    "en-us":{
        oAria:{
            sSortAscending:": activate to sort column ascending",
            sSortDescending:": activate to sort column descending"
        },
        oPaginate:{
            sFirst:"First",
            sLast:"Last",
            sNext:"Next",
            sPrevious:"Previous"
        },
        sEmptyTable:"No data available in table",
        sInfo:"Show _START_ to _END_ results, total _TOTAL_ results",
        sInfoEmpty:"Showing 0 to 0 of 0 results",
        sInfoFiltered:"(filtered from _MAX_ total results)",
        sInfoPostFix:"",
        sDecimal:"",
        sThousands:",",
        sLengthMenu:"Show _MENU_ results",
        sLoadingRecords:"Loading...",
        sProcessing:"Processing...",
        sSearch:"Search:",
        sSearchPlaceholder:"",
        sUrl:"",
        sZeroRecords:"No matching records found"
    }
};

$.extend( $.fn.dataTable.defaults, {
    language: dataTableLanguage["zh-cn"]
} );