<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use DB;
use App\Models\Utility;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Designation;

class CompanyDetailsLogin extends Controller
{

     public function wizard_form()
     {
          $timezones = config('timezones');
          $state = DB::table('state')->get();
          return view('wizard_form', compact('timezones', 'state'));
     }

     public function himl_call_back(request $request)
     {

          // dd(base64_decode($request->company_logo));

          $state = DB::table('state')->where('state_id', $request->bstate)->first();
          $getdistict = DB::table('district')->where('districtid', $request->bdistrict)->first();
          $html = '<div class="form foure_form d-none">
                    <div class="card card-fluid">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form_title mb-3">
                                        <h4> Review and Submit</h4>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="page">
                                        4/4
                                    </div>
                                </div>
                            </div>
                    <fieldset>

                    <div class="form-card">
                        <div class="instruction">
                            <h4> Review the information you provided before submitting.</h4>
                        </div>
                        <hr>
                        <p>By clicking "Submit," you agree that the information provided is accurate.
                        </p>
                        <div class="form_data">
                            <h5> Company Information</h5>
                            <div class="data">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <strong>Company Logo:</strong>
                                        <p id="show_images"></p>
                                        <p class="error_message_form text-danger"></p>
                                    </div>

                                    <div class="col-sm-4">
                                        <strong>Company Name:</strong>
                                        <p>' . $request->company_name . '</p>
                                    </div>

                                    <div class="col-sm-4">
                                        <strong>Company Address:</strong>
                                        <p>' . $request->company_address . '</p>
                                    </div>
                                    <div class="col-sm-4">
                                        <strong>City:</strong>
                                        <p>' . $request->company_city . '</p>
                                    </div>

                                    <div class="col-sm-4">
                                        <strong>State:</strong>
                                        <p>' . $request->company_state . '</p>
                                    </div>

                                    <div class="col-sm-4">
                                        <strong>Zip/Postal Code:</strong>
                                        <p>' . $request->company_zipcode . '</p>
                                    </div>
                                    <div class="col-sm-4">
                                        <strong>Country:</strong>
                                        <p>' . $request->company_Country . '</p>
                                    </div>

                                   

                                   

                                    <div class="col-sm-4">
                                        <strong>Telephone:</strong>
                                        <p>' . $request->company_telephone . '</p>
                                    </div>

                                    <div class="col-sm-4">
                                        <strong>System Email:</strong>
                                        <p>' . $request->system_email . '</p>
                                    </div>

                                    <div class="col-sm-4">
                                        <strong>Phone:</strong>
                                        <p>' . $request->company_phone . '</p>
                                    </div>

                                    <div class="col-sm-4">
                                        <strong>GST Number:</strong>
                                        <p>' . $request->company_gst . '</p>
                                    </div>

                                    <div class="col-sm-4">
                                        <strong>ESIC (Employee State Insurance Corporation)Number:</strong>
                                        <p>' . $request->company_esic . '</p>
                                    </div>
                                    <div class="col-sm-4">
                                        <strong>PF (Provident Fund) Number:</strong>
                                        <p>' . $request->company_pf . '</p>
                                    </div>

                                    <div class="col-sm-4">
                                        <strong>TAN (Tax Deduction and Collection Account Number):</strong>
                                        <p>' . $request->company_tan . '</p>
                                    </div>

                                    <div class="col-sm-4">
                                        <strong>PEC (Professional Employer Code):</strong>
                                        <p>' . $request->company_pec . '</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form_data">
                            <h5> Business Hours and Timezone</h5>
                            <div class="data">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <strong>Company Start Time:</strong>
                                        <p>' . date("g:i a", strtotime($request->company_starttime)) . '</p>
                                    </div>

                                    <div class="col-sm-4">
                                        <strong>Company End Time:</strong>
                                        <p>' . date("g:i a", strtotime($request->company_endtime)) . '</p>
                                    </div>

                                    <div class="col-sm-4">
                                        <strong>Timezone:</strong>
                                        <p>' . $request->company_timezone . '</p>
                                    </div>
                                </div>
                                <hr>
                                <h6 class="bg-secondary p-2 text-white rounded-1">Branch Information</h6>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <strong>Branch Name:</strong>
                                        <p>' . $request->bname . '</p>
                                    </div>

                                    <div class="col-sm-4">
                                        <strong>Branch Country:</strong>
                                        <p>' . $request->bcountry . '</p>
                                    </div>


                                    <div class="col-sm-4">
                                        <strong>Branch State:</strong>
                                        <p>' . $state->state_title . '</p>
                                    </div>

                                    <div class="col-sm-4">
                                        <strong>Branch District:</strong>
                                        <p>' . $getdistict->district_title . '</p>
                                    </div>


                                    <div class="col-sm-4">
                                        <strong>Branch City:</strong>
                                        <p>' . $request->bcity . '</p>
                                    </div>

                                    <div class="col-sm-4">
                                        <strong>Branch Zip/Postal Code:</strong>
                                        <p>' . $request->bzip . '</p>
                                    </div>

                                    <div class="col-sm-4">
                                        <strong>Branch Address:</strong>
                                        <p>' . $request->baddress . '</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form_data">
                            <h5> Department and Designation Information</h5>
                            <div class="data">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <strong>Department Name:</strong>
                                        <p>' . $request->dname . '</p>
                                    </div>

                                    <div class="col-sm-4">
                                        <strong>Designation:</strong>
                                        <p>' . $request->designation . '</p>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="button" name="submit" class="next btn btn-primary action-button final_submit"
                        value="Submit" onclick="return final_submit()";/>
                    <input type="button" name="back" class="back btn btn-light action-button-previous"
                        value="Back" onclick="return third_form_back()"/>
                </fieldset>
            </div>
        </div>
    </div>';

          $response = ["html" => $html];
          return json_encode($response);
     }

     public function finally_submit_data_company(request $request)
     {
          try
          {
               DB::beginTransaction();


               $post = $request->all();
               if (!isset($request->ip_restrict))
               {
                    $post['ip_restrict'] = 'off';
               }

               unset($post['_token']);

               $settings = Utility::settings();
               foreach ($post as $key => $data)
               {
                    if ((in_array($key, array_keys($settings)) && $data !== null))
                    {
                         // dd( $settings);

                         \DB::insert(
                              'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                              [
                                   // $settings,
                                   $data,
                                   $key,
                                   \Auth::user()->creatorId(),
                              ]
                         );
                    }
               }
               $arrEnv = [
                    'TIMEZONE' => $request->timezone,
               ];

               $user = \Auth::user();
               if ($request->company_logo)
               {
                    $logoName = $user->id . '_dark_logo.png';
                    $dir = 'uploads/logo/';
                    $validation = [];
                    $validation = [
                         'mimes:' . 'png',
                         'max:' . '20480',
                    ];

                    $path = Utility::upload_file($request, 'company_logo', $logoName, $dir, $validation);

                    if ($path['flag'] == 1)
                    {
                         $url = $path['url'];
                    }
                    else
                    {
                         // return redirect()->back()->with('error', __($path['msg']));
                         $response = [
                              'status' => 0,
                              'message' => $path['msg'],
                         ];
                         return ApiResponse($response);
                    }
                    \DB::insert(
                         'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                         [
                              $logoName,
                              'company_logo',
                              \Auth::user()->creatorId(),
                         ]
                    );
               }


               $branch             = new Branch();
               $branch->name        = $request->bname;
               $branch->country_name = $request->bcountry;
               $branch->state_name   = $request->bstate;
               $branch->district_name = $request->bdistrict;
               $branch->city_name     = $request->bcity;
               $branch->zip_code      = $request->bzip;
               $branch->address       = $request->baddress;

               $branch->created_by = \Auth::user()->creatorId();
               $branch->save();

               //department
               $department             = new Department();
               $department->branch_id  = $branch->id;
               $department->name       = $request->dname;
               $department->status       = 'Active';
               $department->created_by = \Auth::user()->creatorId();
               $department->save();

               $designation                = new Designation();
               $designation->department_id = $department->id;
               $designation->name          = $request->designation;
               $designation->status       = 'Active';
               $designation->created_by    = \Auth::user()->creatorId();
               $designation->save();
               Utility::setEnvironmentValue($arrEnv);
               DB::commit();
               $response = [
                    'status' => 1,
                    'message' => 'success',
               ];
               return ApiResponse($response);
          }
          catch (\Exception $e)
          {
               DB::rollBack();
               $error_msg =  error_msg();
               return ApiResponse($error_msg);
          }
          catch (\Throwable $e)
          {
               DB::rollBack();
               $error_msg =  error_msg();
               return ApiResponse($error_msg);
          }
     }
}
