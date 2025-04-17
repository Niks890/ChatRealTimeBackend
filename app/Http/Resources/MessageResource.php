<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'group_id'   => $this->group_id,
            'sender_id'  => $this->sender_id,
            'message'    => $this->message,
            'type'       => $this->type,
            'file_path'  => $this->file_path,
            'created_at' => $this->created_at,
            'sender'     => [
                'id'   => $this->sender->id,
                'name' => $this->sender->name,
                'avatar' => $this->sender->avatar,
                'created_at' => $this->sender->created_at,
                'updated_at' => $this->sender->updated_at
            ],
        ];
    }
}
