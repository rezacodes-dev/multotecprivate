<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use DB;

class CommoditiesApiController extends Controller
{



	public function fetchApi()
	{
		
		$api_key = env('COMMODITIES_API_KEY');

		$commodities = DB::table('commodities_pricing')->where('status', 1)->pluck('symbols')->toArray();
		// dd($commodities);
		$chunks = array_chunk($commodities, 5);


		foreach ($chunks as $chunk) {


			$symbols = implode(",", $chunk);

			$api_endpoint = "https://commodities-api.com/api/latest? access_key=$api_key&base=USD&symbols=$symbols";
			$response = Http::get($api_endpoint);
			$res_data = $response->json();
			// dd($res_data);
			$this->updateCommoditiesPricing(@$res_data['data']);
		}

		return response()->json(['message' => 'Commodities Price updated successfully']);
	}

	function updateCommoditiesPricing($data)
	{


		$previousPrices = DB::table('commodities_pricing')
			->whereIn('symbols', array_keys($data['rates']))
			->pluck('current_price', 'symbols');

		foreach ($previousPrices as $key => $previousPrice) {
			$utc_time = date('Y-m-d H:i:s');

			// Create a Carbon instance from the UTC time
			$utc_date_time = Carbon::createFromFormat('Y-m-d H:i:s', $utc_time, 'UTC');

			// Convert to Eastern Standard Time (EST)
			$est_date_time = $utc_date_time->setTimezone('America/New_York');

			$updated_price = [
				'previous_price' => $previousPrice, // Set previous_price to current_price before update
				'current_price' => $data['rates'][$key],
				'updated_through' => 1,
				'updated_at' => $est_date_time
			];

			DB::table('commodities_pricing')
				->where('symbols', $key)
				->update($updated_price);
		}
	}
}
