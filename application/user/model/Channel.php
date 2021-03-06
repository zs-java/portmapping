<?php
/**
 * Created by IntelliJ IDEA.
 * User: 七友
 * Date: 2019/5/28
 * Time: 8:50
 */

namespace app\user\model;


use think\Model;

class Channel extends Model
{

    protected function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
    }

    //验证端口是否已经使用
    function checkPortUsable($host_id, $port) {
        //验证端口号是否被http占用
        $count = db('domain_port')->where('host_id', $host_id)->where('open_port', $port)->count();
        if($count > 0)
            return false;

        //验证端口号是否被tcp占用
        $count = db('channel')
            ->where('protocol', TCP_CODE)
//            ->where('enable', ENABLE)
            ->where('cus_domain', $port)
            ->count();
        if($count > 0)
            return false;
        return true;
    }

    //生成可用的端口号
    function randomUsablePort($host_id) {
        $host = Host::get($host_id);
        return $this->random($host);
    }

    //递归生成端口
    private function random($host) {
        $port = mt_rand($host['open_min_port'], $host['open_max_port']);
        if($this->checkPortUsable($host['id'], $port))
            return $port;
        else
            return $this->random($host);
    }



}