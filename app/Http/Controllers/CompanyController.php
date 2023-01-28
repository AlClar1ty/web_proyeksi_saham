<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Companies;
use App\Prices;

class CompanyController extends Controller
{
    public function fetchingData(){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://api.goapi.id/v1/stock/idx/companies');
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
    		$result = json_decode($result);
        	$diffCount = $result->data->count - Companies::count();
        	if($diffCount > 0){
		        $arrCompanies = $result->data->results;

		        for ($i=0; $i < $diffCount ; $i++) {
		        	$company = new Companies;
		        	$company->ticker = $arrCompanies[Companies::count()]->ticker;
		        	$company->name = $arrCompanies[Companies::count()]->name;
		        	$company->logo = $arrCompanies[Companies::count()]->logo;
		        	$company->save();

		        }
        	}
        }
        else{
        	dd($err);
        }
    }

    public function index(Request $request){
    	$url = $request->all();

    	if($request->has('search')){
	    	$companies = Companies::where('name', 'LIKE', '%'.$request->search.'%')->paginate(10);
    	}
    	else{
	    	$companies = Companies::paginate(10);
    	}

        return view('company', compact('companies', 'url'));
    }

    public function show(Companies $company){
    	if(count($company->price) > 0){
    		if(!$company['watching']){
    			$priceNya = $company->price->last();
	    		if(strtotime($priceNya['updated_at']) < strtotime("-30 minutes")){
	    			$curl = curl_init();
			        curl_setopt($curl, CURLOPT_URL, 'https://api.goapi.id/v1/stock/idx/'. $company['ticker']);
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
			    		$result = json_decode($result);
		    			$priceNya->open = $result->data->last_price->open;
						$priceNya->high = $result->data->last_price->high;
						$priceNya->low = $result->data->last_price->low;
						$priceNya->close = $result->data->last_price->close;
						$priceNya->volume = $result->data->last_price->volume;
						$priceNya->save();
			        }
			        else{
			        	dd($err);
			        }
	    		}
    		}
    	}
    	else{
    		if($company){
    			$curl = curl_init();
		        curl_setopt($curl, CURLOPT_URL, 'https://api.goapi.id/v1/stock/idx/'. $company['ticker']);
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
		    		$result = json_decode($result);
		    		if($company->description == null){
			    		$company->description = $result->data->result->description;
				        $company->email = $result->data->result->email;
				        $company->sector = $result->data->result->sector;
				        $company->phone = $result->data->result->phone;
				        $company->address = $result->data->result->address;
				        $company->website = $result->data->result->website;
				        $company->save();
		    		}

		    		$priceNya = new Prices();
	    			$priceNya->open = $result->data->last_price->open;
					$priceNya->high = $result->data->last_price->high;
					$priceNya->low = $result->data->last_price->low;
					$priceNya->close = $result->data->last_price->close;
					$priceNya->volume = $result->data->last_price->volume;
					$priceNya->company_id = $company->id;
					$priceNya->save();
		        }
		        else{
		        	dd($err);
		        }
    		}
    		else{
    			dd("err");
    		}
    	}
        return view('detail', compact('company'));
    }
}
