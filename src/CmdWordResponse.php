<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the GPL-3.0 License.
 */

namespace Fresns\CmdWordManager;

use Fresns\CmdWordManager\Exceptions\FresnsCmdWordException;
use Fresns\CmdWordManager\Supports\Collection;
use Illuminate\Http\Response;

class CmdWordResponse
{
    protected Collection $body;

    public function __construct(array $data)
    {
        $this->body = new Collection($data);
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
        return $this->body->get('code');
    }

    //get json message
    public function getMessage(): string
    {
        return $this->body->get('message');
    }

    //get json data(all)
    public function getData(?string $segment = null, $default = null)
    {
        return $this->body->get("data.$segment", $default);
    }

    //get json(all)
    public function getOrigin(?string $segment = null, $default = null)
    {
        return $this->body->get($segment, $default);
    }

    //Determine if the request is true
    public function isSuccessResponse(): bool
    {
        return (int) $this->getCode() === 0;
    }

    //Determine if the request is false
    public function isErrorResponse(): bool
    {
        return ! $this->isSuccessResponse();
    }

    //Get the error response object
    public function getErrorResponse()
    {
        if (\request()->wantsJson()) {
            return response()->json($this->toArray(), Response::HTTP_OK, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
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
