<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index()
    {
        return view('upload-file');
    }

    public function upload(Request $request)
    {
        if ($request->has('mycsv')) {

            $data = file($request->mycsv);

            // chunking file
            $chunks = array_chunk($data, 1000);

            foreach ($chunks as $key => $chunk) {
                $name = "/temp{$key}.csv";
                $path = resource_path('temp');
                file_put_contents($path . $name, $chunk);
            }

            return 'Upload done';
        }

        return 'File not found';
    }

    public function store()
    {
        $path = resource_path('temp');
        $files = glob("$path/*.csv");

        $header = [];
        foreach ($files as $key => $file) {
            $data = array_map('str_getcsv', file($file));

            if ($key === 0) {
                $header = $data[0];
                unset($data[0]);
            }

            foreach ($data as $sale) {
                $sale_data = array_combine($header, $sale);
                Sale::create($sale_data);
            }
        }

        return 'stored';
    }
}
