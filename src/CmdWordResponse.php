<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace Fresns\CmdWordManager;

use Fresns\CmdWordManager\Exceptions\FresnsCmdWordException;
use Illuminate\Support\Arr;

class CmdWordResponse
{
    protected $body;

    public function __construct(array $data)
    {
        $this->body = $data;
    }

    //make
    public static function make(array $data): static
    {
        return new static($data);
    }

    //create
    public static function create(array $data): static
    {
        return static::make($data);
    }

    //get json code
    public function getCode(): ?int
    {
        return $this->getOrigin('code');
    }

    //get json message
    public function getMessage(): string
    {
        return $this->getOrigin('message');
    }

    //get json data(all)
    public function getData(?string $segment = null, $default = null)
    {
        $key = $segment ? "data.$segment" : 'data';

        return Arr::get($this->body, $key, $default);
    }

    //get json(all)
    public function getOrigin(?string $segment = null, $default = null)
    {
        $key = $segment ?: '';

        if (empty($key)) {
            return $this->body;
        }

        return Arr::get($this->body, $segment, $default);
    }

    //Determine if the request is true
    public function isSuccessResponse(): bool
    {
        return $this->getCode() === 0;
    }

    //Determine if the request is false
    public function isErrorResponse(): bool
    {
        return ! $this->isSuccessResponse();
    }

    //Get the error response
    public function getErrorResponse()
    {
        if (request()->wantsJson()) {
            return response()->json($this->toArray());
        }

        FresnsCmdWordException::throw($this->getMessage(), $this->getCode());
    }

    //to array
    public function toArray(): array
    {
        return $this->getOrigin();
    }

    //to string
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    //to object
    public function toObject(): object
    {
        return (object) $this->getOrigin();
    }

    //to string
    public function __toString(): string
    {
        return $this->toJson();
    }

    public function __call(string $method, array $args)
    {
        return $this;
    }
}
