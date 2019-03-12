<?php

return array(
    'accounting modules'=>array(//會計模塊
        'access'=>'CU',
        'items'=>array(
            'Customer List'=>array(//追數列表
                'access'=>'CU02',
                'url'=>'/customer/index',
            ),
        ),
    ),
    'manager module'=>array(//經理模塊
        'access'=>'MR',
        'items'=>array(
            'import several'=>array(//導入追數
                'access'=>'MR01',
                'url'=>'/customer/import',
            ),
            'New After Clients'=>array(
                'access'=>'MR02',
                'url'=>'/clients/index',
            ),
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
        ),
    ),
);
