<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class StoreNewRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Todo: replace with check that user cna make this request rather than wave them through.
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
            'cc_reference' => 'string|unique:registrations|nullable',
            'consent' => 'required|acceptance',
            'eligibility' => 'required|in:healthy-start,other',
            'carer' =>  'required|string'
        ];

        // Dynamic form arrays need loops
        // Secondary carers must be distinct if present
        foreach ($this->request->get('carers') as $k => $v) {
            $rules['carers.'.$k] = 'distinct|string';
        }

        // Children have a YY-mm format date (eg 2017-05)
        foreach ($this->request->get('dob') as $k => $v) {
            // validate dateformat is a month.
            $rules['dob'] = 'date_format:YY-mm';
        }

        return $rules;
    }
}
