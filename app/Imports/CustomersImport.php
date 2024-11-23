<?php

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Log;

class CustomersImport implements ToModel, WithHeadingRow, WithValidation
{
    protected $errors = [];

    /**
     * Process each row of the file and attempt to import customer data.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */


    public function model(array $row)
    {
        $name = $row['name']; 
        $email = $row['email'];
        $phone = $row['phone'];

        try {
            return new Customer([
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'created_by' => auth()->id(), 
            ]);
        } catch (\Exception $e) {
            Log::error('Error importing customer: ' . $e->getMessage(), [
                'row' => $row,
            ]);
            $this->errors[] = [
                'row' => $row,
                'message' => 'Error importing row: ' . $e->getMessage()
            ];
        }

        return null; 
    }

    /**
     * Define the validation rules for the import.
     *
     * @return array
     */

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'required|max:15|unique:customers,phone',
        ];
    }

    /**
     * Get the list of validation errors, including row numbers.
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    
    public function onFailure(\Illuminate\Validation\ValidationException $e)
    {
        $failures = $e->errors();

        foreach ($failures as $rowIndex => $errors) {
            $this->errors[] = [
                'row' => $rowIndex + 2,
                'message' => implode(', ', $errors),
            ];
        }
    }
}
