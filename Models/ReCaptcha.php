<?php

/**
 * Class ReCaptcha
 */
class ReCaptcha
{
    /**
     * @var string
     */
    static $NOT_COMPLETED = "ReCaptcha Must Be Completed";
    /**
     * @var string
     */
    static $FAILED = "ReCaptcha Failed";

    /**
     * ReCaptcha constructor.
     */
    public function __construct()
    {

    }


    /**
     * @param $response - a string that when 0 means the user didnt attempt it
     * @return bool - whether google says the person is a beep boop robot 
     */
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