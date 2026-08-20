<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Models\Branch;
use Illuminate\Http\Request;

class CompanyCheckMiddelewre
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

        
        if(\Auth::user()->type == 'company'){
            $branch = Branch::where('created_by',\Auth::user()->creatorId())->first();
            if(empty($branch->name)){
                return redirect()->route('wizard_form')->with('error',__('file.add_bank_details'));
            }
        }
       return $next($request);
    }
}
