<?php

namespace App\Jobs;

use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class SalesCsvProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;
    public $header;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data, $header)
    {
        $this->data = $data;
        $this->header = $header;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sale_data_array = [];

        foreach ($this->data as $sale) {
            $sale_data = array_combine($this->header, $sale);

            $sale_data = array_merge($sale_data, [
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

            $sale_data_array[] = $sale_data;
        }

        Sale::insert($sale_data_array);
    }


    /**
     * Handle a job failure.
     *
     * @param Throwable $exception
     * @return void
     */
    public function failed(Throwable $exception)
    {
        info($exception->getMessage());
        // Send user notification of failure, etc...
    }
}
