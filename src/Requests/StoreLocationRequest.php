<?php

namespace Chuckbe\ChuckcmsModuleBooker\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLocationRequest extends FormRequest
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
            'location_name' => 'required',
            'location_lat' => 'required',
            'location_long' => 'required',
            'location_gid' => 'nullable'
        ];
    }
}
