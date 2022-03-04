<?php

namespace App\Containers\User\UI\API\Backoffice\Resources;


use App\Ship\Core\Abstracts\Resources\JsonResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use JsonSerializable;

class AccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'login' => $this->login,
            'email' => $this->email,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'middle_name' => $this->middle_name,
            'phone_number' => $this->phone_number,
            'avatar' => $this->avatar,
            'birthday' => $this->birthday,
            'gender' => $this->gender,
            'properties' => $this->properties,
        ];
    }
}
