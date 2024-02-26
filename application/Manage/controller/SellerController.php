<?php
namespace app\Manage\controller;

use app\Manage\model\AccountModel;
use app\Manage\model\SellerSkuModel;
use app\Manage\validate\SellerSkuValidate;
use think\exception\DbException;
use think\Session;
use think\Config;

class SellerController extends BaseController
{
    /**
     * @throws DbException
     */
    public function sku(): \think\response\View
    {
        $where = [];
        $user = AccountModel::get(['id'=>Session::get(Config::get('USER_LOGIN_FLAG')), 'status' => AccountModel::STATUS_ACTIVE]);
        if ($user['super'] != 1 && $user['manage'] != 1) {
            $where['seller_id'] = $user['id'];
        }

        $keyword = $this->request->get('keyword', '', 'htmlspecialchars');
        $this->assign('keyword', $keyword);
        if ($keyword) {
            $where['product_sku'] = ['like', '%' . $keyword . '%'];
        }

        //
        $sellerSkuObj = new SellerSkuModel();
        $list = $sellerSkuObj->with('user')->where($where)->order('id asc')->paginate(Config::get('PAGE_NUM'));
        $this->assign('list', $list);

        Session::set(Config::get('BACK_URL'), $this->request->url(), 'manage');
        return view();
    }

    // 添加
    /**
     * @throws DbException
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $user = AccountModel::get(['id'=>Session::get(Config::get('USER_LOGIN_FLAG')), 'status' => AccountModel::STATUS_ACTIVE]);
            $post['painter_id'] = $user['id'];
            $post['state'] = SellerSkuModel::STATE_ACTIVE;
            $post['sku_file_name'] = $post['product_sku'];
            $dataValidate = new SellerSkuValidate();
            if ($dataValidate->scene('add')->check($post)) {
                $model = new SellerSkuModel();
                $sku = $model->where(['seller_id' => $post['seller_id'], 'painter_id' => $user['id'], 'product_sku' => $post['product_sku']])->find();
                if (!empty($sku)) {
                    echo json_encode(['code' => 0, 'msg' => '您已创建过该Sku']);
                    exit;
                }
                if ($model->allowField(true)->save($post)) {
                    echo json_encode(['code' => 1, 'msg' => '添加成功']);
                } else {
                    echo json_encode(['code' => 0, 'msg' => '添加失败，请重试']);
                }
            } else {
                echo json_encode(['code' => 0, 'msg' => $dataValidate->getError()]);
            }
            exit;
        } else {
            $this->assign('seller_id', input('id'));

            $back_url = Session::get(Config::get('BACK_URL'), 'manage');
            if (in_array($this->request->url(), $back_url)) {
                array_pop($back_url);
            } else {
                $back_url[] = $this->request->url();
            }
            $this->assign('back_url', array_reverse($back_url)[1]);
            Session::set(Config::get('BACK_URL'), $back_url, 'manage');
            return view();
        }
    }

    // 编辑
    /**
     * @throws DbException
     */
    public function edit($id)
    {
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $user = AccountModel::get(['id'=>Session::get(Config::get('USER_LOGIN_FLAG')), 'status' => AccountModel::STATUS_ACTIVE]);
            $post['seller_id'] = $user['id'];
            $dataValidate = new SellerSkuValidate();
            if ($dataValidate->scene('edit')->check($post)) {
                $model = new SellerSkuModel();
                if ($model->allowField(true)->save($post, ['id' => $id])) {
                    echo json_encode(['code' => 1, 'msg' => '修改成功']);
                } else {
                    echo json_encode(['code' => 0, 'msg' => '修改失败，请重试']);
                }
            } else {
                echo json_encode(['code' => 0, 'msg' => $dataValidate->getError()]);
            }
            exit;
        } else {
            $info = SellerSkuModel::get(['id' => $id]);
            $this->assign('info', $info);

            return view();
        }
    }

    // 删除
    /**
     * @throws DbException
     */
    public function delete()
    {
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $block = SellerSkuModel::get($post['id']);
            if ($block->delete()) {
                echo json_encode(['code' => 1, 'msg' => '操作成功']);
            } else {
                echo json_encode(['code' => 0, 'msg' => '操作失败，请重试']);
            }
        } else {
            echo json_encode(['code' => 0, 'msg' => '异常操作']);
        }
        exit;
    }

    // 状态切换
    /**
     * @throws DbException
     */
    public function status()
    {
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $user = SellerSkuModel::get($post['id']);
            $user['state'] = $user['state'] == SellerSkuModel::STATE_ACTIVE ? 0 : SellerSkuModel::STATE_ACTIVE;
            $user->save();
            echo json_encode(['code' => 1, 'msg' => '操作成功']);
        } else {
            echo json_encode(['code' => 0, 'msg' => '异常操作']);
        }
        exit;
    }
}
