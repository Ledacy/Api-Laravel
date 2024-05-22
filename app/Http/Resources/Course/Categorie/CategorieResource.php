<?php

namespace App\Http\Resources\Course\Categorie;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategorieResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return [
            "id" => $this ->resource -> id,
            "name" => $this ->resource -> name,
            "imagen" => $this ->resource ->imagen ?env("APP_URL")."storage/".$this ->resource ->imagen: NULL,
            "categorie_id" => $this -> resource -> categorie_id,
            "categorie" => $this -> resource ->father ?[
                "name" => $this ->resource ->father ->name,
                "imagen" => env("APP_URL")."storage/".$this ->resource ->father ->imagen,
            ] :NULL,
            "state" => $this -> resource -> state ?? 1,
        ];
    }
}
