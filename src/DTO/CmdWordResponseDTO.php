<?php

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
