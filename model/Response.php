<?php
//8tgc7z81

/**
 * Class Response
 */
require_once __DIR__ . '/../controller/ajax.php';

class Response
{
    /**
     * @var bool
     */
    private bool $_success;
    /**
     * @var array
     */
    private array $_messages = array();
    /**
     * @var
     */
    private $_data;
    /**
     * @var int
     */
    private int $_httpStatusCode = 100;
    /**
     * @var bool
     */
    private bool $_toCache = false;
    /**
     * @var array
     */
    private array $_responseData = array();
    /**
     * @var array
     */
    private array $_appData = array();
    /**
     * @var array
     */
    private array $_control_params = array();
    /**
     * @var int
     */
    private int $_cache_timeout;

    private string $_requestSource = 'web';

    public bool $appendServerInfo=false;

    /**
     * @param bool $success
     */
    public function setSuccess(bool $success)
    {
        $this->_success = $success;
    }

    /**
     * @param $message
     */
    public function addMessage($message)
    {
        $this->_messages[] = $message;
    }

    /**
     * @param $data
     */
    public function setData($data)
    {
        $this->_data = $data;
    }


    public function setAppData($data)
    {
        $this->_requestSource = 'app';
        $this->_appData = $data;
    }

    /**
     * @param $httpStatusCode
     */
    public function setHttpStatusCode($httpStatusCode)
    {
        $this->_httpStatusCode = $httpStatusCode;
    }

    /**
     * @param bool $toCache
     * @param int $timeout
     */
    public function toCache(bool $toCache = true, int $timeout = 60): void
    {
        //enable cache by default for 60secs
        $this->_toCache = $toCache;
        $this->_cache_timeout = $timeout;
    }

 

    /**
     *
     */
    public
    function send()
    {
        header('Content-type:application/json;charset=utf-8');

        $_serverLocation = '1111,2222';
        if ($this->_toCache == true && $this->_success) {
            //cache only successful requests
            header('Cache-Control', 'private');
            header('Cache-Control', "must-revalidate");
            header('Cache-Control: max-age=' . $this->_cache_timeout);
        } else {
            header('Cache-Control: no-cache, no-store');
        }


        if (!is_numeric($this->_httpStatusCode) || ($this->_success !== false && $this->_success !== true)) {
            http_response_code(500);
            $this->_responseData['statusCode'] = 500;
            $this->_responseData['success'] = false;
            $this->_messages[] = 'Somethin\' Went  Wrong ';
            $this->_responseData['messages'] = $this->_messages;
            if ($this->_requestSource === 'app') {
                $this->_responseData['appData'] = [];
            }
        } else {

            $this->_responseData['control_params'] = $this->_control_params;
            http_response_code($this->_httpStatusCode);
            $this->_responseData['statusCode'] = $this->_httpStatusCode;
            $this->_responseData['success'] = $this->_success;
            $this->_responseData['messages'] = $this->_messages;
            $this->_responseData['data'] = $this->_data;
            if ($this->_requestSource === 'app') {
                $this->_responseData['appData'] = $this->_appData;;
            }

        }

      if($this->appendServerInfo)  $this->_responseData['server'] = [
            'location' => $_serverLocation,
            'status' => 'Good',
            'success' => true
        ];
        echo json_encode($this->_responseData);
    }

}