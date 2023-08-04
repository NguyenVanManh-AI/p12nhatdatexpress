<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CreateNewRequest extends FormRequest
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
        $rules = [
            'news_title' => 'required|max:255|unique:news,news_title',
            'news_description' => 'required|max:255',
            'news_tag' => 'max:255',
            'group_id' => 'required',
            'video_url' => ['nullable', 'max:255', 'regex:/^(?:https?:\/\/)?(?:m\.|www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(\?\S*)?$/'],
            'audio_url' => 'file|mimes:audio/mpeg,mpga,mp3,wav,aac',
            'image_url' => 'required|max:255',
            'news_content' => 'required|max:50000',
            // SEO
            'meta_key' => 'required|max:255',
            'meta_desc' => 'required|max:255',
            'meta_title' => 'required|max:255',
            'meta_url' => 'required|max:255|unique:news,news_url',
        ];

        if (request()->checked_express) {
            $rules['video_url'] = ['required', 'max:255', 'regex:/^(?:https?:\/\/)?(?:m\.|www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(\?\S*)?$/'];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'news_title.required' => 'Tiêu đề không được để trống',
            'news_title.max' => 'Tiêu đề tối đa 255 kí tự',
            'news_title.unique' => 'Tiêu đề đã tồn tại',
            'news_description.required' => 'Mô tả không được để trống',
            'news_description.max' => 'Mô tả tối đa 255 kí tự',
            'video_url.max' => 'Đường dẫn tối đa 255 kí tự',
            'video_url.regex' => 'Đường dẫn video youtube không hợp lệ',
            'news_tag.max' => 'Độ dài tối đa 255 kí tự',
            'group_id.required' => 'Vui lòng chọn danh mục',
            'audio_url.mimes' => 'Định dạng file không hợp lệ',
            'image_url.required' => 'Vui lòng chọn ảnh đại diện',
            'news_content.required' => 'Nội dung không được để trống',
            'video_url.required' => 'Đường dẫn video không được để trống',

            //SEO
            'meta_key.required' => 'Vui lòng nhập',
            'meta_key.max' => 'Tối đa 255 kí tự',
            'meta_desc.required' => 'Vui lòng nhập',
            'meta_desc.max' => 'Tối đa 255 kí tự',
            'meta_title.required' => 'Vui lòng nhập',
            'meta_title.max' => 'Tối đa 255 kí tự',
            'meta_url.required' => 'Vui lòng nhập',
            'meta_url.max' => 'Tối đa 255 kí tự',
            'meta_url.unique' => 'Đường dẫn đã tồn tại',
        ];
    }
}
