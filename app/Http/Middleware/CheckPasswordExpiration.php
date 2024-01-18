<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Setting;
class CheckPasswordExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        
        // Check if the user is authenticated
        if (Auth::check()) {
            
            $user = Auth::user();
            
            // Check if the user's password has expired
            $passwordUpdatedAt = new Carbon($user->password_updated_at);'<br>';
            $daysSincePasswordUpdate = $passwordUpdatedAt->diffInDays(Carbon::now());
            $expirationPeriod = Setting::where('config_key', 'passwordSecurity|expiryDays')->get('value')[0]['value'];
            
            
            if($daysSincePasswordUpdate >= $expirationPeriod)
            {
                // Password has expired, redirect to reset password page
                return redirect()->route('admin.reset-password.create',['mobile' => $user->mobile]);
            }
        }
        
        return $next($request);
    }
}
