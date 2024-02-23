<?php

namespace app\Manage\model;

use think\Model;

class SellerSkuModel extends Model
{
    const STATE_ACTIVE = 1;

    protected $name = 'seller_sku';

    protected $resultSetType = 'collection';

    protected $insert = ['created_at', 'updated_at'];

    protected $update = ['updated_at'];

    protected function setCreatedAtAttr()
    {
        return date('Y-m-d H:i:s');
    }

    protected function setUpdatedAtAttr()
    {
        return date('Y-m-d H:i:s');
    }

    public function seller(): \think\model\relation\HasOne
    {
        return $this->hasOne('AccountModel', 'id', 'seller_id');
    }

    public function painter(): \think\model\relation\HasOne
    {
        return $this->hasOne('AccountModel', 'id', 'painter_id');
    }
}
