<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Csv;
use App\Models\CsvRequest;
use Illuminate\Http\Request;

class CsvController extends Controller
{
    public function upload(CsvRequest $request)
    {
        $originalFile = $request->file('file');
        $csv = Csv::create([
            'tag' => $request['tag'],
            'filename' => $originalFile->getClientOriginalName(),
            'user_id' => auth()->id(),
        ]);
        $file = $csv->addMediaFromRequest('file')
            ->toMediaCollection('imports');
        $filename = storage_path('app\\public\\'.$file->id.'\\'.$file->file_name);
        $skip = TRUE;
        $row = 0;
        if (($handle = fopen($filename, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                if ($skip == FALSE) {
                    $row++;
                    $company = Company::create([
                        'name' => $data[7],
                        'email' => $data[9],
                        'phone' => $data[8],
                        'address' => $data[10]
                    ]);
                    $contact = Contact::create([
                        'name' => $data[3],
                        'email' => $data[5],
                        'phone' => $data[4],
                        'address' => $data[6],
                        'company_id' => $company->id
                    ]);
                    Application::create([
                        'contact_id' => $contact->id,
                        'csv_id' => $csv->id,
                        'sum' => floatval($data[2]),
                        'object_type' => $data[0],
                        'lease_term' => $data[1],
                    ]);
                }
                $skip = FALSE;
            }
            fclose($handle);
        }
        $response = [
            'status' => 'success',
            'rows_count' => $row,
            'csv' => $csv,
        ];

        return response($response, 201);
    }

    public function delete(Request $request)
    {
        $fields = $request->validate([
            'tag' => 'required|string',
        ]);
        Csv::where('tag', $fields['tag'])->delete();
        return response('Deleted successfully');
    }
}
