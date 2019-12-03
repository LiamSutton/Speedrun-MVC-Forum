<?php

class ReCaptcha
{
    static $NOT_COMPLETED = "ReCaptcha Must Be Completed";
    static $FAILED = "ReCaptcha Failed";
    public function __construct()
    {

    }



    public static function getReCaptchaResult($response)
    {
        if (strlen($response) == 0)
        {
            return false;
        }
    
        $result = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6Lc0psUUAAAAAOuaEeY6Q4mEEzPwOaZnPKz2FUbG&response=".$response);
        $resultData = json_decode($result, true);

        return $resultData['success'];
    }
}