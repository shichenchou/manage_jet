<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TsaiYiHua\ECPay\Checkout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use TsaiYiHua\ECPay\Services\StringService;
use TsaiYiHua\ECPay\Collections\CheckoutResponseCollection;
use App\Services\OrderService;

class ShoppingController extends Controller
{
    public function __construct(Checkout $checkout, CheckoutResponseCollection $checkoutResponse,OrderService $OrderService)
    {
        $this->OrderService = $OrderService;
        $this->checkout = $checkout;
        $this->checkoutResponse = $checkoutResponse;
    }
    public function infomationCheck(Request $request)
    {

        $post = $request->post();
        $order_id = isset($post['id']) ? (int)$post['id']:0;
        if($order_id  === 0) {
            return ;
        }

        $get_data = $this->OrderService->getRowForView($order_id);

        $status = isset($get_data['status']) ? $get_data['status']:'';
        if($status != 'submitted'){
            return;
        }

        $basic_price = isset($get_data['basic_price']) ? (int)$get_data['basic_price']:0;
        $item_price = isset($get_data['item_price']) ? (int)$get_data['item_price']:0;

        $total = $basic_price + $item_price;

        Session::put('order_id', $order_id);
        $user = Auth::user();
        //第三方支付
        $formData = [
            'UserId' =>  Auth::id(), // 用戶ID , Optional
            'ItemDescription' => '客製商品及設計費',
            'ItemName' => 'SSuyuss商品費用',
            'OrderId' => random_int(1, 9999999999999999),
            'TotalAmount' => $total,
            'PaymentMethod' => 'Credit', // ALL, Credit, ATM, WebATM
        ];


        return $this->checkout->setNotifyUrl(route('notify'))->setReturnUrl(route('return'))->setPostData($formData)->send();
    }
    public function notifyUrl(Request $request)
    {
        $serverPost = $request->post();
        $checkMacValue = $request->post('CheckMacValue');
        unset($serverPost['CheckMacValue']);
        $checkCode = StringService::checkMacValueGenerator($serverPost);
        if ($checkMacValue == $checkCode) {
            return '1|OK';
        } else {
            return '0|FAIL';
        }
    }

    public function returnUrl(Request $request)
    {
        $serverPost = $request->post();
        $checkMacValue = $request->post('CheckMacValue');
        unset($serverPost['CheckMacValue']);
        $checkCode = StringService::checkMacValueGenerator($serverPost);
        if ($checkMacValue == $checkCode) {
            if (!empty($request->input('redirect'))) {
                return redirect($request->input('redirect'));
            } else {
                $order_id = Session::get('order_id');
                //付款完成，下面接下來要將購物車訂單狀態改為已付款
                $this->OrderService->changeStatusToSuccess($order_id);
                //目前是顯示所有資料將其DD出來
                //dd($this->checkoutResponse->collectResponse($serverPost));
                return redirect($request->input('redirect'));
            }
        }
    }
}
