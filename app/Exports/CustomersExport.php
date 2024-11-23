<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon; 
class CustomersExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Return the collection of customers
     */
    public function collection()
    {
        return Customer::with('user')->get(); 
    }

    /**
     * Return the headings for the columns
     */
    public function headings(): array
    {
        return [
            'Serial No',     
            'Name', 
            'Email', 
            'Phone', 
            'Created By',    
            'Created At', 
            'Updated At'
        ];
    }

    /**
     * Map the data to rows (add serial number and format data)
     */
    public function map($customer): array
    {
        static $serial = 1; // Initialize static variable to track serial numbers

        // Format the Created At and Updated At dates using Carbon
        $createdAt = Carbon::parse($customer->created_at)->format('d-m-Y');
        $updatedAt = Carbon::parse($customer->updated_at)->format('d-m-Y');

        // Check if the customer has a user (creator) and fetch the name
        $createdBy = $customer->user ? $customer->user->name : 'N/A';

        return [
            $serial++,                // Increment the serial number
            $customer->name,          // Customer Name
            $customer->email,         // Customer Email
            $customer->phone,         // Customer Phone
            $createdBy,               // Customer Creator's Name
            $createdAt,               // Formatted Created At
            $updatedAt                // Formatted Updated At
        ];
    }
}
