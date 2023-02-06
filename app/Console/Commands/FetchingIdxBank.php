<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Companies;
use App\Prices;

class FetchingIdxBank extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'price:fetching_idx';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $dayNow = date('l');
        $hourNow = date('H');
        $minuteNow = date('i');
        if($dayNow != "Sunday" && $dayNow != "Saturday"){
            if(((int)$hourNow >= 9 && (int)$hourNow <= 11) || ((int)$hourNow >= 13 && (int)$hourNow <= 15)){
                if((int)$hourNow == 15 && (int)$minuteNow == 30){
                    echo "Not the time for fetching data.\n";
                    return false;
                }
                if((int)$hourNow == 9 && (int)$minuteNow == 0){
                    echo "Not the time for fetching data.\n";
                    return false;
                }

                $watcherCompany = Companies::where('watching', true)->get();
                $bar = $this->output->createProgressBar(count($watcherCompany));
                $bar->start();

                foreach ($watcherCompany as $companyNya) {
                    $this->fetchingData($companyNya);
                    $bar->advance();
                }

                $bar->finish();
                echo "Fetching all Data Success.\n";
            }
            else{
                echo "Not the time for fetching data.\n";
            }
        }
        else{
            echo "Not the time for fetching data.\n";
        }
    }

    function fetchingData(Companies $companyNya){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://api.goapi.id/v1/stock/idx/'. $companyNya['ticker']);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'X-API-KEY: QC3yoHwxK9yzDw3EWXoLCe4arphgMQ',
            'Content-Type: application/json'
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if($result){
            if(count($companyNya->price) > 71){
                $companyNya->price->first()->delete();
            }
            $result = json_decode($result);
            $priceNya = new Prices();
            $priceNya->open = $result->data->last_price->open;
            $priceNya->high = $result->data->last_price->high;
            $priceNya->low = $result->data->last_price->low;
            $priceNya->close = $result->data->last_price->close;
            $priceNya->volume = $result->data->last_price->volume;
            $priceNya->company_id = $companyNya->id;
            $priceNya->save();
            echo "Fetching data for ". $companyNya->ticker ." success.\n";
        }
        else{
            echo "Error: " . $err ."\n";
        }
    }
}
