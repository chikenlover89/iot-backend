<?php

namespace App\Http\Resources;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeviceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id'            => $this->id,
            'account_id'    => $this->account_id,
            'name'          => $this->name,
            'description'   => $this->description,
            'type'          => $this->type,
            'last_activity' => $this->last_activity,
            'signal'        => $this->signal,
            'created_at'    => $this->created_at,
        ];

        if($this->account->creator_id === Auth::id()) {
            $data['token']       = $this->token;
            $data['location_id'] = $this->location_id;
        }

        return $data;
    }
}
