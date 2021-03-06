<?php
namespace App\Http\Controllers;
use DB;
use Log;
use App\Shop;
use App\Setting;
use App\Mail\GeekEmail;
use App\Objects\ScriptTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Oseintow\Shopify\Shopify;
use App\Objects\ShopifyWebhook;
use Oseintow\Shopify\Exceptions\ShopifyApiException;
use App\ShopInfo;

class ShopifyController extends Controller
{
    protected $shopify;
    function __construct(Shopify $shopify)
    {
    	$this->shopify = $shopify;
    }
    public function access(Request $request)
    {
    	
    	$shopUrl = $request->shop;
    	if($shopUrl)
    	{
    		$shop = Shop::where('myshopify_domain' , $shopUrl)->first();
    		if($shop)
    		{
    			session([
    					'shopifyId' => $shop->shopify_id,
    					'myshopifyDomain' => $shop->myshopify_domain,
    					'accessToken' => $shop->access_token
					]);
				$shopProducts = $this->shopify->setShopUrl($shop->myshopify_domain)
					->setAccessToken($shop->access_token)
					->get('admin/products.json',[ 'limit' => 250 , 'page' => 1 ]);
				$product_license_key = DB::table('product_license_key')->select('product_id', 'product_name', 'license_key', 'resold')->get();
				$resold_license_key = DB::table('count_license_key')->select('product_name', 'license_key', 'resold')->get();
    			$customers_withno_keys = DB::table('customer_withno_keys')->select('id', 'product_name', 'license_key', 'customer_email', 'reason')->get();
				return view('home.index' , ['shop' => $shop , 'settings' => $shop->settings, "shop_products" => $shopProducts, "product_license_key" => $product_license_key, "resold" => $resold_license_key, "customers_withno_keys" => $customers_withno_keys, 'success' => '0']);
    		}
    		else{
    			$shopify = $this->shopify->setShopUrl($shopUrl);
    			return redirect()->to($shopify->getAuthorizeUrl(config('shopify.scope') , config('shopify.redirect_uri')));
    		}
    	}
    	else{
    		abort(404);
    	}
    }
    public function callback(Request $request)
    {
		$queryString = $request->getQueryString();
		
    	if($this->shopify->verifyRequest($queryString))
    	{
    		$shopUrl = $request->shop;
    		try{
    			$accessToken = $this->shopify->setShopUrl($shopUrl)->getAccessToken($request->code);
    			$shopResponse = $this->shopify->setShopUrl($shopUrl)
    										  ->setAccessToken($accessToken)
    										  ->get('admin/shop.json');
  				if($shopResponse)
  				{
  					session([
  							'shopifyId' => $shopResponse['id'],
  							'myshopifyDomain' => $shopUrl,
  							'accessToken' => $accessToken
					]);
					
					$shop = $this->createShop($shopResponse);
					$this->createDefaultSettings($shop);
					$this->storeShopInfo($shopResponse, $shop->id);
					ShopifyWebhook::registerAppUninstallWebhook();
					if(config('shopify.billing_enabled'))
					{
						return redirect()->route('billing.charge');
					}
		
					ScriptTag::register();
					  
  					return redirect("https://{$shopUrl}/admin/apps");
  				}
    		} catch (ShopifyApiException $e) {
				Log::critical("Installation Callback exception." , ['message' => $e->getMessage(), 'shop' => $shopUrl]);
				abort(500);
    		}
    	}else{
			abort(500,"Hmm, Something doesn't look right.");
		}
    }
   	protected function createShop($data)
	{
		return Shop::create([
				'shopify_id' => $data['id'],
				'myshopify_domain' => $data['myshopify_domain'],
				'access_token' => session('accessToken')
		]);
	}
	protected function createDefaultSettings($shop)
    {
        return $settings = Setting::create([
            'enabled' => 1,
            'shop_id' => $shop->id,
            'myshopify_domain' => $shop->myshopify_domain
        ]);
	}
	
	protected function storeShopInfo($data, $shopId)
	{
		unset($data['id']);
		$data['shop_id'] = $shopId;
		return ShopInfo::create($data->toArray());
	}

	public function save_data()
	{
		$shopUrl= session('myshopifyDomain');
		$shop = Shop::where('myshopify_domain' , $shopUrl)->first();
		return view('home.index' , ['shop' => $shop , 'settings' => $shop->settings, 'success' => '1']);
	}

	public function save_license_key()
	{
		$product_ids = $_POST['trigger_product'];
		$dash_pos = strpos($product_ids, "-");
		$product_id = substr($product_ids, 0, $dash_pos);
		$product_name = substr($product_ids, $dash_pos+1);

		$license_key = $_POST['license_key'];
		$resold = $_POST['resold'];
		$shopify_store_id = $_POST['shopify_store_id'];
		$license_key_table = DB::Table('product_license_key')->where('product_id', $product_id )->where('license_key', $license_key)->first();
		if(empty($license_key_table))
		{
			$id = DB::table('product_license_key')->insertGetId([
				'shopify_store_id' => $shopify_store_id,
				'product_id' => $product_id, 
				'product_name' => $product_name,
				'license_key' => $license_key, 
				'resold'=> $resold, 
				'created_at'=> date('Y-m-d H:i:s'), 
				'updated_at'=> date('Y-m-d H:i:s')
			]);
		}else{
			DB::table('product_license_key')->where('product_id', $product_id)->update([
				'resold'=> $resold, 
				'updated_at' => date('Y-m-d H:i:s')]);
		}

		$shopUrl= session('myshopifyDomain');
		$shop = Shop::where('myshopify_domain' , $shopUrl)->first();
		$shopProducts = $this->shopify->setShopUrl($shop->myshopify_domain)
					->setAccessToken($shop->access_token)
					->get('admin/products.json',[ 'limit' => 250 , 'page' => 1 ]);
		$product_license_key = DB::table('product_license_key')->select('product_id', 'product_name', 'license_key', 'resold')->get();
		$resold_license_key = DB::table('count_license_key')->select('product_name', 'license_key', 'resold')->get();
		$customers_withno_keys = DB::table('customer_withno_keys')->select('id', 'product_name', 'license_key', 'customer_email', 'reason')->get();
		return view('home.index' , ['shop' => $shop , 'settings' => $shop->settings, "shop_products" => $shopProducts, "product_license_key" => $product_license_key, "resold" => $resold_license_key, "customers_withno_keys" => $customers_withno_keys, 'success' => '1']);
	}

	public function count_resold_license_keys()
	{
		$product_license_key = DB::table('product_license_key')->select('product_id', 'product_name', 'license_key', 'resold')->get();
		foreach($product_license_key as $key)
		{
			$license_key_count = DB::Table('customer_product_keys')
					->select('product_id', 'license_key', 'customer_email')
							->where('license_key', $key->license_key)->count();
			$license_key_counting_check = DB::Table('count_license_key')
				->select('license_key')
					->where('license_key', $key->license_key)->first();
			if(empty($license_key_counting_check))
			{
				$id = DB::table('count_license_key')->insertGetId([
					'product_name' => $key->product_name,
					'license_key' => $key->license_key,
					'resold' => $license_key_count, 
					'created_at'=> date('Y-m-d H:i:s'), 
					'updated_at'=> date('Y-m-d H:i:s')
				]);
			}else
			{
				DB::table('count_license_key')->where('license_key', $key->license_key)->update([
					'resold'=> $license_key_count, 
					'updated_at' => date('Y-m-d H:i:s')]);
			}
		}

		$shopUrl= session('myshopifyDomain');
		$shop = Shop::where('myshopify_domain' , $shopUrl)->first();
		$shopProducts = $this->shopify->setShopUrl($shop->myshopify_domain)
					->setAccessToken($shop->access_token)
					->get('admin/products.json',[ 'limit' => 250 , 'page' => 1 ]);
		$product_license_key = DB::table('product_license_key')->select('product_id', 'product_name', 'license_key', 'resold')->get();
		$resold_license_key = DB::table('count_license_key')->select('product_name', 'license_key', 'resold')->get();
		$customers_withno_keys = DB::table('customer_withno_keys')->select('id', 'product_name', 'license_key', 'customer_email', 'reason')->get();
		return view('home.index' , ['shop' => $shop , 'settings' => $shop->settings, "shop_products" => $shopProducts, "product_license_key" => $product_license_key, "resold" => $resold_license_key, "customers_withno_keys" => $customers_withno_keys, 'success' => '5']);

	}
	public function delete_license()
	{
		$product_id = $_GET['product_id'];
		$license_key = $_GET['license_key'];
		
		$deleting = DB::table('product_license_key')->where('product_id', $product_id )
			->where('license_key', $license_key)->delete();

		$shopUrl= session('myshopifyDomain');
		$shop = Shop::where('myshopify_domain' , $shopUrl)->first();
		$shopProducts = $this->shopify->setShopUrl($shop->myshopify_domain)
					->setAccessToken($shop->access_token)
					->get('admin/products.json',[ 'limit' => 250 , 'page' => 1 ]);
		$product_license_key = DB::table('product_license_key')->select('product_id', 'product_name', 'license_key', 'resold')->get();
		$resold_license_key = DB::table('count_license_key')->select('product_name', 'license_key', 'resold')->get();
		$customers_withno_keys = DB::table('customer_withno_keys')->select('id', 'product_name', 'license_key', 'customer_email', 'reason')->get();
		return view('home.index' , ['shop' => $shop , 'settings' => $shop->settings, "shop_products" => $shopProducts, "product_license_key" => $product_license_key, "resold" => $resold_license_key, "customers_withno_keys" => $customers_withno_keys, 'success' => '4']);
	
		
	}

	public function send_license_email()
	{
		$objDemo = new \stdClass();

		$license_key = array();

		$email 			= $_POST['email'];
		$customerKeyId 	= $_POST['customerKeyId'];
		$licenseKey 	= $_POST['licenseKey'];
		$product_name 	= $_POST['productName'];

		array_push($license_key, $licenseKey);
		
        $objDemo->email = $email;
        $objDemo->license_key = $license_key;
        $objDemo->product_name = $product_name;
        $objDemo->sender = 'Geek Repair Store';
        $objDemo->receiver = 'Valuable Customer';

		$response = Mail::to($email)->send(new GeekEmail($objDemo));

		
			$deleting = DB::table('customer_withno_keys')->where('id', $customerKeyId )->delete();


	}

	public function test_send()
    {
		$objDemo = new \stdClass();
		$email = "wajahatanwar56@gmail.com";
		$license_key = array("1231212421", "12124124", "12124124");
		$product_name = "Fuckit";
        $objDemo->email = $email;
        $objDemo->license_key = $license_key;
        $objDemo->product_name = $product_name;
        $objDemo->sender = 'Geek Repair Store';
        $objDemo->receiver = 'Valuable Customer';

		$response = Mail::to($email)->send(new GeekEmail($objDemo));
		// Log::info('Congratulations! Email Sent.');
		// die();
	}

	// public function test_function_for_order()
	// {
	// 	$product_id = "1452081643590";
	// 	$email =  "mr.wajahatanwar@gmail.com";
	// 	$all_product_details = DB::Table('product_license_key')->select('product_id', 'product_name', 'license_key', 'resold')->where('product_id', $product_id)->get();
		
	// 	foreach($all_product_details as $product_detail)
	// 	{
	// 		$product_id2 = $product_detail->product_id;
	// 		$license_key = $product_detail->license_key;
	// 		$resold = $product_detail->resold;

	// 		$license_key_count = DB::Table('customer_product_keys')
	// 			->select('product_id', 'license_key', 'customer_email')
	// 					->where('license_key', $license_key)->count();

	// 		// dd($license_key_count);
	// 		if($license_key_count < $resold)
	// 		{
	// 			$validating_license_key = DB::Table('customer_product_keys')
	// 			->select('product_id', 'license_key', 'customer_email')
	// 				->where('product_id', $product_id)
	// 					->where('license_key', $license_key)
	// 						->where('customer_email', $email)->first();
	
	// 			if(empty($validating_license_key))
	// 			{
	// 				$id = DB::table('customer_product_keys')->insertGetId([
	// 					'product_id' => $product_id,
	// 					'license_key' => $license_key, 
	// 					'customer_email' => $email,
	// 					'created_at'=> date('Y-m-d H:i:s'), 
	// 					'updated_at'=> date('Y-m-d H:i:s')
	// 				]);
	// 				$this->send($email, $license_key);
	// 				dd("done");
	// 			}
	// 		}
	// 	}
	// }

	// public function send($email, $license_key)
    // {
    //     $objDemo = new \stdClass();
    //     $objDemo->email = $email;
    //     $objDemo->license_key = $license_key;
    //     $objDemo->sender = 'Geek Repair Store';
    //     $objDemo->receiver = 'Valuable Customer';

	// 	$respnse = Mail::to($email)->send(new GeekEmail($objDemo));
	// 	dd($respnse);
	// }

}