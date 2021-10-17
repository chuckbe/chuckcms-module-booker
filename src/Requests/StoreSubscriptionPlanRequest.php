<?php

namespace Chuckbe\ChuckcmsModuleBooker\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubscriptionPlanRequest extends FormRequest
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
            'type' => 'required|in:one-off,weekly,monthly,quarterly,yearly',
            'is_active' => 'required|in:0,1',
            'name' => 'required',

            'months_valid' => 'numeric|required',
            'days_valid' => 'numeric|required',

            'usage_per_day' => 'numeric|required',

            'weight' => 'numeric|required',
            'price' => 'required|min:0',

            'disabled_weekdays' => 'nullable|array',
            'disabled_dates' => 'nullable|string',

            'order' => 'numeric|required'
        ];
    }
}
