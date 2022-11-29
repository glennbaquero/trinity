<?php

namespace App\Http\Controllers\API\Doctor;

use App\Models\Specializations\Specialization;
use App\Http\Controllers\Controller;

use App\Models\Pages\PageItem;

use Illuminate\Http\Request;

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

        $privacy_item = PageItem::where('slug', 'privacy_policy_doc')->first();
        $privacy = $privacy_item ? $privacy_item->content: '';

        return response()->json([
            'versions' => [
                'ios' => [
                    'stable_version' => env('IOS_DOC_STABLE_VERSION'),
                    'minimum_version' => env('IOS_DOC_MINIMUM_VERSION'),
                    'message' => 'Your App is outdated please download latest version.',
                    'download_link' => 'https://apps.apple.com/ph/app/trinity-doctor/id1484025783',
                ],
                'android' => [
                    'stable_version' => env('ANDROID_DOC_STABLE_VERSION'),
                    'minimum_version' => env('ANDROID_DOC_MINIMUM_VERSION'),
                    'message' => 'Your App is outdated please download latest version.',
                    'download_link' => 'https://play.google.com/store/apps/details?id=com.praxxys.trinitydoctor',
                ],
            ],
            'terms_and_condition' => $terms_and_condition,
            'privacy' => $privacy,
            'routes' => $this->getRoutes(),
            'specializations' => $this->specializations(),
        ]);
    }

    protected function getRoutes()
    {
        return array_merge($this->getApiRoutes());
    }

    protected function getApiRoutes() {
        return [
            // 'api.care.notifications.fetch' => route('api.care.notifications.fetch'),
            // 'api.care.notifications.read' => route('api.care.notifications.read'),
            'api.doc.store.token' => route('api.doc.store.token'),
            'api.doctor.notification.read' => route('api.doctor.notification.read'),
            'api.doctor.notifications.count' => route('api.doctor.notifications.count'),
            // 'api.update.token' => route('api.update.token'),

            'api.doctor.login' => route('api.doctor.login'),
            'api.doctor.logout' => route('api.doctor.logout'),
            'api.doctor.register' => route('api.doctor.register'),
            'web.doctor.password.email' => route('web.doctor.password.email'),

            'api.doctor.home' => route('api.doctor.home'),
            'api.doctor.notifications' => route('api.doctor.notifications'),
            'api.doctor.update.profile' => route('api.doctor.update.profile'),
            'api.doctor.update.online-status' => route('api.doctor.update.online-status'),
            'api.doctor.update.password' => route('api.doctor.update.password'),
            'api.doctor.merchant.redeem' => route('api.doctor.merchant.redeem'),
            'api.doctor.linked.products' => route('api.doctor.linked.products'),
            'api.doctor.linked.patients' => route('api.doctor.linked.patients'),
            'api.doctor.articles' => route('api.doctor.articles'),
            'api.doctor.articles.download' => route('api.doctor.articles.download'),
            'api.doctor.articles.comments.fetch' => route('api.doctor.articles.comments.fetch'),
            'api.doctor.articles.store' => route('api.doctor.articles.store'),
            'api.doctor.replies.store' => route('api.doctor.replies.store'),
            'api.doctor.faqs.fetch' => route('api.doctor.faqs.fetch'),

            'api.doctor.patient.records' => route('api.doctor.patient.records'),


            /*
            |--------------------------------------------------------------------------
            | Phase 4 Routes
            |--------------------------------------------------------------------------
            */

            'api.doctor.online-consultations.fetch' => route('api.doctor.online-consultations.fetch'),
            'api.doctor.schedules.store' => route('api.doctor.schedules.store'),
            'api.doctor.schedules.fetch' => route('api.doctor.schedules.fetch-nopagination'),

            'api.doctor.consultations.fetch.consultation.history' => route('api.doctor.consultations.fetch.consultation.history'),
            'api.doctor.consultations.fetch.chat.requests' => route('api.doctor.consultations.fetch.chat.requests'),
            'api.doctor.consultations.update.status' => route('api.doctor.consultations.update.status'),
            'api.doctor.consultations.send.notification' => route('api.doctor.consultations.send.notification'),


            'api.doctor.payouts.request' => route('api.doctor.payouts.request'),
            'api.doctor.payouts.fetch' => route('api.doctor.payouts.fetch'),

            'api.doctor.consultation-chats.fetch' => route('api.doctor.consultation-chats.fetch'),
            'api.doctor.consultation-chats.store' => route('api.doctor.consultation-chats.store'),
            
            'api.doctor.video-call-session.store' => route('api.doctor.video-call-session.store'),           
            'api.doctor.video-call-session.show' => route('api.doctor.video-call-session.show'),           

            'api.doctor.medical-prescriptions.store' => route('api.doctor.medical-prescriptions.store'),
            'api.doctor.medical-prescriptions.fetch' => route('api.doctor.medical-prescriptions.fetch'),

            'api.doctor.upload.signature' => route('api.doctor.upload.signature'),
            'api.doctor.call.manage' => route('api.doctor.call.manage-call'),            
        ];
    }

    /**
     * Fetch specialization types
     */
    protected function specializations()
    {
        return Specialization::all()->map(function($specialization) {
                    return [
                        'id' => $specialization->id,
                        'name' => $specialization->name,
                    ];
                })->sortBy('name')->values();
    }
}
