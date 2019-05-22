<?php

namespace App\Services;

use Illuminate\Routing\ResponseFactory;

class ResponseMacros
{
    /**
     * response macros
     *
     * @var array
     */
    protected $macros = [
        'success',
        'message',
        'error',
        'errorBadRequest',
        'errorUnauthorized',
        'errorForbidden',
        'errorNotFound',
        'errorInternal',
    ];

    /**
     * ResponseMacros construct
     *
     * ResponseMacros constructor.
     * @param ResponseFactory $factory
     */
    public function __construct(ResponseFactory $factory)
    {
        $this->factory = $factory;

        $this->bindMacros();
    }

    /**
     * bind macros
     */
    public function bindMacros()
    {
        foreach ($this->macros as $macro) {
            $this->$macro($this->factory);
        }
    }

    /**
     * success macro
     *
     * @param $factory
     */
    public function success($factory)
    {
        $factory->macro(__FUNCTION__, function ($data = null, $message = 'Success', $status = 200) use ($factory) {
            return $factory->make([
                'status' => $status,
                'message' => $message,
                'data' => $data,
            ], $status);
        });
    }

    /**
     * message macro
     *
     * @param $factory
     */
    public function message($factory)
    {
        $factory->macro(__FUNCTION__, function ($message = '', $status = 200, $data = null) use ($factory) {
            return $factory->make([
                'status' => $status,
                'message' => $message,
                'data' => $data,
            ], $status);
        });
    }

    /**
     * error macro
     *
     * @param $factory
     */
    public function error($factory)
    {
        $factory->macro(__FUNCTION__, function ($status, $message = 'Error', $data = null) use ($factory) {
            return $factory->make([
                'status' => $status,
                'message' => $message,
                'data' => $data,
            ], $status);
        });
    }

    /**
     * errorBadRequest
     *
     * @param $factory
     */
    public function errorBadRequest($factory)
    {
        $factory->macro(__FUNCTION__, function ($message = 'Bad Request', $status = 400, $data = null) use ($factory) {
            return $factory->make([
                'status' => $status,
                'message' => $message,
                'data' => $data,
            ], $status);
        });
    }

    /**
     * errorUnauthorized
     *
     * @param $factory
     */
    public function errorUnauthorized($factory)
    {
        $factory->macro(__FUNCTION__, function ($message = 'Unauthorized', $status = 401, $data = null) use ($factory) {
            return $factory->make([
                'status' => $status,
                'message' => $message,
                'data' => $data,
            ], $status);
        });
    }

    /**
     * errorForbidden
     *
     * @param $factory
     */
    public function errorForbidden($factory)
    {
        $factory->macro(__FUNCTION__, function ($message = 'Forbidden', $status = 403, $data = null) use ($factory) {
            return $factory->make([
                'status' => $status,
                'message' => $message,
                'data' => $data,
            ], $status);
        });
    }

    /**
     * errorNotFound
     *
     * @param $factory
     */
    public function errorNotFound($factory)
    {
        $factory->macro(__FUNCTION__, function ($message = 'Not Found', $status = 404, $data = null) use ($factory) {
            return $factory->make([
                'status' => $status,
                'message' => $message,
                'data' => $data,
            ], $status);
        });
    }

    /**
     * errorInternal
     *
     * @param $factory
     */
    public function errorInternal($factory)
    {
        $factory->macro(__FUNCTION__, function ($message = 'Internal Error', $status = 500, $data = null) use ($factory) {
            return $factory->make([
                'status' => $status,
                'message' => $message,
                'data' => $data,
            ], $status);
        });
    }
}