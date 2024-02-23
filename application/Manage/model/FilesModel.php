<?php

namespace app\Manage\model;

use think\Model;

class FilesModel extends Model
{
    protected $name = 'files';

    protected $resultSetType = 'collection';

    public function sku(): \think\model\relation\HasOne
    {
        return $this->hasOne('SellerSkuModel', 'id', 'seller_sku_id');
    }

    public function type(): \think\model\relation\HasOne
    {
        return $this->hasOne('FilesTypeModel', 'id', 'file_type');
    }

    public function seller(): \think\model\relation\HasOne
    {
        return $this->hasOne('AccountModel', 'id', 'seller_id');
    }

    public function painter(): \think\model\relation\HasOne
    {
        return $this->hasOne('AccountModel', 'id', 'painter_id');
    }

    public function server(): \think\model\relation\HasOne
    {
        return $this->hasOne('AccountModel', 'id', 'server_id');
    }
}
