<?php

namespace App\Http\Controllers;

use App\Jobs\SalesCsvProcess;
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

            $header = [];

            foreach ($chunks as $key => $chunk) {

                /*if ($key < 2 ) {*/
                    $data = array_map('str_getcsv', $chunk);

                    if ($key === 0) {
                        $header = $data[0];
                        unset($data[0]);
                    }

                    SalesCsvProcess::dispatch($data, $header);
               /* }*/
            }

            return 'stored';
        }

        return 'File not found';
    }
}
