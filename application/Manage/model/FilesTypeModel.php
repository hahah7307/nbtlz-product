<?php

namespace app\Manage\model;

use think\Model;

class FilesTypeModel extends Model
{
    const STATE_ACTIVE = 1;

    protected $name = 'files_type';

    protected $resultSetType = 'collection';
}
