<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TypeResource extends JsonResource
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
            "title"=> $this->title,
            "user"=>$this->user->full_name,
            "kirim_chiqim"=> $this->is_input, // => "kirim_chiqim" fix
            "active"=> $this->active,
        ];
    }
}
