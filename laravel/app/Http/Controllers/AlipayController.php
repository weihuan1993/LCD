<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class AlipayController extends Controller {

// 发起支付请求
    public function Alipay(){
        $alipay = app('alipay.web');
        $alipay->setOutTradeNo('lcb'.rand('10000000','99999999'));
        $alipay->setTotalFee('0.01');
        $alipay->setSubject('理财宝用户操作');
        $alipay->setBody('用户账户充值');
        $alipay->setQrPayMode('5'); //该设置为可选1-5，添加该参数设置，支持二维码支付。

        // 跳转到支付页面。
        return redirect()->to($alipay->getPayLink());
    }

// 异步通知支付结果
    public function AliPayNotify(Request $request){
// 验证请求。
        if (!app('alipay.web')->verify()) {
            Log::notice('Alipay notify post data verification fail.', [
                'data' => $request->instance()->getContent()
            ]);
            return 'fail';
        }
// 判断通知类型。
        switch ($request ->input('trade_status','')) {
            case 'TRADE_SUCCESS':
            case 'TRADE_FINISHED':
                // TODO: 支付成功，取得订单号进行其它相关操作。
                Log::debug('Alipay notify post data verification success.', [
                    'out_trade_no' => $request -> input('out_trade_no',''),
                    'trade_no' => $request -> input('trade_no','')
                ]);
                break;
        }
        return 'success';
    }

// 同步通知支付结果
    public function AliPayReturn(Request $request){
// 验证请求。
        if (!app('alipay.web')->verify()) {
            Log::notice('支付宝返回查询数据验证失败。', [
                'data' => $request->getQueryString()
            ]);
            return view('alipayfail');
        }
// 判断通知类型。
        switch ($request ->input('trade_status','')) {
            case 'TRADE_SUCCESS':
            case 'TRADE_FINISHED':
                // TODO: 支付成功，取得订单号进行其它相关操作。
                Log::debug('支付宝通知获得数据验证成功。', [
                    'out_trade_no' => $request ->input('out_trade_no',''),
                    'trade_no' => $request -> input('trade_no','')
                ]);
                break;
        }
        echo "<script>alert('支付成功');location.href='http://www.appbao.com/'</script>";
    }
}