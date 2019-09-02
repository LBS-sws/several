<?php

return array(
    'accounting modules'=>array(//會計模塊
        'access'=>'CU',
        'items'=>array(
            'Customer List'=>array(//追數列表
                'access'=>'CU02',
                'url'=>'/customer/index',
            ),
            'Batch Modifying'=>array(//追數列表
                'access'=>'CU03',
                'url'=>'/batchModify/edit',
            ),
        ),
    ),
    'manager module'=>array(//經理模塊
        'access'=>'MR',
        'items'=>array(
/*            'import several'=>array(//導入追數
                'access'=>'MR01',
                'url'=>'/customer/import',
            ),*/
            'New After Clients'=>array(
                'access'=>'MR02',
                'url'=>'/clients/index',
            ),
/*            'import several info'=>array(
                'access'=>'MR03',
                'url'=>'/import/index',
            ),*/
        ),
    ),
	'Security'=>array(//保安
		'access'=>'XD',
		'items'=>array(
			'User'=>array(//用戶管理
				'access'=>'XD01',
				'url'=>'/user/index',
				'tag'=>'@',
			),
		),
	),
    'System Setting'=>array(//系統設置
        'access'=>'XR',
        'items'=>array(
            'Group number'=>array( //集團編號
                'access'=>'XR01',
                'url'=>'/group/index',
            ),
            'client company'=>array(//客户公司
                'access'=>'XR02',
                'url'=>'/company/index',
            ),
            'Staff List'=>array(//員工列表
                'access'=>'XR03',
                'url'=>'/staff/index',
            ),
            'Firm List'=>array(//公司列表
                'access'=>'XR04',
                'url'=>'/firm/index',
            ),
            'Automatic Staff'=>array(//公司列表
                'access'=>'XR05',
                'url'=>'/automatic/index',
            ),
        ),
    ),
    'Search'=>array(//查詢
        'access'=>'BC',
        'items'=>array(
            'Search Group'=>array( //集團查詢
                'access'=>'BC01',
                'url'=>'/searchGroup/index',
            ),
            'Search Company'=>array(//客户公司查詢
                'access'=>'BC02',
                'url'=>'/searchCompany/index',
            ),
            'Search Firm'=>array(//LBS公司查詢
                'access'=>'BC03',
                'url'=>'/searchFirm/index',
            ),
            'Search Staff'=>array(//指派員工查詢
                'access'=>'BC04',
                'url'=>'/searchStaff/index',
            ),
            'Search Customer'=>array(//查询追数详情
                'access'=>'BC05',
                'url'=>'/searchCustomer/index',
            ),
        ),
    ),
    'Report'=>array(//報表
        'access'=>'BR',
        'items'=>array(
            'import several'=>array(//導入追數
                'access'=>'BR01',
                'url'=>'/customer/import',
            ),
            'export several'=>array(//導出追數
                'access'=>'BR02',
                'url'=>'/import/edit',
            ),
            'Report Manager'=>array(//報表管理員
                'access'=>'BR03',
                'url'=>'/import/index',
            ),
        ),
    ),
);
