<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SocialAuthRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'google_client_id' => ['required_if:google_status,on','max:255'],
            'google_secret_id' => ['required_if:google_status,on','max:255'],
            'envato_client_id' => ['required_if:envato_status,on','max:255'],
            'envato_secret_id' => ['required_if:envato_status,on','max:255'],
            'microsoft_app_id' => ['required_if:microsoft_status,on','max:255'],
            'microsoft_secret_id' => ['required_if:microsoft_status,on','max:255'],

        ];
    }
}
