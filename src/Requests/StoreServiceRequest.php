<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
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
            'type' => 'required',
            'name' => 'required',
            'duration' => 'numeric|required',
            'min_duration' => 'nullable|numeric',
            'max_duration' => 'nullable|numeric',
            'price' => 'nullable',
            'deposit' => 'nullable'
        ];
    }
}
