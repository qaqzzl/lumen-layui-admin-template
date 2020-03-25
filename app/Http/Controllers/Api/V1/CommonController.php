<?php

use App\Http\Controllers\Api\V1\BaseController;

class CommonController extends BaseController{

    // 发送短信验证码
    public function sendSms(Request $request, CommonService $commonService)
    {
        $data = $this->validate($request,[
            'phone'=>'required',
            'verify_class'=>'required',
        ],[
            'phone.required' => '手机号不能为空',
            'verify_class.required' => '验证码类型不能为空',
        ]);
        $commonService->SendSms($data['phone'], $data['verify_class']);

        return api_success(null,'短信验证码发送成功');
    }

    // 获取七牛上传token

}