<?php

namespace App\Http\Controllers;

use DB;
use App\Setting;
use App\Objects\ScriptTag;
use Log;
use App\Shop;
use App\Mail\GeekEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Oseintow\Shopify\Facades\Shopify;
use Symfony\Component\HttpFoundation\Response;


class WebhookController extends Controller
{
    public function app_uninstall(Request $request)
    {

	    $data = $request->getContent();

	    $hmacHeader = $request->server('HTTP_X_SHOPIFY_HMAC_SHA256');

	    if (Shopify::verifyWebHook($data, $hmacHeader)) {
	        
	    	$payload = json_decode($data , true);
	    	// $shop = Shop::where('shopify_id' , $payload['id'])->first();
	    	// $shop->delete();
	    	Log::info('Webhook Request verified and Handled.');
	    	return new Response('Webhook Handled', 200);

	    } else {
	        Log::info('Webhook Request was not verified.');
	    }

	}
	
	public function order_create(Request $request)
    {

	    $data = $request->getContent();
	    $hmacHeader = $request->server('HTTP_X_SHOPIFY_HMAC_SHA256');

	    if (Shopify::verifyWebHook($data, $hmacHeader)) {
			
			$payload = json_decode($data , true);
			$order_id = $payload['id'];
			$contact_email = $payload['contact_email'];
			$product_id = $payload['line_items']['0']["product_id"];
			Log::info($payload);
			// Log::info($order_id);
			// Log::info($contact_email);
			// Log::info($product_id);
			
			$all_product_details = DB::Table('product_license_key')->select('product_id', 'product_name', 'license_key', 'resold')->where('product_id', $product_id)->get();
			
			// Log::info($order_id);
			// Log::info($contact_email);
			Log::info($all_product_details);
			foreach($all_product_details as $product_detail)
			{
				$product_id2 = $product_detail->product_id;
				$license_key = $product_detail->license_key;
				$resold = $product_detail->resold;

				$license_key_count = DB::Table('customer_product_keys')
					->select('product_id', 'license_key', 'customer_email')
							->where('license_key', $license_key)->count();

				// dd($license_key_count);
				if($license_key_count < $resold)
				{
					$validating_license_key = DB::Table('customer_product_keys')
					->select('product_id', 'license_key', 'customer_email')
						->where('product_id', $product_id)
							->where('license_key', $license_key)
								->where('customer_email', $email)->first();
		
					if(empty($validating_license_key))
					{
						$id = DB::table('customer_product_keys')->insertGetId([
							'product_id' => $product_id,
							'license_key' => $license_key, 
							'customer_email' => $email,
							'created_at'=> date('Y-m-d H:i:s'), 
							'updated_at'=> date('Y-m-d H:i:s')
						]);
						$this->send($email, $license_key);
					}
				}
			}
			return new Response('Webhook Handled', 200);
	    } else {
	        Log::info('Webhook Request was not verified.');
	    }
	}

	public function send($email, $license_key)
    {
        $objDemo = new \stdClass();
        $objDemo->email = $email;
        $objDemo->license_key = $license_key;
        $objDemo->sender = 'Geek Repair Store';
        $objDemo->receiver = 'Valuable Customer';

		$response = Mail::to($email)->send(new GeekEmail($objDemo));
		Log::info('Congratulations! Email Sent.');
		dd($response);
	}
}
