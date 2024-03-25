<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace Fresns\CmdWordManager;

use Fresns\CmdWordManager\Exceptions\ResponseException;
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

        return Arr::get($this->toArray(), $key, $default);
    }

    //get json(all)
    public function getOrigin(?string $segment = null, $default = null)
    {
        $key = $segment ?: '';

        if (empty($key)) {
            return $this->toArray();
        }

        return Arr::get($this->toArray(), $segment, $default);
    }

    //Determine if the request is true
    public function isSuccessResponse(): bool
    {
        return $this->getCode() == 0;
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
            throw new ResponseException($this->getMessage(), $this->getCode());
        }

        return $this->toArray();
    }

    //to array
    public function toArray(): array
    {
        return $this->body;
    }

    //to object
    public function toObject(): object
    {
        return (object) $this->body;
    }

    //to string
    public function toString(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    public function __call(string $method, array $args)
    {
        return $this;
    }
}
