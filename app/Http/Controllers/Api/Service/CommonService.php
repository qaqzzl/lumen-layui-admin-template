<?php
namespace App\Http\Controllers\Api\Service;
use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use App\Exceptions\InternalException;
use App\Models\SmsVerifyCode;

class CommonService {

    /**
     * 发送短信验证码
     * Download：https://github.com/aliyun/openapi-sdk-php
     * Usage：https://github.com/aliyun/openapi-sdk-php/blob/master/README.md
     * @param string $phone
     * @param string $verify_code_class
     * @throws \AlibabaCloud\Client\Exception\ClientException
     * @throws \AlibabaCloud\Client\Exception\ServerException
     */
    public function SendSms(string $phone, string $verify_code_class)
    {
        AlibabaCloud::accessKeyClient(config('config.alibaba.sms.accessKeyId'), config('config.alibaba.sms.accessSecret'))
            ->regionId('cn-hangzhou')
            ->asDefaultClient();

        $code = mt_rand(1000,9999);
        $data = array(
            'phone'=>$phone,
            'verify_code'=>$code,
            'verify_type'=>$verify_code_class,
            'created_at'=>time(),
        );
        if (!SmsVerifyCode::create($data)) {
            throw new InternalException();
        }

        $query = [
            'RegionId' => "cn-hangzhou",
            'PhoneNumbers' => $phone,
            'SignName' => config('config.alibaba.sms.SignName'),
        ];
        switch ($verify_code_class) {
            case 'login':       // 登录
                $query['TemplateCode'] = 'SMS_181196339';
                $query['TemplateParam'] = $code;
                break;
            case 'retrieve_password':       // 找回密码
                break;
            case 'bind_phone':       // 绑定手机号
                break;
        }

        try {
            $result = AlibabaCloud::rpc()
                ->product('Dysmsapi')
                // ->scheme('https') // https | http
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->host('dysmsapi.aliyuncs.com')
                ->options([
                    'query' => $query,
                ])
                ->request();
            print_r($result->toArray());
        } catch (ClientException $e) {
//            echo $e->getErrorMessage() . PHP_EOL;
            throw new InternalException(5000, $e->getErrorMessage());
        } catch (ServerException $e) {
//            echo $e->getErrorMessage() . PHP_EOL;
            throw new InternalException(5000, $e->getErrorMessage());
        }
    }
}