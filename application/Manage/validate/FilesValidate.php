<?php

namespace app\Manage\validate;

use think\Validate;

class FilesValidate extends Validate
{
    protected $rule = [
        'file_url'          =>  'require',
        'seller_sku_id'     =>  'require',
        'file_type'         =>  'require',
        'file_name'         =>  'require',
        'seller_id'         =>  'require',
        'painter_id'        =>  'require',
        'file_tmp_url'      =>  'require',
        'file_tmp_expire'   =>  'require',
        'file_path'         =>  'require',
    ];

    protected $message = [
        
    ];

    protected $field = [
        'file_url'          =>  '文件路径',
        'seller_sku_id'     =>  'SkuId',
        'file_type'         =>  '文件类型',
        'file_name'         =>  '文件名称',
        'seller_id'         =>  '运营人员',
        'painter_id'        =>  '美工人员',
        'file_tmp_url'      =>  '临时文件路径',
        'file_tmp_expire'   =>  '临时文件过期时间',
        'file_path'         =>  '文件路径',
    ];

    protected $scene = [
        'add'           =>  ['file_url', 'file_path', 'seller_sku_id', 'file_type', 'file_name', 'seller_id', 'painter_id'],
        'edit'          =>  ['file_tmp_url', 'file_tmp_expire'],
    ];
}
