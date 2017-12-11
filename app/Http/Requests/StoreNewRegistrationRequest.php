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
        /*
         * These rules validate that the form data is well-formed.
         * It is NOT responsible for the context validation of that data.
         */
        $rules = [
            // MAY be present; MUST be not-null string; MUST be unique in db
            'cc_reference' => 'string|nullable|unique:registrations',
            // MUST be present; MUST be in "yes, on, 1, or true"
            'consent' => 'required|accepted',
            // MUST be present; MUST be in listed states
            'eligibility' => 'required|in:healthy-start,other',
            // MUST be present; MUST be a not-null string
            'carer' =>  'required|string',
            // MAY be present; MUST be a distinct, non-null string
            'carers.*' => 'distinct|string',
            // MAY be present; MUST be a date format of '2017-07' per http://php.net/manual/en/datetime.formats.date.php
            'kids.*' => 'date_format:YY-mm',
        ];

        return $rules;
    }
}
