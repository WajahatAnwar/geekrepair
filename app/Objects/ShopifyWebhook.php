<?php

namespace App\Objects;



use Oseintow\Shopify\Facades\Shopify;

class ShopifyWebhook {
    
    public static function registerAppUninstallWebhook()
    {
        // $postData = [
            
        //     "topic" => "app/uninstalled",
        //     "address" => config('app.url') . '/shopify/webhook/app_uninstall',
        //     "format" => "json"
            
        // ];

        $postData2 = [
            
            "topic" => "orders/create",
            "address" => config('app.url') . '/shopify/webhook/order_create',
            "format" => "json"
            
        ];
        
        // return Shopify::setShopUrl(session('myshopifyDomain'))
        //                ->setAccessToken(session('accessToken'))
        //                ->post('admin/webhooks.json' , [ "webhook" => $postData]);
        
        return Shopify::setShopUrl(session('myshopifyDomain'))
                       ->setAccessToken(session('accessToken'))
                       ->post('admin/webhooks.json' , [ "webhook" => $postData2]);
    }
    
}