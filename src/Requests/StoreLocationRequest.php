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
            'name' => 'required|max:185'.$this->has('update') ? '' : '|unique:'.config('chuckcms-module-booker.locations.table').',name',

            'disabled_weekdays' => 'nullable|array',
            
            'start_time_monday' => 'sometimes|array',
            'end_time_monday' => 'sometimes|array',

            'start_time_tuesday' => 'sometimes|array',
            'end_time_tuesday' => 'sometimes|array',

            'start_time_wednesday' => 'sometimes|array',
            'end_time_wednesday' => 'sometimes|array',

            'start_time_thursday' => 'sometimes|array',
            'end_time_thursday' => 'sometimes|array',

            'start_time_friday' => 'sometimes|array',
            'end_time_friday' => 'sometimes|array',

            'start_time_saturday' => 'sometimes|array',
            'end_time_saturday' => 'sometimes|array',

            'start_time_sunday' => 'sometimes|array',
            'end_time_sunday' => 'sometimes|array',

            'disabled_dates' => 'nullable|string',

            'order' => 'numeric|required',

            'max_weight' => 'numeric|required',

            'services' => 'nullable|array',

            'update' => 'sometimes',
            'id' => 'required_with:update'
        ];
    }
}
