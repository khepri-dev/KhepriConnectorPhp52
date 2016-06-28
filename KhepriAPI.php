<?php
/**
 * API Version 1.0
 * @author greg
 *
 */

class KhepriAPI  {
    private static $version = '1.0';

    private static $flagInstance = false;
    private static $urlKhepri = false;
    private static $apiKey = false;

    private static $curlFd = false;
    private static $curlParams = array();
    // optionnal parameters
    private static $proxyHost = false;
    private static $proxyPort = false;
    private static $proxyUser = false;
    private static $proxyPasswd = false;


    /**
     *
     * @return Ambigous <boolean, Json>
     */
    private static function launchRequest() {
        $back = false;

        $chk = curl_setopt_array(self::$curlFd, self::$curlParams);
        if ($chk === false)
            trigger_error('[Error][Khepri][Curl Error - Wrong Parameters]');

        $res = curl_exec(self::$curlFd);
        if ($res === false)
            trigger_error('[Error][Khepri][Curl Exec - Error Append during call API] - '.curl_error(self::$curlFd));
        else
            $back = json_decode($res);

        return $back;
    }



    /**
     *
     * @param unknown $instanceId
     * @return Ambigous <boolean, mixed>
     */
    public static function reset($instanceId) {
        $back = false;

        $dimensionQueryString = false;

        if (self::$flagInstance != false) {

            $url = self::$urlKhepri.'/api/reset.json?instance='.$instanceId.'&api_key='.self::$apiKey;
            self::$curlParams[CURLOPT_URL] = $url;

            $back = self::launchRequest();

        } else
            trigger_error('[Error][Khepri][Khepri Not Initialized - Call init first]');

        return $back;
    }



    /**
     *
     * @param unknown $instanceId
     * @param string $dimensions
     */
    public static function dimensions($instanceId, $dimensionsAsked) {
        $back = false;
        $dimensionQueryString = false;

        if (self::$flagInstance != false) {

            $url = self::$urlKhepri.'/api/dimensions.json?instance='.$instanceId.'&api_key='.self::$apiKey.'&dimensions='.$dimensionsAsked;
            if ($dimensionQueryString != false)
                $url .= '&'.$dimensionQueryString;
            self::$curlParams[CURLOPT_URL] = $url;

            $back = self::launchRequest();

        } else
            trigger_error('[Error][Khepri][Khepri Not Initialized - Call init first]');

        return $back;
    }


    /**
     *
     * @param unknown $instanceId
     * @param unknown $solution
     * @param unknown $dimensions
     * @return Ambigous <boolean, mixed>
     */
    public static function success($instanceId, $solution, $dimensions = array()) {
        $back = false;
        $dimensionQueryString = false;

        if (self::$flagInstance != false) {

            if (is_array($dimensions) && count($dimensions) > 0)
                $dimensionQueryString = http_build_query($dimensions);

            $url = self::$urlKhepri.'/api/success.json?instance='.$instanceId.'&api_key='.self::$apiKey.'&solution='.urlencode($solution);
            if ($dimensionQueryString != false)
                $url .= '&'.$dimensionQueryString;
            self::$curlParams[CURLOPT_URL] = $url;

            $back = self::launchRequest();

        } else
            trigger_error('[Error][Khepri][Khepri Not Initialized - Call init first]');

        return $back;
    }


    /**
     *
     * @param unknown $urlKhepri
     * @param unknown $apiKey
     */
    public static function init($urlKhepri, $apiKey) {
        self::$flagInstance = true;

        self::$curlFd = curl_init();
        self::$urlKhepri = $urlKhepri;
        self::$apiKey = $apiKey;

        self::$curlParams[CURLOPT_RETURNTRANSFER] = true;

        if (self::$proxyHost != false && self::$proxyPort != false) {
            self::$curlParams[CURLOPT_PROXY] = self::$proxyHost;
            self::$curlParams[CURLOPT_PROXYPORT] = self::$proxyPort;
            if (self::$proxyUser != false && self::$proxyPasswd != false) {
                self::$curlParams[CURLOPT_PROXYUSERPWD] = self::$proxyUser.':'.self::$proxyPasswd;
                self::$curlParams[CURLOPT_PROXYAUTH] = CURLAUTH_BASIC;
            }
        }
    }



    /**
     *
     * @param unknown $instanceId
     * @param string $exclude
     * @param string $forced_solutions
     * @param unknown $dimensions
     * @return Ambigous <boolean, mixed>
     */
    public static function ask($instanceId, $excludes = array(), $forcedSolutions = array(), $dimensions = array()){
        $back = false;
        $excludeQueryString = false;
        $forcedSolutionsQueryString = false;
        $dimensionQueryString = false;

        if (self::$flagInstance != false) {

            $url = self::$urlKhepri.'/api/ask.json?instance='.$instanceId.'&api_key='.self::$apiKey;

            if (is_array($excludes) && count($excludes) > 0) {
                array_walk($excludes, function(&$arg){ $arg = urlencode($arg);} );
                $excludeQueryString = implode(',', $excludes);
                $url .= '&exclude='.$excludeQueryString;
            }

            if (is_array($forcedSolutions) && count($forcedSolutions) > 0) {
                array_walk($forcedSolutions, function(&$arg){ $arg = urlencode($arg);} );
                $forcedSolutionsQueryString = implode(',', $forcedSolutions);
                $url .= '&forced_solutions='.$forcedSolutionsQueryString;
            }

            if (is_array($dimensions) && count($dimensions) > 0)
                $dimensionQueryString = http_build_query($dimensions);

            if ($dimensionQueryString != false)
                $url .= '&'.$dimensionQueryString;

            self::$curlParams[CURLOPT_URL] = $url;

            $back = self::launchRequest();

        } else
            trigger_error('[Error][Khepri][Khepri Not Initialized - Call init first]');

        return $back;
    }


    private function __construct() {}
}