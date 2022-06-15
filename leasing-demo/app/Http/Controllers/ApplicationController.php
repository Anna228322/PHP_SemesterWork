<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Csv;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function get_by_tag(Request $request)
    {
        $fields = $request->validate([
            'tag' => 'required|string',
        ]);

        $csv = Csv::where('tag', $fields['tag'])->first();
        $apps = Application::where('csv_id', $csv->id)->get();
        $response = [
            'applications' => [],
            'count' => count($apps),
        ];
        foreach ($apps as $app)
        {
            $contact = Contact::where('id', $app->contact_id)->first();
            $company = Company::where('id', $app->company_id)->first();
            $contact['company'] = $company;
            $app['contact'] = $contact;
            $app['csv'] = $csv;
            array_push($response['applications'], $app);
        }

        return response($response);
    }
}
