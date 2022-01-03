<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'id'                  => $this->id,
            'transaction_id'      => $this->transaction_id,
            'amount'              => $this->amount,
            'payment_method'      => $this->payment_method == 1 ? 'Cash' : 'Visa',
            'paid_on'             => $this->paid_on,
            'details'             => $this->details,
        ];
    }
}
