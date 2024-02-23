<?php

namespace app\Manage\validate;

use think\Validate;
use think\Db;

class SellerSkuValidate extends Validate
{
    protected $rule = [
        'product_sku'           =>  'require',
        'product_name_cn'       =>  'require',
        'sku_file_name'         =>  'require',
        'seller_id'             =>  'require',
        'painter_id'            =>  'require',
    ];

    protected $message = [
        
    ];

    protected $field = [
        'product_sku'           =>  '产品SKU',
        'product_name_cn'       =>  '产品中文名称',
        'sku_file_name'         =>  '产品目录名称',
        'seller_id'             =>  '运营人员',
        'painter_id'            =>  '美工人员',
    ];

    protected $scene = [
        'add'           =>  ['product_sku', 'product_name_cn', 'sku_file_name', 'seller_id', 'painter_id'],
        'edit'          =>  ['product_sku', 'product_name_cn', 'sku_file_name', 'seller_id'],
    ];
}
