<?php
namespace app\Manage\controller;

use app\Manage\model\AccountModel;
use app\Manage\model\AlibabaCloudCredentialsWrapper;
use app\Manage\model\FilesModel;
use app\Manage\model\FilesTypeModel;
use app\Manage\model\SellerSkuModel;
use app\Manage\validate\FilesValidate;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\exception\DbException;
use think\Session;
use think\Config;

class FilesController extends BaseController
{
    /**
     * @throws ModelNotFoundException
     * @throws DbException
     * @throws DataNotFoundException
     */
    public function seller(): \think\response\View
    {
        $where = ['nickname' => 'nobody'];
        $keyword = $this->request->get('keyword', '', 'htmlspecialchars');
        $this->assign('keyword', $keyword);
        if ($keyword) {
            $where['nickname'] = $keyword;
        }

        $account = new AccountModel();
        $list = $account->where($where)->select();
        $this->assign('list', $list);

        Session::set(Config::get('BACK_URL'), [], 'manage');
        return view();
    }

    /**
     * @throws DbException
     * @throws Exception
     */
    public function sku($id): \think\response\View
    {
        $where['state'] = SellerSkuModel::STATE_ACTIVE;
        if ($id != 1) {
            $where['seller_id'] = $id;
        }
        if (AccountModel::account_role() == 'Painter') {
            $where['product_sku'] = 'no_sku';
            $user = AccountModel::where(['id'=>Session::get(Config::get('USER_LOGIN_FLAG')), 'status' => AccountModel::STATUS_ACTIVE])->find();
            $where['painter_id'] = $user['id'];
        }
        $this->assign('seller_id', $id);

        $keyword = $this->request->get('keyword', '', 'htmlspecialchars');
        $this->assign('keyword', $keyword);
        if ($keyword) {
            $where['product_sku'] = ['like', '%' . $keyword . '%'];
        }

        $sellerSkuModel = new SellerSkuModel();
        $list = $sellerSkuModel->with(['seller', 'painter'])->where($where)->order('id asc')->paginate(Config::get('PAGE_NUM'));
        $this->assign('list', $list);

        $back_url = Session::get(Config::get('BACK_URL'), 'manage');
        if (AccountModel::account_role() == 'Seller') {
            $back_url = [];
        }
        if (in_array($this->request->url(), $back_url)) {
            array_pop($back_url);
        } else {
            $back_url[] = $this->request->url();
        }
        $this->assign('back_url', array_reverse($back_url)[1]);
        Session::set(Config::get('BACK_URL'), $back_url, 'manage');

        return view();
    }

    /**
     * @throws DbException
     * @throws ModelNotFoundException
     * @throws DataNotFoundException
     */
    public function type($id): \think\response\View
    {
        $typeObj = new FilesTypeModel();
        $list = $typeObj->select();
        $this->assign('list', $list);
        $this->assign('sku_id', $id);

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

    /**
     * @throws DbException
     * @throws ModelNotFoundException
     * @throws DataNotFoundException
     */
    public function index($sku, $type): \think\response\View
    {
        $where = [];
        $where['seller_sku_id'] = $sku;
        $where['file_type'] = $type;

        $skuObj = new SellerSkuModel();
        $sellerSku = $skuObj->where(['id' => $sku])->find();
        $where['seller_id'] = $sellerSku['seller_id'];

        $keyword = $this->request->get('keyword', '', 'htmlspecialchars');
        $this->assign('keyword', $keyword);
        if ($keyword) {
            $where['file_path|file_name'] = ['like', '%' . $keyword . '%'];
        }

        $filesObj = new FilesModel();
        $list = $filesObj->with(['sku', 'type', 'seller', 'painter', 'server'])->where($where)->order('id asc')->paginate(Config::get('PAGE_NUM'));
        $this->assign('list', $list);

        $back_url = Session::get(Config::get('BACK_URL'), 'manage');
        if (!in_array($this->request->url(), $back_url)) {
            $back_url[] = $this->request->url();
        }
        $this->assign('back_url', array_reverse($back_url)[1]);
        Session::set(Config::get('BACK_URL'), $back_url, 'manage');

        return view();
    }

    /**
     * @throws DbException
     * @throws ModelNotFoundException
     * @throws DataNotFoundException
     */
    public function createTmpUrl($id)
    {
        if ($this->request->isPost()) {
            $filesObj = new FilesModel();
            $file = $filesObj->find($id);
            $signUrl = AlibabaCloudCredentialsWrapper::signUrl($file['file_path']);

            $newData = [
                'file_tmp_url'      =>  $signUrl['url'],
                'file_tmp_expire'   =>  $signUrl['expire'],
            ];
            $dataValidate = new FilesValidate();
            if ($dataValidate->scene('edit')->check($newData)) {
                $model = new FilesModel();
                if ($model->allowField(true)->save($newData, ['id' => $id])) {
                    echo json_encode(['code' => 1, 'msg' => '生成成功']);
                } else {
                    echo json_encode(['code' => 0, 'msg' => '生成失败，请重试']);
                }
            } else {
                echo json_encode(['code' => 0, 'msg' => $dataValidate->getError()]);
            }
        } else {
            echo json_encode(['code' => 0, 'msg' => '操作异常！']);
            exit;
        }
    }
}
