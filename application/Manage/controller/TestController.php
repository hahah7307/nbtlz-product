<?php
namespace app\Manage\controller;

use AlibabaCloud\Credentials\Credential;
use app\Manage\model\AlibabaCloudCredentialsWrapper;
use OSS\Core\OssException;
use OSS\OssClient;
use think\exception\DbException;

class TestController extends BaseController
{
    /**
     * @throws \ReflectionException
     */
    public function index()
    {
        $ramRoleArn  = new Credential(array(
            'type'              => 'ram_role_arn', // 填写Credential类型，固定值为ram_role_arn。
            'access_key_id'     => 'LTAI5tM41X7if1VTHvrHnSov', // 从环境变量中获取RAM用户的访问密钥（AccessKey ID和AccessKey Secret）。
            'access_key_secret' => 'CGPU3yAaLh8L7kZHWhWtxF0I7QE2Zg',
            'role_arn'          => 'acs:ram::1509702461744485:role/oss', // 从环境变量中获取RAM角色的RamRoleArn。即需要扮演的角色ID，格式为acs:ram::$accountID:role/$roleName。
            'role_session_name' => 'nbtlz', // 自定义角色会话名称，用于区分不同的令牌。
            'policy'            => '', // 自定义权限策略。
        ));
        // 使用环境变量中获取的RAM用户的访问密钥和RAM角色的RamRoleArn配置访问凭证。
        $providerWarpper = new AlibabaCloudCredentialsWrapper($ramRoleArn);
        $provider = $providerWarpper->getCredentials();
        $config = array(
            'provider' => $provider,
            // 以华东1（杭州）为例，填写为https://oss-cn-hangzhou.aliyuncs.com。其他Region请按实际情况填写。
            'endpoint'=> 'https://oss-cn-hangzhou.aliyuncs.com'
        );
        try {
//            // 创建bucket
            $accessKeyId = 'LTAI5tM41X7if1VTHvrHnSov';
            $accessKeySecret = 'CGPU3yAaLh8L7kZHWhWtxF0I7QE2Zg';
            $endpoint = 'https://oss-cn-hangzhou.aliyuncs.com';
            $bucket = "tlz-product";
//
//            $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
//            // 设置Bucket的存储类型为低频访问类型，默认是标准类型。
//            $options = array(
//                OssClient::OSS_STORAGE => OssClient::OSS_STORAGE_IA
//            );
//            // 设置Bucket的读写权限为公共读，默认是私有读写。
//            $res = $ossClient->createBucket($bucket, OssClient::OSS_ACL_TYPE_PUBLIC_READ, $options);
//            dump($res);

//            // 文件上传
//
//            // 填写Object完整路径，例如exampledir/exampleobject.txt。Object完整路径中不能包含Bucket名称。
            $object = "huangjie/test2.jpg";
//            $filePath = "D:\\文档\桌面\\项目文件\\产品数据\\BBK001WH-印第安帐篷\\原图\\9W3A7307.JPG";
//            $options = array(
//                OssClient::OSS_CONTENT_TYPE => 'image/jpg',
//            );
//            try{
//                $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
//
//                $res = $ossClient->uploadFile($bucket, $object, $filePath, $options);
//                dump($res);
//            } catch(OssException $e) {
//                printf(__FUNCTION__ . ": FAILED\n");
//                printf($e->getMessage() . "\n");
//                return;
//            }

            try {

                $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);

                // 生成一个带签名的URL，有效期是3600秒，可以直接使用浏览器访问。
                $timeout = 3600;

                // $options 可以参考https://www.alibabacloud.com/help/zh/doc-detail/47735.htm?spm=a2c63.p38356.b99.530.2b124f7cdGTn1g
                $options = array(
//                     OssClient::OSS_FILE_DOWNLOAD => $download_file,
//                     OssClient::OSS_PROCESS => "image/resize,m_fixed,h_100,w_100",
                );

                $signedUrl = $ossClient->signUrl($bucket, $object, $timeout, "GET", $options);
                dump($signedUrl);

//                print("rtmp url: \n" . $signedUrl);
            } catch (OssException $e) {
                print $e->getMessage();
            }

            print(__FUNCTION__ . "OK" . "\n");
        } catch (OssException $e) {
            printf(__FUNCTION__ . ": FAILED\n");
            printf($e->getMessage() . "\n");
            return;
        }

        exit();
    }
}
