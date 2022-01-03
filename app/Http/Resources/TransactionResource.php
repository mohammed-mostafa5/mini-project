<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'category'      => $this->category->name,
            'subcategory'   => $this->subcategory->name,
            'amount'        => $this->amount,
            'status'        => $this->status,
            'payer'         => $this->payer->name,
            'due_on'        => $this->due_on,
        ];
    }
}
