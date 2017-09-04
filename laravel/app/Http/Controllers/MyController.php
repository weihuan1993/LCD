<?php
/**
 * Created by PhpStorm.
 * User: Shinelon
 * Date: 2017/9/1 0001
 * Time: 10:45
 */

namespace App\Http\Controllers;
use DB;
use Mail;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 1000');
class MyController extends Controller
{
    //用户信息展示接口
    public function userinfo(){
        if(!empty($_REQUEST['user_id'])){
            $user_id=$_REQUEST['user_id'];
            $info=DB::table('userinfo')->where(['id'=>$user_id])->first();
            //手机号*匹配
            $info->telephone=substr($info->telephone, 0, 3).'****'.substr($info->telephone, 7);
            $info->idcard = substr($info->idcard, 0, 6).'*******'.substr($info->idcard, 13);
            $info->real_name = substr($info->real_name, 0, 3).'*'.substr($info->real_name, 6);
            $info->email = substr($info->email, 0, 3).'****'.substr($info->email, 7);
            //用户登录时间
            $info->login_time=date("Y-m-d H:i:s",$info->login_time);
            echo json_encode($info);
        }
    }
    //修改绑定手机
    public function save_tel(){
        if(!empty($_REQUEST['code']) || !empty($_REQUEST['checkcode']) || !empty($_REQUEST['telephone']) || !empty($_REQUEST['phones']) || !empty($_REQUEST['phon'])){
           $data=$_REQUEST;
            if($data['code'] == $data['checkcode'] || $data['phone'] == $data['phones']){
                $result=DB::table('userinfo')->where(['telephone'=>$data['telephone']])->update(['telephone'=>$data['phones']]);
                if($result){
                    $request=[
                        'msg'=>200,
                        'res'=>'成功'
                    ];
                    echo json_encode($request);
                }else{
                    $request=[
                        'msg'=>400,
                        'res'=>'绑定手机修改失败'
                    ];
                    echo json_encode($request);
                }
            }
        }
    }
    //修改登录密码
    public function save_pwd(){
        if(!empty($_REQUEST['y_pwd']) || !empty($_REQUEST['pwd']) || !empty($_REQUEST['pwds']) || !empty($_REQUEST['user_id'])){
            $data=$_REQUEST;
            if($data['pwd'] == $data['pwds']){
                $pwds=md5($data['pwds']);
                $y_pwd=md5($data['y_pwd']);
                $res=DB::table('userinfo')->where(['id'=>$data['user_id'],'pwd'=>$y_pwd])->update(['pwd'=>$pwds]);
                if($res){
                    echo 1;
                }else{
                    echo 2;
                }
            }
        }else{
            echo 3;
    }
}
    //实名认证
    public function send_name(){
        if(!empty($_REQUEST['username']) || !empty($_REQUEST['idcard']) || !empty($_REQUEST['user_id'])){
            $username=$_REQUEST['username'];
            $idcard=$_REQUEST['idcard'];
            $user_id=$_REQUEST['user_id'];
            $file = file_get_contents("http://api.k780.com/?app=idcard.get&idcard=$idcard&appkey=24055&sign=1433bba07ec0f40875f71f4051d5dc34&format=json");
            $file = json_decode($file, true);
            if ($file['success']==0){
               echo 3;die;
            }else{
                $res=DB::table('userinfo')->where(['id'=>$user_id])->update(['idcard'=>$idcard,'real_name'=>$username]);
                if($res){
                    echo 1;
                }
            }
        }else{
            echo 2;
        }
    }
    //绑定银行卡
    public function send_bankcard(){
        if(!empty($_REQUEST['back']) || !empty($_REQUEST['back_card']) || !empty($_REQUEST['user_id']) ||!empty($_REQUEST['tel'])){
            $data=$_REQUEST;
            DB::table('userinfo')->where(['id'=>$data['user_id']])->update(['bank_status'=>1]);
            $res=DB::table('back')->insert($data);
            if($res){
                echo 1;
            }else{
                echo 2;
            }
        }else{
            echo 3;
        }
    }
    //查看银行卡信息
    public function bank_show(){
        $id=$_REQUEST['user_id'];
        $res=DB::table('back')->where(['user_id'=>$id])->get();
        echo json_encode($res);
    }
    //设置支付密码
    public function send_pay(){
        if(!empty($_REQUEST['pwd']) || !empty($_REQUEST['pwds']) || !empty($_REQUEST['user_id'])){
            $data=$_REQUEST;
            if($data['pwd'] == $data['pwds']){
                $res=DB::table('userinfo')->where(['id'=>$data['user_id']])->update(['pay_code'=>$data['pwds']]);
                if($res){
                    echo 1;
                }else{
                    echo 2;
                }
            }else{
                echo 3;
            }
        }
    }
    //修改支付密码
    public function save_pay(){
        if(!empty($_REQUEST['code']) || !empty($_REQUEST['checkcode']) || !empty($_REQUEST['telephone']) || !empty($_REQUEST['phones']) || !empty($_REQUEST['phone'])){
            $data=$_REQUEST;
            if($data['code'] == $data['checkcode'] || $data['phone'] == $data['phones']){
                $result=DB::table('userinfo')->where(['telephone'=>$data['telephone']])->update(['pay_code'=>$data['phones']]);
                if($result){
                    echo 1;
                }else{
                    echo 2;
                }
            }else{
                echo 3;
            }
        }
    }
    //发送邮件
    public function send_email(){
        if(isset($_REQUEST['email'])){
            $email=$_REQUEST['email'];
            $num=rand(10000,99999);
            //发送邮件的方法！
           Mail::raw("您的验证码为：".$num, function ($message) use($email) {
                $message ->to($email)->subject('邮箱验证');
            });
           echo $num;
        }
    }
    //绑定邮箱
    public function save_email(){
        if(!empty($_REQUEST['code']) || !empty($_REQUEST['checkcode']) || !empty($_REQUEST['email']) || !empty($result['user_id'])){
            $data=$_REQUEST;
            $result=DB::table('userinfo')->where(['id'=>$data['user_id']])->update(['email'=>$data['email']]);
            if($result){
                echo 1;
            }else{
                echo 2;
            }
        }else{
            echo 3;
        }
    }
    //修改绑定邮箱
    public function pass_email(){
        if(!empty($_REQUEST['code']) || !empty($_REQUEST['checkcode']) || !empty($_REQUEST['email']) || !empty($_REQUEST['phones']) || !empty($_REQUEST['phone']) || !empty($_REQUEST['user_id'])){
            $data=$_REQUEST;
            if($data['code'] == $data['checkcode'] || $data['phone'] == $data['phones']){
                $result=DB::table('userinfo')->where(['email'=>$data['email'],'id'=>$data['user_id']])->update(['email'=>$data['phones']]);
                if($result){
                    echo 1;
                }else{
                    echo 2;
                }
            }else{
                echo 3;
            }
        }
    }
    //提现
    public function withdraw(){
        if(!empty($_REQUEST['back']) || !empty($_REQUEST['back_card']) || !empty($_REQUEST['money']) || !empty($_REQUEST['user_id'])){
            $data=$_REQUEST;
            //先查出账户余额
            $y_money=DB::table('userinfo')->where(['id'=>$data['user_id']])->first();
            $z_money=$y_money->money;
            if($data['money'] > $z_money){
                echo 2;die;
            }else{
                $new_money=$z_money-$data['money'];
                //减去账户余额的100元
                DB::table('userinfo')->where(['id'=>$data['user_id']])->update(['money'=>$new_money]);
                //在查出银行卡内的钱余额
                $back=DB::table('back')->where(['user_id'=>$data['user_id']])->first();
                $yh_money=$back->back_money;
                $z_money=$data['money']+$yh_money;
                //为该银行卡加上100元
                DB::table('back')->where(['user_id'=>$data['user_id'],'back_card'=>$data['back_card']])->update(['back_money'=>$z_money]);
                //写入提现的日志
                $info=[
                    'user_id' =>$data['user_id'],
                    'back_card' =>$data['back_card'],
                    'log' =>$data['money'],
                    'time' =>date("Y-m-d H:i:s",time())
                ];
                DB::table('back_log')->insert($info);
                echo 1;
            }
        }else{
            echo 3;
        }
    }
    //提现记录
    public function record(){
        if(isset($_REQUEST['user_id'])){
            $user_id=$_REQUEST['user_id'];
            $info=DB::table('back_log')
                ->join('userinfo','user_id','=','userinfo.id')
                ->select('back_log.*','userinfo.real_name')
                ->where(['user_id'=>$user_id])
                ->get();
            echo json_encode($info);
        }
    }
    //修改昵称
    public function save_username(){
        if(!empty($_REQUEST['username'])  || !empty($_REQUEST['user_id'])){
            $data=$_REQUEST;
            $res=DB::table('userinfo')->where(['id'=>$data['user_id']])->update(['username'=>$data['username']]);
            if($res){
                echo 1;
            }
        }else{
            echo 2;
        }
    }
    //意见反馈
    public function send_user(){
        if(!empty($_REQUEST['log'])  || !empty($_REQUEST['user_id'])){
            $data=$_REQUEST;
            $data['time']=time();
            $data['status']=1;
            $res=DB::table('user_log')->insert($data);
            if($res){
                echo 1;
            }
        }else{
            echo 2;
        }
    }
}