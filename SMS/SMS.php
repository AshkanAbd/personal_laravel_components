<?php

namespace App\Components\SMS;

use Illuminate\Validation\ValidationException;
use Kavenegar\Exceptions\ApiException;
use Kavenegar\KavenegarApi;
use Log;

class SMS
{
    /**
     * Send SMS using kavenegar send method
     * Send exact given $msg to given $receiver
     *
     * @param string $msg
     * @param string $receiver
     * @param bool $throwError
     * @return bool
     * @throws ValidationException
     */
    public static function send($msg, $receiver, $throwError = false)
    {
        if (!components('SMS_LOGIN', true)) {
            return true;
        }
        try {
            $apiKey = components('SMS_API_KEY', '616A785042765341307045304C7843697970436C4D61476F6B655373716A6A59');
            $sender = components('SMS_SENDER', '10000007707077');
            $api = new KavenegarApi($apiKey);
            $api->Send($sender, $receiver, $msg);
            return true;
        } catch (ApiException $e) {
            Log::error($e);
        } catch (\Kavenegar\Exceptions\HttpException $e) {
            Log::error($e);
        }
        if ($throwError) {
            throw new ValidationException(null, badRequestMsg('مشکل در ارسال پیامک. لطفا بعدا امتحان کنید.'));
        }
        return false;
    }

    /**
     * Send SMS using kavenegar lookup method
     * Send given $token using $template to $receiver
     *
     * @param string $template
     * @param string $receiver
     * @param string $token
     * @param bool $throwError
     * @return bool
     * @throws ValidationException
     */
    public static function lookUp($template, $receiver, $token, $throwError = false)
    {
        if (!components('SMS_LOGIN', true)) {
            return true;
        }
        try {
            $apiKey = components('SMS_API_KEY', '616A785042765341307045304C7843697970436C4D61476F6B655373716A6A59');
            $api = new KavenegarApi($apiKey);
            $api->VerifyLookup($receiver, $token, '', '', $template);
            return true;
        } catch (ApiException $e) {
            Log::error($e);
        } catch (\Kavenegar\Exceptions\HttpException $e) {
            Log::error($e);
        }
        if ($throwError) {
            throw new ValidationException(null, badRequestMsg('مشکل در ارسال پیامک. لطفا بعدا امتحان کنید.'));
        }
        return false;
    }
}