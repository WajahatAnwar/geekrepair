<?php
namespace App\Http\Controllers;
use DB;
use Log;
use App\Shop;
use App\Setting;
use App\Objects\ScriptTag;
use Illuminate\Http\Request;
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
    			return view('home.index' , ['shop' => $shop , 'settings' => $shop->settings, "shop_products" => $shopProducts, 'success' => '2']);
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
		$license_key_table = DB::Table('product_license_key')->where('product_id', $product_id )->where('license_key', $license_key)->first();
		if(empty($license_key_table))
		{
			$id = DB::table('product_license_key')->insertGetId([
				'product_id' => $product_id, 
				'product_name' => $product_name,
				'license_key' => $license_key, 
				'resold'=> $resold, 
				'created_at'=> date('Y-m-d H:i:s'), 
				'updated_at'=> date('Y-m-d H:i:s')
			]);
		}else{
			DB::table('product_license_key')->where('product_id', $product_id)->update([
				'updated_at' => date('Y-m-d H:i:s')]);
		}
	}

}