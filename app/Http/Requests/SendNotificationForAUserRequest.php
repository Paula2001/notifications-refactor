<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendNotificationForAUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'message_body' => ['string', 'required'],
            'user_id' =>  [ 'required' ,'exists:users,id']
        ];
    }
}
