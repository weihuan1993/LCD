<?php
/**
 * Created by PhpStorm.
 * User: Shinelon
 * Date: 2017/8/31 0031
 * Time: 14:22
 */

namespace App\Http\Controllers;
use DB;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 1000');

class RegController extends Controller
{
    //注册发送短信接口
    public function send_sns(){
        if(!empty($_REQUEST['tel'])){
            $tel=$_REQUEST['tel'];
            $res=DB::table('userinfo')->where(['telephone'=>$tel])->first();
            if($res){
                echo 2;die;
            }
            $code = rand(10000,99999);
            $url="http://api.k780.com/?app=sms.send&tempid=51105&param=usernm%3Dadmin%26code%3D$code&phone=$tel&appkey=23213&sign=89d9875888695333ad91320961af34dc";
            file_get_contents($url);
            echo $code;
        }
    }

    //注册验证和完成注册
    public function register(){
        if(!empty($_REQUEST['code']) || !empty($_REQUEST['checkcode']) || !empty($_REQUEST['telephone']) || !empty($_REQUEST['pwd_ones']) || !empty($_REQUEST['pwd'])){
            $date=$_REQUEST;
            if($date['code'] == $date['checkcode'] || $date['pwd_ones'] == $date['pwd']){
                unset($date['code']);
                unset($date['checkcode']);
                unset($date['pwd_ones']);
                $date['username']='LCB'.rand(10000,99999);
                $date['pwd']=md5($date['pwd']);
                $date['create_time']=time();
                $date['login_time']=time();
                $res=DB::table('userinfo')->insertGetId($date);
                if($res){
                    $request=[
                        'msg'=>200,
                        'id'=>$res
                    ];
                    echo json_encode($request);
                }
            }else{
                $request=[
                    'msg'=>400,
                    'res'=>'短信验证码或密码不匹配'
                ];
                echo json_encode($request);
            }
        }
    }
    //登录接口
    public function login(){
        if(!empty($_REQUEST['tel']) || !empty($_REQUEST['password'])){
            $data=$_REQUEST;
            $pwd=md5($data['pwd']);
            $res=DB::table('userinfo')->where(['telephone'=>$data['tel'],'pwd'=>$pwd])->first();
            $time=time();
            DB::table('userinfo')->where(['telephone'=>$data['tel']])->update(['login_time'=>$time]);
            if($res){
                $request=[
                    'msg'=>200,
                    'id'=>$res->id
                ];
                echo json_encode($request);
            }else{
                $request=[
                    'msg'=> 400,
                    'res'=> '用户名或密码错误'
                ];
                echo json_encode($request);
            }
        }
    }
    //忘记密码发送短信
    public function send_sms(){
        if(!empty($_REQUEST['tel'])){
            $tel=$_REQUEST['tel'];
            $res=DB::table('userinfo')->where(['telephone'=>$tel])->first();
            if(!$res){
                echo 1;die;
            }else{
                $code = rand(10000,99999);
                $url="http://api.k780.com/?app=sms.send&tempid=51105&param=usernm%3Dadmin%26code%3D$code&phone=$tel&appkey=23213&sign=89d9875888695333ad91320961af34dc";
                file_get_contents($url);
                echo $code;
            }
        }
    }
    //忘记密码接口
    public function out_pwd(){
        if(!empty($_REQUEST['code']) || !empty($_REQUEST['checkcode']) || !empty($_REQUEST['telephone']) || !empty($_REQUEST['pwd_ones']) || !empty($_REQUEST['pwd'])){
            $data=$_REQUEST;
            if($data['code'] == $data['checkcode'] || $data['pwd_ones'] == $data['pwd']){
                $pwd=md5($data['pwd']);
                $result=DB::table('userinfo')->where(['telephone'=>$data['telephone']])->update(['pwd'=>$pwd]);
                if($result){
                    $request=[
                        'msg'=>200,
                        'res'=>'成功'
                    ];
                    echo json_encode($request);
                }else{
                    $request=[
                        'msg'=>400,
                        'res'=>'密码修改失败'
                    ];
                    echo json_encode($request);
                }
            }
        }
    }

}