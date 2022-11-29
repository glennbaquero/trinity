<?php

namespace App\Http\Controllers\API\Care;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\API\Care\Articles\ArticleFetchController;

use App\Models\Regions\Region;
use App\Models\Provinces\Province;
use App\Models\Products\Product;
use App\Models\Articles\Article;
use App\Models\Pages\PageItem;

class ConfigFetchController extends Controller
{
    /**
     * Fetch the system.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetch()
    {
        $terms_item = PageItem::where('slug', 'terms_and_condition')->first();
        $terms_and_condition =  $terms_item ? $terms_item->content: '' ;

        $privacy_item = PageItem::where('slug', 'privacy')->first();
        $privacy = $privacy_item ? $privacy_item->content: '';
        
        return response()->json([
            'versions' => [
                'ios' => [
                    'stable_version' => env('IOS_CARE_STABLE_VERSION'),
                    'minimum_version' => env('IOS_CARE_MINIMUM_VERSION'),
                    'message' => 'Your App is outdated please download latest version.',
                    'download_link' => 'https://apps.apple.com/ph/app/trinity-care/id1484025649',
                ],
                'android' => [
                    'stable_version' => env('ANDROID_CARE_STABLE_VERSION'),
                    'minimum_version' => env('ANDROID_CARE_MINIMUM_VERSION'),
                    'message' => 'Your App is outdated please download latest version.',
                    'download_link' => 'https://play.google.com/store/apps/details?id=com.praxxys.trinitycare',
                ],
            ],
            'routes' => $this->getRoutes(),
            'terms_and_condition' => $terms_and_condition,
            'privacy' => $privacy,
            'provinces' => $this->getProvinces(),
            'regions' => $this->getRegions(),
            'products' => $this->getProducts(),
            'articles' => $this->getArticles(),
            // 'regions' => $this->getRegions(),
        ]);
    }

    // public function getRegions() {
    //     return Region::with('provinces')->get();
    // }

    public function getArticles() {
        $items = Article::where('for_doctor', 1)->get();
        $result = [];

        foreach($items as $item) {
            array_push($result, [
                'id' => $item->id,
                'title' => $item->title,
                'overview' => $item->overview,
                'image_path' => url($item->renderImagePath('image_path')),
                'date' => $item->renderShortDate()
            ]);
        }

        return $result;
    }

    public function getProducts() {
        return Product::get();
    }

    public function getRegions() {
        return Region::with('provinces')->get();
    }

    public function getProvinces() {
        return Province::with('cities')->get();
    }

    protected function getRoutes()
    {
        return array_merge($this->getApiRoutes());
    }

    protected function getApiRoutes() {
        return [
            'api.care.login' => route('api.care.login'),
            'api.care.logout' => route('api.care.logout'),
            'api.care.register' => route('api.care.register'),
            'api.care.fb.register' => route('api.care.fb.register'),
            'api.care.home' => route('api.care.home'),
            'api.care.profile.update' => route('api.care.profile.update'),
            'api.care.setting.update' => route('api.care.setting.update'),
            'api.care.address.add' => route('api.care.address.add'),
            'api.care.product-parents.fetch' => route('api.care.product-parents.fetch'),
            'api.care.products.fetch' => route('api.care.products.fetch'),
            'api.care.products.fetch.variants' => route('api.care.products.fetch.variants'),
            'api.care.articles.fetch' => route('api.care.articles.fetch'),
            'api.care.cart.add' => route('api.care.cart.add'),
            'api.care.cart.fetch' => route('api.care.cart.fetch'),
            'api.care.cart.item.update' => route('api.care.cart.item.update'),
            'api.care.cart.delete' => route('api.care.cart.delete'),

            /*
            |--------------------------------------------------------------------------
            | Phase 5 Routes
            |--------------------------------------------------------------------------
            */
            'api.care.cart.linked-md.update' => route('api.care.cart.linked-md.update'),
            'api.care.cart.prescription.upload' => route('api.care.cart.prescription.upload'),

            /*
            |--------------------------------------------------------------------------
            | Phase 5 Routes End
            |--------------------------------------------------------------------------
            */            

            'api.care.articles.store' => route('api.care.articles.store'),
            'api.care.replies.store' => route('api.care.replies.store'),
            'api.care.doctor.scan' => route('api.care.doctor.scan'),

            'api.care.my-health.fetch' => route('api.care.my-health.fetch'),
            'api.care.my-health.update' => route('api.care.my-health.update'),
            'api.care.my-health.update-reviewers' => route('api.care.my-health.update-reviewers'),

            'api.care.faqs.fetch' => route('api.care.faqs.fetch'),
            
            'api.care.merchants' => route('api.care.merchants'),
            'api.care.merchant.details' => route('api.care.merchant.details'),
            'api.care.reward.redeem' => route('api.care.reward.redeem'),
            'api.care.history.redeem' => route('api.care.history.redeem'),
            'api.care.shipping-matrixes.fetch' => route('api.care.shipping-matrixes.fetch'),

            'api.care.upload.deposit' => route('api.care.upload.deposit'),
            'api.care.notifications' => route('api.care.notifications'),
            'api.care.notification.read' => route('api.care.notification.read'),

            'api.care.verify.email' => route('api.care.verify.email'),

            'api.store.token' => route('api.store.token'),

            'web.social.facebook.login' => route('api.care.facebook.login'),
            'web.social.facebook.callback' => route('api.care.facebook.callback'),
            'api.care.apple.login' => route('api.care.apple.login'),

            'web.social.google.login' => route('api.care.google.login'),

            'api.care.password.email' => route('api.care.password.email'),


            /*
            |--------------------------------------------------------------------------
            | Phase 4 Routes
            |--------------------------------------------------------------------------
            */
            'api.care.credit-packages.history' => route('api.care.credit-packages.history'),
            'api.care.credit-packages.fetch' => route('api.care.credit-packages.fetch'),

            'api.care.consultations.fetch-consultation' => route('api.care.consultations.fetch-consultation'),
            'api.care.consultations.fetch' => route('api.care.consultations.fetch'),
            'api.care.consultations.fetch-history' => route('api.care.consultations.fetch-history'),            
            'api.care.consultations.store' => route('api.care.consultations.store'),
            'api.care.consultations.cancel' => route('api.care.consultations.cancel'),
            'api.care.consultations.check-pending' => route('api.care.consultations.check-pending'),
            'api.care.consultations.chat-request' => route('api.care.consultations.chat-request'),
            'api.care.consultations.completed' => route('api.care.consultations.completed'),
            'api.care.consultations.send.notification' => route('api.care.consultations.send.notification'),

            'api.care.specializations.fetch' => route('api.care.specializations.fetch'),
            'api.care.specialization-doctors.fetch' => route('api.care.specialization-doctors.fetch'),

            'api.care.doctors.list' => route('api.care.doctors.list'),

            'api.care.credit-package.checkout' => route('api.care.credit-package.checkout'),
            'api.care.credit-package.return' => route('api.care.credit-package.return'),

            'api.care.personal-informations.store' => route('api.care.personal-informations.store'),
            'api.care.personal-informations.share' => route('api.care.personal-informations.share'),

            'api.care.schedules.fetch' => route('api.care.schedules.fetch'),

            'api.care.refunds.store' => route('api.care.refunds.store'),

            'api.care.consultation-chats.fetch' => route('api.care.consultation-chats.fetch'),
            'api.care.consultation-chats.store' => route('api.care.consultation-chats.store'),

            'api.care.video-call-session.store' => route('api.care.video-call-session.store'),
            'api.care.video-call-session.show' => route('api.care.video-call-session.show'),

            'api.care.medical-prescriptions.fetch' => route('api.care.medical-prescriptions.fetch'),
            'api.care.medical-prescriptions.download' => route('api.care.medical-prescriptions.download'),

            /*
            |--------------------------------------------------------------------------
            | Phase 5 Routes
            |--------------------------------------------------------------------------
            */
            'api.care.medical-prescriptions.check-price' => route('api.care.medical-prescriptions.check-price'),
           
            'api.care.doctor-reviews.get-reviews' => route('api.care.doctor-reviews.get-reviews'),
            'api.care.doctor-reviews.store-review' => route('api.care.doctor-reviews.store-review'),

            'api.care.my-vouchers.fetch' => route('api.care.my-vouchers.fetch'),     
            'api.care.my-vouchers.redeem' => route('api.care.my-vouchers.redeem'),                 

            'api.care.request-claim-referrals.request' => route('api.care.request-claim-referrals.request'),
            'api.care.profile.orders.fetch' => route('api.care.invoice.fetch'),
            'api.care.product-filters.fetch' => route('api.care.products-filter.fetch'),

            'api.care.my-doctors.fetch' => route('api.care.my-doctors.fetch'),

            'api.care.articles.comments.fetch' => route('api.care.articles.comments.fetch'),
            'api.care.articles.related.fetch' => route('api.care.articles.related.fetch'),

            'api.care.call.manage' => route('api.care.call.manage-call'),            
        ];
    }
}
