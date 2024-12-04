<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IncomeExpanseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=>$this->id,
            "qiymati"=>$this->value,
            "valyuta_turi"=>$this->currency,
            "kategoriyasi"=>$this->title,
            "nomi_izohi"=>$this->comment,
            "yaratilgan_sana"=>$this->created_at->format('y.m.d'),
            "ozgartirilgan_sana"=>$this->updated_at->format('y.m.d'),
            "foydalanuvchi_ismi"=>$this->full_name,
            "turi"=>$this->is_input,
        ];
    }
}

/*

 return [
            "id"=>$this->id,
            "title"=> $this->title,
            "user"=>$this->user->full_name,
            "kirim/chiqim"=> $this->is_input,
            "active"=> $this->active,
        ];

        */
