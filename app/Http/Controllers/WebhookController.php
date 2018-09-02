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
		$email_sent = true;
	    if (Shopify::verifyWebHook($data, $hmacHeader)) {
			
			$payload = json_decode($data , true);
			$order_id = $payload['id'];
			$email = $payload['contact_email'];
			$product_id = $payload['line_items']['0']["product_id"];
			$product_name = $payload['line_items']['0']["title"];
			$quantity = $payload['line_items']['0']["quantity"];
			
			$all_product_details = DB::Table('product_license_key')->select('product_id', 'product_name', 'license_key', 'resold')->where('product_id', $product_id)->get();
			Log::info("Hook Called");
			for ($i=0; $i < $quantity ; $i++) 
			{ 
				Log::info("Loop Called");
				$license_key = $all_product_details[$i]->license_key;
				$resold = $all_product_details[$i]->resold;

				$license_key_count = DB::Table('customer_product_keys')
					->select('product_id', 'license_key', 'customer_email')
							->where('license_key', $license_key)->count();
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
								'product_id' 	=> $product_id,
								'license_key' 	=> $license_key, 
								'customer_email'=> $email,
								'created_at'	=> date('Y-m-d H:i:s'), 
								'updated_at'	=> date('Y-m-d H:i:s')
							]);
							
							$this->send($email, $license_key);
							$email_sent = false;
							if($i >= $quantity)
							{
								break 1;
							}
					}
				}else
				{
					$email_sent = true;
				}
			}
			if($email_sent)
			{
				$id = DB::table('customer_withno_keys')->insertGetId([
					'product_name' 	=> $product_name,
					'license_key' 	=> $license_key, 
					'customer_email'=> $email,
					'created_at'	=> date('Y-m-d H:i:s'), 
					'updated_at'	=> date('Y-m-d H:i:s')
				]);	
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
		// Log::info('Congratulations! Email Sent.');
		// die();
	}
}
