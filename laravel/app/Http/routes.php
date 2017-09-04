<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

//APP项目注册接口
Route::any('reg/send-sns', 'RegController@send_sns');  //基于短信注册的发短信
Route::any('reg/register', 'RegController@register');  //注册验证和完成注册
##########登录###########
Route::any('reg/login', 'RegController@login');  //用户登录
Route::any('reg/send-sms', 'RegController@send_sms');  //忘记密码之发送短信验证码
Route::any('reg/out-pwd', 'RegController@out_pwd');  //忘记密码之修改密码
##########用户设置#########
Route::any('my/userinfo', 'MyController@userinfo');  //用户设置之用户信息展示
Route::any('my/save-tel', 'MyController@save_tel');  //用户设置之修改绑定手机
Route::any('my/save-pwd', 'MyController@save_pwd');  //用户设置之修改登录密码
Route::any('my/send-name', 'MyController@send_name');  //用户设置之实名认证
Route::any('my/send-bankcard', 'MyController@send_bankcard');  //用户设置之绑定银行卡
Route::any('my/bank-show', 'MyController@bank_show');  //用户设置之查看已绑定银行卡
Route::any('my/send-pay', 'MyController@send_pay');  //用户设置之设置支付密码
Route::any('my/save-pay', 'MyController@save_pay');  //用户设置之修改支付密码
Route::any('my/send-email', 'MyController@send_email');  //用户设置之发送邮件
Route::any('my/save-email', 'MyController@save_email');  //用户设置之绑定邮箱
Route::any('my/pass-email', 'MyController@pass_email');  //用户设置之修改绑定邮箱
Route::any('my/withdraw', 'MyController@withdraw');  //用户设置之提现
Route::any('my/record', 'MyController@record');  //用户设置之提现记录查看
Route::any('my/save-username', 'MyController@save_username');  //用户设置之修改昵称
Route::any('my/send-user', 'MyController@send_user');  //意见反馈
//支付宝支付处理路由
Route::get('alipay','AlipayController@Alipay');  // 发起支付请求
Route::any('notify','AlipayController@AliPayNotify'); //服务器异步通知页面路径
Route::any('return','AlipayController@AliPayReturn');  //页面跳转同步通知页面路径
