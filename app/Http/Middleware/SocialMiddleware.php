<?php

namespace App\Http\Middleware;

use Closure;

class SocialMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $services = ['facebook','google','github'];
        $enableServices =[];
        foreach ($services as $service) {
            if(config('services'.$service)){
                $enableServices[] = $service;
            }
        }
        if(!in_array(strtolower($request->services),$enableServices)){
            if($request->expectsJson()){
                return response()->json(['success'=>false,'message'=>'Invalid social service'],403);

            }else{
                return redirect()->back();
            }
        }
        return $next($request);
    }
}
