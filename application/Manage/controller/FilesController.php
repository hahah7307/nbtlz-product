<?php
namespace app\Manage\controller;

use think\exception\DbException;
use think\Session;
use think\Config;

class FilesController extends BaseController
{
    public function seller()
    {

    }

    public function sku()
    {

    }

    public function type()
    {

    }
    /**
     * @throws DbException
     */
    public function index(): \think\response\View
    {
        $where = [];
        $keyword = $this->request->get('keyword', '', 'htmlspecialchars');
        $this->assign('keyword', $keyword);
        if ($keyword) {
            $where['username|nickname|phone|email'] = ['like', '%' . $keyword . '%'];
        }

        // 仓库列表
        $storage = new WarehouseModel();
        $list = $storage->where($where)->order('id asc')->paginate(Config::get('PAGE_NUM'));
        $this->assign('list', $list);

        Session::set(Config::get('BACK_URL'), $this->request->url(), 'manage');
        return view();
    }
}
