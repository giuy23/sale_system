<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
          'id' => $this->id,
          'created_at' => $this->created_at->format('Y-m-d H:i'),
          'discount_total' => floatval($this->discount_total),
          'total' => floatval($this->total),
          'igv' => floatval($this->igv),
          'state' => intval($this->state),
          'user_name' => isset($this->user) ? ($this->user->name . ', ' . $this->user->surname) ?? 'USUARIO ELIMINADO' : 'USUARIO ELIMINADO',
          'client_name' => $this->client->full_name  ?? 'CLIENTE ELIMINADO',
        ];
    }
}
