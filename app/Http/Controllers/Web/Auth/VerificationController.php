<?php

namespace App\Http\Controllers\Web\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Access\AuthorizationException;
use Auth;

use App\Models\Users\User;
use App\Models\Users\MedicalRepresentative;

class VerificationController extends Controller
                    
{
    /*
                    
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
                    
     * Where to redirect users after verification.
     *
     * @var string
     */

    protected $redirectTo = '';
    /**
                    
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('auth:web', ['except' => ['verify']]);
        // $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
                    
     * Show the email verification notice.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function show(Request $request)
    {
        return $request->user()->hasVerifiedEmail()
                        ? redirect()->route('web.account.verified', $request->user()->email)
                        : view('web.auth.verify');
    }

    /**
                    
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function verify(Request $request, $id, $type)
    {
        $model = $type == 'care' ? new User : new MedicalRepresentative; 
        $user = $model::find($id);

        if ($id != $user->getKey()) {
            throw new AuthorizationException;
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('web.account.verified', $user->email);
        }
        
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect()->route('web.account.verified', $user->email)->with('verified', true);
    }

    protected function user() {
        return new User;
    }

    protected function guard() {
        return Auth::guard('web');
    }
}