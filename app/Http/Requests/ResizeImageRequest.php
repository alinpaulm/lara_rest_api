<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class ResizeImageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $regex = 'regex:/^\d+(\.\d+)?%?$/';

        $rules = [
            'image' => ['required'],
            'w' => ['required', $regex], // e.g. of possible values: 50, 50%, 50.123, 50.123%
            'h' => $regex,
            'album_id' => 'exists:\App\Models\Album,id'
        ];

        // $image = $this->post('image'); // doesn't get the uploaded image, only the string url image
        $image = $this->all()['image'] ?? false;
        // dd($image);

        if($image && $image instanceof UploadedFile) {
            $rules['image'][] = 'image';
        } else {
            $rules['image'][] = 'url';
        }

        return $rules;
    }
}
