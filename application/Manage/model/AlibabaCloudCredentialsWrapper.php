<?php

namespace app\Manage\model;

use OSS\Credentials\Credentials;
use OSS\Credentials\CredentialsProvider;
use OSS\Credentials\StaticCredentialsProvider;
use think\Model;

class AlibabaCloudCredentialsWrapper extends Model implements CredentialsProvider
{
    /**
     * @var Credentials
     */
    private $wrapper;

    public function __construct($wrapper){
        $this->wrapper = $wrapper;

        parent::__construct();
    }
    public function getCredentials(): StaticCredentialsProvider
    {
        $ak = $this->wrapper->getAccessKeyId();
        $sk = $this->wrapper->getAccessKeySecret();
        $token = $this->wrapper->getSecurityToken();

        return new StaticCredentialsProvider($ak, $sk, $token);
    }
}
