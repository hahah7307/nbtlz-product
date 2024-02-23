<?php

namespace app\Manage\controller;

use app\Manage\model\AccountModel;
use app\Manage\model\AlibabaCloudCredentialsWrapper;
use app\Manage\model\FilesModel;
use app\Manage\model\FilesTypeModel;
use app\Manage\model\SellerSkuModel;
use app\Manage\validate\FilesValidate;
use think\Config;
use think\Controller;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\Session;

class UploadController extends Controller
{
    /**
     * @throws DbException
     * @throws ModelNotFoundException
     * @throws DataNotFoundException
     */
    public function file_upload()
    {
        header("content-type:text/html;charset=utf-8");
        if (empty($_FILES)) {
            echo json_encode(['code' => 0, 'msg' => '请先上传文件']);
            exit();
        }

        $relation = self::getFileRelation();
        $seller = AccountModel::get($relation['seller']);
        $sku = SellerSkuModel::get($relation['sku']);
        $type = FilesTypeModel::get($relation['type']);

        //设置时区
        date_default_timezone_set('PRC');
        //获取文件名
        $filename = $_FILES['file']['name'];
        //获取文件临时路径
        $temp_name = $_FILES['file']['tmp_name'];
        //获取大小
        $size = $_FILES['file']['size'];
        //获取文件上传码，0代表文件上传成功
        $error = $_FILES['file']['error'];
        if ($error) {
            echo json_encode(['code' => 0, 'msg' => '文件上传失败']);
            exit();
        }
        //判断文件大小是否超过设置的最大上传限制
        if ($size > 10 * 1024 * 1024){
            echo json_encode(['code' => 0, 'msg' => '文件大小超过10M']);
            exit();
        }
        //phpinfo函数会以数组的形式返回关于文件路径的信息 
        //[dirname]:目录路径[basename]:文件名[extension]:文件后缀名[filename]:不包含后缀的文件名
        $arr = pathinfo($filename);
        //获取文件的后缀名
        $ext_suffix = $arr['extension'];
        //设置允许上传文件的后缀
        $suffix = config('FILES_EXT');
        //判断上传的文件是否在允许的范围内（后缀）==>白名单判断
        if (!in_array($ext_suffix, $suffix)) {
            //window.history.go(-1)表示返回上一页并刷新页面
            echo json_encode(['code' => 0, 'msg' => '上传了不支持的文件类型']);
            exit();            
        }
        //检测存放上传文件的路径是否存在，如果不存在则新建目录
        if (!file_exists('upload/product')){
            mkdir('upload/product');
        }
//        //为上传的文件新起一个名字，保证更加安全
//        $default_title = date('YmdHis',time()).rand(100,1000);
//        $new_filename = $default_title.'.'.$ext_suffix;
        //将文件从临时路径移动到磁盘
        $user = AccountModel::where(['id'=>Session::get(Config::get('USER_LOGIN_FLAG')), 'status' => AccountModel::STATUS_ACTIVE])->find();
        $new_filename = $arr['filename'] . '_' . date('YmdHis') . '_' . $user['id'] . '.' . $ext_suffix;
        if (move_uploaded_file($temp_name, 'upload/product/' . $new_filename)){
            $aliyunUpload = AlibabaCloudCredentialsWrapper::uploadFile('upload/product/' . $new_filename, '2024/' . $seller['username'] . '/' . $sku['product_sku'] . '/' . $type['name'] . '/' . $new_filename);
            if ($aliyunUpload['code'] == 1) {
                $filesData = [
                    'seller_sku_id' =>  $sku['id'],
                    'file_type'     =>  $type['id'],
                    'file_url'      =>  $aliyunUpload['url'],
                    'file_path'     =>  '2024/' . $seller['username'] . '/' . $sku['product_sku'] . '/' . $type['name'] . '/' . $new_filename,
                    'file_name'     =>  $filename,
                    'seller_id'     =>  $seller['id'],
                    'painter_id'    =>  $user['id']
                ];
                $dataValidate = new FilesValidate();
                if ($dataValidate->scene('add')->check($filesData)) {
                    if (is_file('upload/product/' . $new_filename)) {
                        unlink('upload/product/' . $new_filename);
                    }
                    $model = new FilesModel();
                    if ($model->allowField(true)->save($filesData)) {
                        echo json_encode(['code' => 1, 'msg' => '上传成功']);
                    } else {
                        echo json_encode(['code' => 0, 'msg' => '上传失败，请重试']);
                    }
                } else {
                    echo json_encode(['code' => 0, 'msg' => $dataValidate->getError()]);
                }
            } else {
                echo json_encode(['code' => 0, 'msg' => '上传失败']);
            }
        }else{
            echo json_encode(['code' => 0, 'msg' => '文件上传失败']);
        }
        exit;
    }

    /**
     * @throws DbException
     */
    static public function getFileRelation(): array
    {
        $arr = explode('/', $_SERVER['HTTP_REFERER']);
        $typeFormat = array_reverse($arr)['0'];
        $typeId = explode('.', $typeFormat)[0];
        $skuId = array_reverse($arr)[2];
        $sku = SellerSkuModel::get($skuId);
        return [
            'type'      =>  $typeId,
            'sku'       =>  $skuId,
            'seller'    =>  $sku['seller_id']
        ];
    }
}
