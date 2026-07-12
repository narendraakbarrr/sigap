<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnnouncementResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'content'    => $this->content,
            'is_pinned'  => $this->is_pinned,
            'created_by' => $this->creator->name ?? null,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
