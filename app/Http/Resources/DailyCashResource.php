<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DailyCashResource extends JsonResource
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
      'start_money' => floatval($this->start_money),
      'final_money' => floatval($this->final_money ?? 0.00),
      'profit' => floatval($this->profit ?? 0.00),
      'state' => intval($this->state ?? 1),
      'user_name' => $this->user->name,
      'user_id' => $this->user_id,

      'created_at' => $this->created_at->format('Y-m-d H:i'),
      'updated_at' => $this->updated_at->format('Y-m-d H:i'),
    ];
  }
}
