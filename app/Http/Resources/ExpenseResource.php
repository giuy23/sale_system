<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
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
          'amount' => floatval($this->amount),
          'description' => $this->description,
          'type' => intval($this->type),
          'daily_cash_id' => $this->daily_cash_id,
        ];
    }
}
