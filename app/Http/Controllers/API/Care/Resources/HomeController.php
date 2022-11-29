<?php

namespace App\Http\Controllers\API\Care\Resources;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Controllers\API\Care\Products\ProductFetchController;
use App\Http\Controllers\API\Care\Doctors\DoctorFetchController;
use App\Http\Controllers\API\Care\Articles\ArticleFetchController;
use App\Http\Controllers\API\Care\Addresses\AddressFetchController;
use App\Http\Controllers\API\Care\Carts\CartFetchController;
use App\Http\Controllers\API\Care\Regions\RegionFetchController;
use App\Http\Controllers\API\Care\Provinces\ProvinceFetchController;
use App\Http\Controllers\API\Care\Cities\CityFetchController;

use App\Models\Invoices\Invoice;
use App\Models\Articles\Article;
use App\Models\Points\Point;
use App\Models\Carts\Cart;
use App\Models\ShippingMethod\Standard;
use App\Models\ShippingMethod\Express;
use App\Models\Notifications\Notification;
use App\Models\BankDetails\BankDetail;
use App\Models\Products\Product;
use App\Models\Pages\PageItem;
use App\Models\Pages\Page;



use Carbon\Carbon;

use DB;

class HomeController extends Controller
{

    public function getPoints($invoices)
    {
        $points = $invoices->reduce(function($list, $invoice) {
            foreach ($invoice->invoiceItems as $item) {
                $list[] = [
                    'product_name' => $item->product->name,
                    'points' => $item->product->client_points
                ];
            }

            return $list;
        }, []);

        return $points;
    }

    
    public function resources(Request $request) 
    {

        $user = $request->user();

        $credits = (int) $user->countCredits();

        $fetch_addresses = new AddressFetchController($request);
        $fetch_regions = new RegionFetchController($request);
        $fetch_provinces = new ProvinceFetchController($request);
        $fetch_cities = new CityFetchController($request);

    	$ongoing_orders = Invoice::where(['user_id' => $user->id])->whereIn('status_id', [1, 2, 3, 4])->count();

    	$fetch_products = $user->doctors;
    	$doctors = collect($user->doctors)->map(function($doctor) {
            $doctor->ratings = $doctor->computeRatings();
            return $doctor;
        });
        
        $articles = Article::where('for_doctor', 1)->inRandomOrder()->take(5)->get();
        $addresses = $fetch_addresses->fetch($request);
        $carts = $user->getOrCreateCart()->cartItems;
        $regions = $fetch_regions->fetch($request);
        $provinces = $fetch_provinces->fetch($request);
        $cities = $fetch_cities->fetch($request);
        $bank_details = BankDetail::all();
        $points = $user->points;
        $reviewers = $user->reviewers->pluck('id')->toArray();

        $products = Product::getRandom();

        $articlesArray = [];

        foreach ($articles as $article) {
            array_push($articlesArray, [
                'id' => $article->id,
                'title' => str_limit($article->title, 25),
                'short_overview' => $article->renderShortString(),
                'overview' => $article->overview,
                'full_image' => url($article->renderImagePath('image_path')),
                'date' => $article->renderShortDate()
            ]);
        }

        $total_points = collect($points)->reduce(function($num, $point) {
            $num += $point['points'];
            return $num;
        });
        $total_items = $request->user()->getCart() ? count($request->user()->getCart()) : 0;

        $terms_item = PageItem::where('slug', 'terms_and_condition')->first(); 
        $terms_and_condition = $terms_item ? $terms_item->content : '';

        $privacy_item = PageItem::where('slug', 'privacy')->first(); 
        $privacy = $privacy_item ? $privacy_item->content: '';

        $myHealth_item = Page::where('slug', 'care_app_myhealth')->first();

    	return response()->json([
    		'products' => $products,
            'doctors' => $doctors,
            'regions' => $regions->original['items'],
    		'provinces' => $provinces->original['items'],
    		'cities' => $cities->original['items'],
            'articles' => $articlesArray,
            'addresses' => $addresses->original['items'],
            'cartItems' => $carts,
            'total_cart_items' => $total_items,
    		'ongoing_orders' => $ongoing_orders,
            'total_points' => count($points) ? $total_points : 0,
            'bank_details' => $bank_details,
            'terms_and_condition' => $terms_and_condition,
            'privacy' => $privacy,
            'reviewers' => $reviewers,
            'myhealth_item' => $myHealth_item->getData(),
            'credits' => $credits,
            'notification_count' => $user->notifications()->where('read_at', null)->count(),
            'opentok_key' => env('OPENTOK_API_KEY')
    	]);
    }

}
