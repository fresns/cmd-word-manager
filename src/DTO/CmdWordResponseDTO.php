<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the GPL-3.0 License.
 */

namespace Fresns\CmdWordManager\DTO;

use Fresns\DTO\DTO;

class CmdWordResponseDTO extends DTO
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'code' => 'required',
            'message' => 'required',
            'data' => 'nullable',
        ];
    }
}
