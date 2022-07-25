<?php

namespace App\Http\Requests;

use App\Models\Resource;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreResourceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', 'string', Rule::in(Resource::getTypes())],
            'title' => ['required', 'string', 'unique:resources'],
            'description' => ['required_if:type,=,' . Resource::TYPE_HTML, 'string'],
            'html_snippet' => ['required_if:type,=,' . Resource::TYPE_HTML, 'string'],
            'link' => ['required_if:type,=,' . Resource::TYPE_LINK, 'string', 'url',],
            'link_target' => ['sometimes', 'string', Rule::in(['_parent', '_blank'])],
            'file' => [
                'required_if:type,=,' . Resource::TYPE_PDF,
                'file',
                'max:3068',
                'mimes:pdf',
            ]
        ];
    }
}
