<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
        // $input      = $this->all();
        // $required   = 'required';
        // if (isset($input['id'])) {
        //     $required = null;
        //     if (isset($input['image'])) {
        //         $required = 'required';
        //     }
        // }
        return [
            'name'              => 'required',
            'status'            => 'not_in:0',
            'ordering'          => 'required|integer',
        ];
    }

    public function attributes()
    {
        $attributes =  collect(require(app_path('Helpers/Form/config/category.php')))->map(function ($item) {
            return $item['label'];
        })->toArray();


        return $attributes;
    }

    public function messages()
    {
        return [
            'name.required'                 => __('messages.required'),
            'status.not_in'                 => __('messages.required'),
            'ordering.required'             => __('messages.required'),
            'ordering.integer'              => __('messages.integer'),
        ];
    }
}
