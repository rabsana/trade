<?php

namespace Rabsana\Trade\Contracts\Abstracts;

use Exception;
use Illuminate\Support\Facades\Log;
use Rabsana\Trade\Contracts\Interfaces\HandleException as HandleExceptionInterface;

abstract class HandleException implements HandleExceptionInterface
{
    public int $status = 400;
    public bool $success = false;
    public string $message = '';
    public array $errors = [];
    public array $data = [];

    public function response()
    {
        return response()->json([
            'status'                => $this->status,
            'success'               => $this->success,
            'message'               => $this->message,
            'errors'                => $this->errors,
            'data'                  => $this->data,
        ], $this->status);
    }

    public function report(Exception $e): HandleException
    {
        Log::error("rabsana-trade-error :" . $e);

        return $this;
    }

    public function setStatus(int $status): HandleException
    {
        $this->status = $status;
        return $this;
    }

    public function setSuccess(bool $success): HandleException
    {
        $this->success = $success;
        return $this;
    }

    public function setMessage(string $message): HandleException
    {
        $this->message = $message;
        return $this;
    }

    public function setErrors(array $errors): HandleException
    {
        $this->errors = $errors;
        return $this;
    }

    public function setData(array $data): HandleException
    {
        $this->data = $data;
        return $this;
    }
}
