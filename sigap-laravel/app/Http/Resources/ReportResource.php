<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'status'      => $this->status,
            'category'    => $this->category?->name,
            'category_id' => $this->category_id,
            'photo_url'   => $this->photo_path
                            ? asset('storage/' . $this->photo_path)
                            : null,
            'location'    => $this->location_address,
            'latitude'    => $this->latitude,
            'longitude'   => $this->longitude,
            'urgency'     => $this->urgency,
            'created_at'  => $this->created_at?->format('d M Y'),
            'user'        => [
                'id'   => $this->user?->id,
                'name' => $this->user?->name,
            ],
            'status_logs' => $this->whenLoaded('statusLogs', function () {
                return $this->statusLogs->map(fn($log) => [
                    'status'     => $log->status,
                    'notes'      => $log->notes,
                    'changed_by' => $log->changedBy?->name,
                    'changed_at' => $log->created_at?->format('d M Y H:i'),
                ]);
            }),
        ];
    }
}
