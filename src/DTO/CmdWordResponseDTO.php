<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace Fresns\CmdWordManager\DTO;

use Fresns\DTO\DTO;

class CmdWordResponseDTO extends DTO
{
    public function rules(): array
    {
        return [
            'code' => 'required',
            'message' => 'required',
            'data' => 'nullable',
        ];
    }
}
