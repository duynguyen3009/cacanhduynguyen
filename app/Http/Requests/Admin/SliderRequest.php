<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SliderRequest extends FormRequest
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
            'name'              => 'required',
            'href'              => 'required|url',
            'image'             => 'required|image|mimes:jpeg,jpg,png|max:10000', // max 10000kb
            'description'       => 'required',
            'status'            => 'not_in:0',
            'ordering'          => 'required|integer',
            'date_show_start'   => 'required|date',
            'date_show_end'     => 'required|date|after_or_equal:date_show_start'
        ];
    }

    public function attributes()
    {
        $attributes =  collect(require(app_path('Helpers/Form/config/slider.php')))->map(function ($item) {
            return $item['label'];
        })->toArray();

        $attributes['image'] = 'Hình ảnh';

        return $attributes;
    }

    public function messages()
    {
        return [
            'name.required'                 => __('messages.required'),
            'href.required'                 => __('messages.required'),
            'href.url'                      => __('messages.required'),
            'description.required'          => __('messages.required'),
            'status.not_in'                 => __('messages.required'),
            'ordering.required'             => __('messages.required'),
            'ordering.integer'              => __('messages.integer'),
            'date_show_start.required'      => __('messages.required'),
            'date_show_start.date'          => __('messages.date'),
            'date_show_end.required'        => __('messages.required'),
            'date_show_end.date'            => __('messages.date'),
            'date_show_end.after_or_equal'  => __('messages.after_or_equal'),
            'image.required'                => __('messages.required'),
            'image.image'                   => __('messages.image'),
            'image.mimes'                   => __('messages.image'),
            'image.max'                     => __('messages.image_max'),
        ];
    }
}
