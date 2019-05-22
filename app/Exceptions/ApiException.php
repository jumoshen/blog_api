<?php

namespace App\Exceptions;

use Exception;

class ApiException extends Exception
{
    /**
     * @var int
     */
    protected $statusCode = 400;

    /**
     * @var string
     */
    protected $message = '未知错误，请稍后重试';

    public function __construct($message = '', $statusCode = false, Exception $previous = null)
    {
        if (!$message) {
            $message = $this->message;
        }

        if (!$statusCode) {
            $statusCode = $this->statusCode;
        }

        parent::__construct($message, $statusCode, $previous);
    }

    /**
     * 报告异常
     *
     * @return void
     */
    public function report()
    {
        logger()->error(__CLASS__ . ':' . $this->getMessage() . ' at ' . $this->getFile() . ':' . $this->getLine() . ' trace:' . $this->getTraceAsString());
    }

    /**
     * 将异常渲染到 HTTP 响应中。
     *
     * @param  \Illuminate\Http\Request
     * @return mixed
     */
    public function render($request)
    {
        return response()->errorInternal($this->getMessage(), $this->getCode() >= 400 ? $this->getCode() : 500);
    }
}
