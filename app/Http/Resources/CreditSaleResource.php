<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CreditSaleResource extends JsonResource
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
      'amount_payable' => floatval($this->amount_payable),
      'remaining_amount' => floatval($this->remaining_amount),
      'description' => $this->description,
      'sale_id' => floatval($this->sale_id),
      'created_at' => $this->created_at->format('Y-m-d H:i'),
    ];
  }
}
