<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta name="csrf-token" content="{{ csrf_token() }}" />
     <title>{{ __('Set up detail') }}</title>
     <link rel="icon" href="https://app.hrshastri.com//storage/uploads/logo//160_favicon.png" type="image/x-icon" />
     <link rel="preconnect" href="https://fonts.googleapis.com">
     <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
     <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Livvic:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,900&family=Poppins:wght@100&display=swap" rel="stylesheet">
     <!-- for calender-->
     <!-- Favicon icon -->
     <!-- for calender-->
     <link rel="stylesheet" href="{{ asset('assets/css/plugins/main.css')}}">

     <link rel="stylesheet" href="{{ asset('/assets/css/plugins/datepicker-bs5.min.css')}}">
     <link rel="stylesheet" href="{{ asset('/assets/css/plugins/style.css')}}">
     <!-- font css -->
     <link rel="stylesheet" href="{{ asset('/assets/css/plugins/bootstrap-switch-button.min.css')}}">
     <link rel="stylesheet" href="{{ asset('/assets/fonts/tabler-icons.min.css')}}">
     <link rel="stylesheet" href="{{ asset('/assets/fonts/feather.css')}}">
     <link rel="stylesheet" href="{{ asset('/assets/fonts/fontawesome.css')}}">
     <link rel="stylesheet" href="{{ asset('/assets/fonts/material.css')}}">
     <!-- vendor css -->
     <meta name="url" content="{{ asset('/chats')}}" data-user="160">
     <link rel="stylesheet" href="{{ asset('/assets/css/customizer.css')}}">
     <link rel="stylesheet" href="{{ asset('/css/custom.css')}}">
     <link rel='stylesheet' href='https://unpkg.com/nprogress@0.2.0/nprogress.css' />
     <link rel="stylesheet" href="{{ asset('/assets/css/style.css')}}" id="main-style-link">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" />
     <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

     <style>
          .wizard_form .logo img {
               width: 200px;
          }

          .wizard_form .logo {
               background-color: #fff;
               overflow: hidden;
               width: 200px;
               margin: 0 auto;
               border-radius: 5px;
               padding: 10px 11px;
               margin-bottom: 40px;
          }

          .wizard_form {
               position: relative;
          }

          .wizard_form:before {
               position: absolute;
               content: '';
               background: linear-gradient(141.55deg, rgba(81, 69, 157, 0) 3.46%, rgba(255, 58, 110, 0.6) 99.86%), #51459d !important;
               top: 0;
               height: 100%;
               width: 100%;
               left: 0;
               z-index: -1;
          }

          .form_title h4 {
               font-size: 24px;
          }

          .company_logo {
               width: 150px;
               margin-top: 10px
          }

          .select2-container {
               display: block !important;
               width: 100% !important;
          }

          .select2-container--default .select2-selection--single .select2-selection__rendered {

               line-height: 38px !important;
          }

          .select2-container .select2-selection--single {
               border: 1px solid #ced4da !important;
               border-radius: 6px !important;
               height: 40px !important;

          }

          .select2-container--default .select2-selection--single .select2-selection__arrow {
               top: 8px !important;
               right: 8px !important;
          }
     </style>
</head>

<body>

     <section class="wizard_form min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
          <div class="container">
               <div class="row">
                    <div class="col-sm-9 mb-3 text-center mx-auto">
                         <div class="logo">
                              <img src="https://app.hrshastri.com//storage/uploads/logo/logo-dark.png" class="img-fluid" alt="">
                         </div>
                    </div>
               </div>
               <div class="row">
                    <div class="col-sm-9 mx-auto">
                         <div class="form first_form">


                              <div class="card card-fluid">
                                   <div class="card-body ">
                                        <div class="row">
                                             <div class="col-sm-6">
                                                  <div class="form_title mb-3">
                                                       <h4>Company Information</h4>
                                                  </div>
                                             </div>
                                             <div class="col-sm-6">
                                                  <div class="page">
                                                       1/4
                                                  </div>
                                             </div>
                                        </div>
                                        <fieldset>

                                             <div class="form-card">

                                                  <div class="instruction">
                                                       <h4> Please provide your company's information.</h4>
                                                  </div>

                                                  <hr>
                                                  <form enctype="multipart/form-data">
                                                       <div class="row">
                                                            <div class="col-sm-4 mb-3">
                                                                 <label for="company_logo" class="form-label">Company Logo <span class="text-danger"> * </span></label>
                                                                 <input class="form-control" type="file" name="company_logo" id="company_logo" onblur="return company_logo_1();" onchange="company_logo_1();">
                                                                 <label style="display:none" generated="true" class="errorAll" id="company_logo_error" for="company_logo_error">&nbsp;</label>
                                                                 <img src="" alt="">
                                                                 <img id="uploadPreview" class="company_logo" />
                                                                 <div id="ul_id" name="ul_id" hidden></div>
                                                            </div>

                                                            <div class="col-sm-4 mb-3">
                                                                 <label for="company_name" class="form-label">Company Name <span class="text-danger"> * </span></label>
                                                                 <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Please enter company name" onblur="return company_name_1();">
                                                                 <label style="display:none" generated="true" class="errorAll" id="company_name_error" for="company_name_error">&nbsp;</label>
                                                            </div>

                                                            <div class="col-sm-4 mb-3">
                                                                 <label for="company_address" class="form-label">Company Address <span class="text-danger">*</span></label>
                                                                 <input type="text" class="form-control" id="company_address" name="company_address" placeholder="Please enter company address" onblur="return company_address_1();">
                                                                 <label style="display:none" generated="true" class="errorAll" id="company_address_error" for="company_address_error">&nbsp;</label>
                                                            </div>

                                                            <div class="col-sm-4 mb-3">
                                                                 <label for="company_city" class="form-label">City <span class="text-danger">*</span></label>
                                                                 <input type="text" class="form-control" id="company_city" placeholder="Please enter city" onblur="return company_city_1();">
                                                                 <label style="display:none" generated="true" class="errorAll" id="company_city_error" for="company_city_error">&nbsp;</label>
                                                            </div>

                                                            <div class="col-sm-4 mb-3">
                                                                 <label for="company_state" class="form-label">State <span class="text-danger">*</span></label>
                                                                 <input type="text" class="form-control" id="company_state" placeholder="Please enter state" onblur="return company_state_1();">
                                                                 <label style="display:none" generated="true" class="errorAll" id="company_state_error" for="company_state_error">&nbsp;</label>
                                                            </div>

                                                            <div class="col-sm-4 mb-3">
                                                                 <label for="company_zipcode" class="form-label">Zip/Postal Code <span class="text-danger">*</span></label>
                                                                 <input type="text" class="form-control" id="company_zipcode" placeholder="Please enter zipcode" onKeyPress="if(this.value.length==6) return false;" onblur="return company_zipcode_1();">
                                                                 <label style="display:none" generated="true" class="errorAll" id="company_zipcode_error" for="company_zipcode_error">&nbsp;</label>
                                                            </div>

                                                            <div class="col-sm-4 mb-3">
                                                                 <label for="company_Country" class="form-label">Country <span class="text-danger"> * </span></label>
                                                                 <input type="text" class="form-control" id="company_Country" placeholder="Please enter country" onblur="return company_Country_1();">
                                                                 <label style="display:none" generated="true" class="errorAll" id="company_Country_error" for="company_Country_error">&nbsp;</label>
                                                            </div>

                                                            <div class="col-sm-4 mb-3">
                                                                 <label for="company_telephone" class="form-label">Telephone <span class="text-danger"> * </span></label>
                                                                 <input type="text" class="form-control" id="company_telephone" placeholder="Please enter telephone" onKeyPress="if(this.value.length==10) return false;" onblur="return company_telephone_1();">
                                                                 <label style="display:none" generated="true" class="errorAll" id="company_telephone_error" for="company_telephone_error">&nbsp;</label>
                                                            </div>

                                                            <div class="col-sm-4 mb-3">
                                                                 <label for="system_email" class="form-label">System Email <span class="text-danger"> * </span></label>
                                                                 <input type="email" class="form-control" id="system_email" name="system_email" placeholder="Please enter email" onblur="return system_email_1();">
                                                                 <label style="display:none" generated="true" class="errorAll" id="system_email_error" for="system_email_error">&nbsp;</label>
                                                            </div>

                                                            <div class="col-sm-4 mb-3">
                                                                 <label for="company_phone" class="form-label">Phone</label>
                                                                 <input type="text" class="form-control" id="company_phone" name="company_phone" onKeyPress="if(this.value.length==10) return false;" placeholder="Please enter phone">
                                                            </div>

                                                            <div class="col-sm-4 mb-3">
                                                                 <label for="company_gst" class="form-label">GST Number</label>
                                                                 <input type="text" class="form-control" id="company_gst" placeholder="Please enter GST number" name="company_gst">
                                                            </div>
                                                            <div class="col-sm-4 mb-3">
                                                                 <label for="company_esic" class="form-label">ESIC (Employee State Insurance Corporation) Number</label>
                                                                 <input type="text" class="form-control" id="company_esic" name="company_esic" placeholder="Please enter ESIC">
                                                            </div>

                                                            <div class="col-sm-4 mb-3">
                                                                 <label for="company_pf" class="form-label">PF (Provident Fund) Number</label>
                                                                 <input type="text" class="form-control" id="company_pf" name="company_pf" placeholder="Please enter PF number">
                                                            </div>

                                                            <div class="col-sm-4 mb-3">
                                                                 <label for="company_tan" class="form-label">TAN (Tax Deduction and Collection Account Number)</label>
                                                                 <input type="text" class="form-control" id="company_tan" name="company_tan" placeholder="Please enter TAN number">
                                                            </div>

                                                            <div class="col-sm-4 mb-3">
                                                                 <label for="company_pec" class="form-label">PEC (Professional Employer Code)</label>
                                                                 <input type="text" class="form-control" id="company_pec" name="company_pec" placeholder="Please enter PEC">
                                                            </div>
                                                       </div>

                                             </div>

                                             <input type="button" name="next" class="next btn btn-primary action-button" value="Next" onclick="return first_form()" ; />
                                        </fieldset>
                                   </div>
                              </div>
                         </div>

                         <div class="form secound_form d-none">

                              <div class="card card-fluid">

                                   <div class="card-body ">
                                        <div class="row">

                                             <div class="col-sm-6">
                                                  <div class="form_title mb-3">
                                                       <h4>Business Hours and Timezone</h4>
                                                  </div>
                                             </div>
                                             <div class="col-sm-6">
                                                  <div class="page">
                                                       2/4
                                                  </div>
                                             </div>
                                        </div>
                                        <fieldset>

                                             <div class="form-card">


                                                  <div class="instruction">
                                                       <h4> Configure your company's operating hours and timezone settings.</h4>
                                                  </div>

                                                  <hr>

                                                  <div class="row">
                                                       <div class="col-sm-4 mb-3">
                                                            <label for="company_starttime" class="form-label">Company Start Time <span class="text-danger"> * </span></label>
                                                            <input type="time" class="form-control" name="company_starttime" id="company_starttime" onblur="return company_starttime_1();">
                                                            <label style="display:none" generated="true" class="errorAll" id="company_starttime_error" for="company_starttime_error">&nbsp;</label>
                                                       </div>

                                                       <div class="col-sm-4 mb-3">
                                                            <label for="company_endtime" class="form-label">Company End Time <span class="text-danger"> * </span></label>
                                                            <input type="time" class="form-control" name="company_endtime" id="company_endtime" onblur="return company_endtime_1();">
                                                            <label style="display:none" generated="true" class="errorAll" id="company_endtime_error" for="company_endtime_error">&nbsp;</label>
                                                       </div>

                                                       <div class="col-sm-4 mb-3">
                                                            <label for="timezone" class="form-label">Timezone <span class="text-danger"> * </span></label>
                                                            <select class="form-control form-select select_staging" aria-label="Default select example" id="company_timezone" name="company_timezone" onblur="return company_timezone_1();">
                                                                 <option value="">{{ __('Select Timezone') }}</option>
                                                                 @if (!empty($timezones))
                                                                 @foreach ($timezones as $k => $timezone)
                                                                 <option value="{{ $k }}" {{ env('TIMEZONE') == $k ? 'selected' : '' }}>
                                                                      {{ $timezone }}
                                                                 </option>
                                                                 @endforeach
                                                                 @endif
                                                            </select>
                                                            <label style="display:none" generated="true" class="errorAll" id="company_timezone_error" for="company_timezone_error">&nbsp;</label>
                                                       </div>


                                                  </div>


                                                  <hr>

                                                  <div class="form_title mb-3">
                                                       <h4>Branch Information</h4>
                                                  </div>

                                                  <div class="instruction mb-2">
                                                       <h4> Provide details about your company's branch.</h4>
                                                  </div>


                                                  {{-- <div class="float-end">
                                                <a href="" class="btn btn-primary ">+ Add</a>
                                            </div> --}}
                                                  <div class="row float-start add_border">
                                                       <div class="col-sm-4 mb-3">
                                                            <label for="bname" class="form-label">Branch Name <span class="text-danger"> * </span></label>
                                                            <input type="text" class="form-control" id="bname" name="bname" placeholder="Enter branch name" onblur="return bname_1();">
                                                            <label style="display:none" generated="true" class="errorAll" id="bname_error" for="bname_error">&nbsp;</label>
                                                       </div>

                                                       <div class="col-sm-4 mb-3">
                                                            <label for="bcountry" class="form-label">Branch Country <span class="text-danger"> * </span> </label>
                                                            {{-- <input type="text" class="form-control" id="bcountry" name="bcountry" onblur="return bcountry_1();"> --}}
                                                            <div class="form-icon-user">
                                                                 <select name="bcountry" id="bcountry" class="form-control" onblur="return bcountry_1();">
                                                                      <option value="India">India</option>
                                                                 </select>
                                                            </div>

                                                            <label style="display:none" generated="true" class="errorAll" id="bcountry_error" for="bcountry_error">&nbsp;</label>
                                                       </div>

                                                       <div class="col-sm-4 mb-3">
                                                            <label for="bstate" class="form-label">Branch State <span class="text-danger"> * </span></label>
                                                            {{-- <input type="text" class="form-control" id="bstate" name="bstate" onblur="return bstate_1();"> --}}
                                                            <select name="bstate" id="bstate" required class="form-control select_staging" onblur="return bstate_1();">
                                                                 <option value="">Please select State</option>
                                                                 @foreach ($state as $st)
                                                                 <option value="{{ $st->state_id }}">{{ $st->state_title}}</option>
                                                                 @endforeach
                                                            </select>
                                                            <label style="display:none" generated="true" class="errorAll" id="bstate_error" for="bstate_error">&nbsp;</label>
                                                       </div>

                                                       <div class="col-sm-4 mb-3">
                                                            <label for="bdistrict" class="form-label">Branch District <span class="text-danger"> * </span> </label>
                                                            {{-- <input type="text" class="form-control" id="bdistrict" name="bdistrict" onblur="return bdistrict_1();"> --}}
                                                            <select name="bdistrict" id="bdistrict" required class="form-control select_staging" onblur="return bdistrict_1();">
                                                                 <option value="">Please select district</option>
                                                            </select>
                                                            <label style="display:none" generated="true" class="errorAll" id="bdistrict_error" for="bdistrict_error">&nbsp;</label>
                                                       </div>

                                                       <div class="col-sm-4 mb-3">
                                                            <label for="bcity" class="form-label">Branch City <span class="text-danger"> * </span></label>
                                                            {{-- <input type="text" class="form-control" id="bcity" name="bcity" onblur="return bcity_1();"> --}}
                                                            <select name="bcity" id="bcity" required class="form-control select_staging" onblur="return bcity_1();">
                                                                 <option value="">Please select city</option>
                                                            </select>
                                                            <label style="display:none" generated="true" class="errorAll" id="bcity_error" for="bcity_error">&nbsp;</label>
                                                       </div>

                                                       <div class="col-sm-4 mb-3">
                                                            <label for="bzip" class="form-label">Branch Zip/Postal Code <span class="text-danger"> * </span></label>
                                                            <input type="text" class="form-control" id="bzip" name="bzip" placeholder="Enter branch zipcode" onKeyPress="if(this.value.length==6) return false;" onblur="return bzip_1();">
                                                            <label style="display:none" generated="true" class="errorAll" id="bzip_error" for="bzip_error">&nbsp;</label>
                                                       </div>

                                                       <div class="col-sm-12 mb-3">
                                                            <label for="baddress" class="form-label">Branch Address <span class="text-danger"> * </span></label>
                                                            <input type="text" class="form-control" id="baddress" placeholder="Enter branch address" name="baddress" onblur="return baddress_1();">
                                                            <label style="display:none" generated="true" class="errorAll" id="baddress_error" for="baddress_error">&nbsp;</label>
                                                       </div>

                                                  </div>

                                             </div>

                                             <input type="button" name="next" class="next btn btn-primary action-button" value="Next" onclick="return secound_form()" ; />

                                             <input type="button" name="back" class="back btn btn-light action-button-previous" value="Back" onclick="return first_form_back()" ; />
                                        </fieldset>
                                   </div>
                              </div>
                         </div>

                         <div class="form third_form d-none">


                              <div class="card card-fluid">
                                   <div class="card-body ">
                                        <div class="row">
                                             <div class="col-sm-6">
                                                  <div class="form_title mb-3">

                                                       <h4> Department and Designation Information</h4>
                                                  </div>
                                             </div>
                                             <div class="col-sm-6">
                                                  <div class="page">
                                                       3/4
                                                  </div>
                                             </div>

                                        </div>
                                        <fieldset>

                                             <div class="form-card">
                                                  <div class="instruction">
                                                       <h4> List all the departments in your company and their respective
                                                            designations.</h4>
                                                  </div>

                                                  <hr>
                                                  <div class="add_form">

                                                       <div class="row add_border">
                                                            <div class="col-sm-6 mb-3">
                                                                 <label for="dname" class="form-label">Department Name <span class="text-danger"> * </span></label>
                                                                 <input type="text" class="form-control" id="dname" placeholder="Please enter department name" onblur="return dname_1();">
                                                                 <label style="display:none" generated="true" class="errorAll" id="dname_error" for="dname_error">&nbsp;</label>
                                                            </div>
                                                            <div class="col-sm-6 mb-3">
                                                                 <label for="designation" class="form-label">Designation <span class="text-danger"> * </span></label>
                                                                 <input type="text" class="form-control" id="designation" placeholder="Please enter designation name" onblur="return designation_1();">
                                                                 <label style="display:none" generated="true" class="errorAll" id="designation_error" for="designation_error">&nbsp;</label>
                                                            </div>
                                                       </div>

                                                  </div>
                                             </div>
                                             <input type="button" name="next" class="next btn btn-primary action-button" value="Next" onclick="return third_form()" ; />
                                             <input type="button" name="back" class="back btn btn-light action-button-previous" value="Back" onclick="return secound_form_back()" ; />
                                        </fieldset>
                                   </div>
                              </div>
                         </div>

                         <span class="load_view_html"></span>
                         </form>
                    </div>
               </div>
          </div>
          </div>
     </section>

</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.1.min.js"></script>

<script>
     $(document).ready(function() {


          $('.select_staging').select2({

          });

          $("#bstate").change(function() {

               $.ajax({
                    type: "get",
                    url: "{{ url('bgetdistict') }}" + '/' + this.value,
                    success: function(data) {
                         console.log(data);
                         var options = '<option value="">Please select District</option>';
                         $.each(data, function(index, object) {
                              options += '<option value="' + object.districtid + '">' + object.district_title + '</option>';
                         });
                         $('#bdistrict').html(options);
                    },
                    error: function(data) {
                         $('#bdistrict').html('<option value="">Please select District</option>');
                         console.log('Error:', data);
                    }
               });
          });
          //get City
          $("#bdistrict").change(function() {
               $.ajax({
                    type: "get",
                    url: "{{ url('bgetcities') }}" + '/' + this.value,
                    success: function(data) {
                         var options = '<option value="">Please select City</option>';
                         $.each(data, function(index, object) {
                              console.log(object);
                              options += '<option value="' + object.name + '">' + object.name + '</option>';
                         });
                         $('#bcity').html(options);
                    },
                    error: function(data) {
                         $('#bcity').html('<option value="">Please select City</option>');
                         console.log('Error:', data);
                    }
               });
          });

     });

     function first_form() {
          var status = true;
          if (company_logo_1() == false) {
               status = false;
          }
          if (company_name_1() == false) {
               status = false;
          }
          if (company_address_1() == false) {
               status = false;
          }
          if (company_city_1() == false) {
               status = false;
          }
          if (company_state_1() == false) {
               status = false;
          }
          if (company_zipcode_1() == false) {
               status = false;
          }
          if (company_Country_1() == false) {
               status = false;
          }
          if (company_telephone_1() == false) {
               status = false;
          }
          if (system_email_1() == false) {
               status = false;
          }
          if (status == true) {
               $(".first_form").addClass("d-none");
               $(".secound_form").removeClass("d-none");
          }
          return status;
     }

     function company_logo_1() {
          var company_logo = $("#company_logo").val()



          if (company_logo == '') {
               jQuery("#company_logo").removeClass("right");
               jQuery("#company_logo").next("span").removeClass("right");
               jQuery("#company_logo").addClass("wrong");
               jQuery("#company_logo").next("span").addClass("wrong");
               jQuery("#company_logo").css("border", "1px solid red");
               jQuery("#company_logo_error").css("display", "");
               jQuery("#company_logo_error").css("color", "red");
               jQuery("#company_logo_error").html('This field is required');
               return false;
          } else {
               jQuery("#company_logo").removeClass("wrong");
               jQuery("#company_logo").next("span").removeClass("wrong");
               jQuery("#company_logo").addClass("right");
               jQuery("#company_logo").next("span").addClass("right");
               jQuery("#company_logo").css("border", "1px solid #ccc");
               jQuery("#company_logo_error").css("display", "none");

               var oFReader = new FileReader(document.getElementById("company_logo").files[0]);
               oFReader.readAsDataURL(document.getElementById("company_logo").files[0])
               oFReader.onload = function(oFREvent) {
                    document.getElementById("uploadPreview").src = oFREvent.target.result;
                    document.getElementById('ul_id').innerHTML = oFREvent.target.result;
                    $('#ul_id').val(oFREvent.target.result);
               };

               return true;
          }
     }

     function company_name_1() {
          var company_name = $("#company_name").val()
          if (company_name == '') {
               jQuery("#company_name").removeClass("right");
               jQuery("#company_name").next("span").removeClass("right");
               jQuery("#company_name").addClass("wrong");
               jQuery("#company_name").next("span").addClass("wrong");
               jQuery("#company_name").css("border", "1px solid red");
               jQuery("#company_name_error").css("display", "");
               jQuery("#company_name_error").css("color", "red");
               jQuery("#company_name_error").html('This field is required');
               return false;
          } else {
               jQuery("#company_name").removeClass("wrong");
               jQuery("#company_name").next("span").removeClass("wrong");
               jQuery("#company_name").addClass("right");
               jQuery("#company_name").next("span").addClass("right");
               jQuery("#company_name").css("border", "1px solid #ccc");
               jQuery("#company_name_error").css("display", "none");
               return true;
          }
     }

     function company_address_1() {
          var company_address = $("#company_address").val()
          if (company_address == '') {
               jQuery("#company_address").removeClass("right");
               jQuery("#company_address").next("span").removeClass("right");
               jQuery("#company_address").addClass("wrong");
               jQuery("#company_address").next("span").addClass("wrong");
               jQuery("#company_address").css("border", "1px solid red");
               jQuery("#company_address_error").css("display", "");
               jQuery("#company_address_error").css("color", "red");
               jQuery("#company_address_error").html('This field is required');
               return false;
          } else {
               jQuery("#company_address").removeClass("wrong");
               jQuery("#company_address").next("span").removeClass("wrong");
               jQuery("#company_address").addClass("right");
               jQuery("#company_address").next("span").addClass("right");
               jQuery("#company_address").css("border", "1px solid #ccc");
               jQuery("#company_address_error").css("display", "none");
               return true;
          }
     }

     function company_city_1() {
          var company_city = $("#company_city").val()
          if (company_city == '') {
               jQuery("#company_city").removeClass("right");
               jQuery("#company_city").next("span").removeClass("right");
               jQuery("#company_city").addClass("wrong");
               jQuery("#company_city").next("span").addClass("wrong");
               jQuery("#company_city").css("border", "1px solid red");
               jQuery("#company_city_error").css("display", "");
               jQuery("#company_city_error").css("color", "red");
               jQuery("#company_city_error").html('This field is required');
               return false;
          } else {
               jQuery("#company_city").removeClass("wrong");
               jQuery("#company_city").next("span").removeClass("wrong");
               jQuery("#company_city").addClass("right");
               jQuery("#company_city").next("span").addClass("right");
               jQuery("#company_city").css("border", "1px solid #ccc");
               jQuery("#company_city_error").css("display", "none");
               return true;
          }
     }

     function company_state_1() {
          var company_state = $("#company_state").val()
          if (company_state == '') {
               jQuery("#company_state").removeClass("right");
               jQuery("#company_state").next("span").removeClass("right");
               jQuery("#company_state").addClass("wrong");
               jQuery("#company_state").next("span").addClass("wrong");
               jQuery("#company_state").css("border", "1px solid red");
               jQuery("#company_state_error").css("display", "");
               jQuery("#company_state_error").css("color", "red");
               jQuery("#company_state_error").html('This field is required');
               return false;
          } else {
               jQuery("#company_state").removeClass("wrong");
               jQuery("#company_state").next("span").removeClass("wrong");
               jQuery("#company_state").addClass("right");
               jQuery("#company_state").next("span").addClass("right");
               jQuery("#company_state").css("border", "1px solid #ccc");
               jQuery("#company_state_error").css("display", "none");
               return true;
          }
     }

     function company_zipcode_1() {
          var company_zipcode = $("#company_zipcode").val()
          var regexp = /^[0-9-+s()]*$/;
          if (company_zipcode == '') {
               jQuery("#company_zipcode").removeClass("right");
               jQuery("#company_zipcode").next("span").removeClass("right");
               jQuery("#company_zipcode").addClass("wrong");
               jQuery("#company_zipcode").next("span").addClass("wrong");
               jQuery("#company_zipcode").css("border", "1px solid red");
               jQuery("#company_zipcode_error").css("display", "");
               jQuery("#company_zipcode_error").css("color", "red");
               jQuery("#company_zipcode_error").html('This field is required');
               return false;
          } else if (company_zipcode.length != 6) {
               jQuery("#company_zipcode").removeClass("right");
               jQuery("#company_zipcode").next("span").removeClass("right");
               jQuery("#company_zipcode").addClass("wrong");
               jQuery("#company_zipcode").next("span").addClass("wrong");
               jQuery("#company_zipcode").css("border", "1px solid red");
               jQuery("#company_zipcode_error").css("display", "");
               jQuery("#company_zipcode_error").css("color", "red");
               jQuery("#company_zipcode_error").html('Please enter a valid zipcode number');
               return false;
          } else if (company_zipcode.search(regexp) == -1) {
               jQuery("#company_zipcode").removeClass("right");
               jQuery("#company_zipcode").next("span").removeClass("right");
               jQuery("#company_zipcode").addClass("wrong");
               jQuery("#company_zipcode").next("span").addClass("wrong");
               jQuery("#company_zipcode").css("border", "1px solid red");
               jQuery("#company_zipcode_error").css("display", "");
               jQuery("#company_zipcode_error").css("color", "red");
               jQuery("#company_zipcode_error").html('Please enter a valid zipcode number');
               return false;
          } else {
               jQuery("#company_zipcode").removeClass("wrong");
               jQuery("#company_zipcode").next("span").removeClass("wrong");
               jQuery("#company_zipcode").addClass("right");
               jQuery("#company_zipcode").next("span").addClass("right");
               jQuery("#company_zipcode").css("border", "1px solid #ccc");
               jQuery("#company_zipcode_error").css("display", "none");
               return true;
          }
     }

     function company_Country_1() {
          var company_Country = $("#company_Country").val()
          if (company_Country == '') {
               jQuery("#company_Country").removeClass("right");
               jQuery("#company_Country").next("span").removeClass("right");
               jQuery("#company_Country").addClass("wrong");
               jQuery("#company_Country").next("span").addClass("wrong");
               jQuery("#company_Country").css("border", "1px solid red");
               jQuery("#company_Country_error").css("display", "");
               jQuery("#company_Country_error").css("color", "red");
               jQuery("#company_Country_error").html('This field is required');
               return false;
          } else {
               jQuery("#company_Country").removeClass("wrong");
               jQuery("#company_Country").next("span").removeClass("wrong");
               jQuery("#company_Country").addClass("right");
               jQuery("#company_Country").next("span").addClass("right");
               jQuery("#company_Country").css("border", "1px solid #ccc");
               jQuery("#company_Country_error").css("display", "none");
               return true;
          }
     }

     function company_telephone_1() {
          var company_telephone = $("#company_telephone").val()
          var regexp = /^[0-9-+s()]*$/;
          if (company_telephone == '') {
               jQuery("#company_telephone").removeClass("right");
               jQuery("#company_telephone").next("span").removeClass("right");
               jQuery("#company_telephone").addClass("wrong");
               jQuery("#company_telephone").next("span").addClass("wrong");
               jQuery("#company_telephone").css("border", "1px solid red");
               jQuery("#company_telephone_error").css("display", "");
               jQuery("#company_telephone_error").css("color", "red");
               jQuery("#company_telephone_error").html('This field is required');
               return false;
          } else if (company_telephone.length != 10) {
               jQuery("#company_telephone").removeClass("right");
               jQuery("#company_telephone").next("span").removeClass("right");
               jQuery("#company_telephone").addClass("wrong");
               jQuery("#company_telephone").next("span").addClass("wrong");
               jQuery("#company_telephone").css("border", "1px solid red");
               jQuery("#company_telephone_error").css("display", "");
               jQuery("#company_telephone_error").css("color", "red");
               jQuery("#company_telephone_error").html('Please enter a valid telephone number 10 digits');
               return false;
          } else if (company_telephone.search(regexp) == -1) {
               jQuery("#company_telephone").removeClass("right");
               jQuery("#company_telephone").next("span").removeClass("right");
               jQuery("#company_telephone").addClass("wrong");
               jQuery("#company_telephone").next("span").addClass("wrong");
               jQuery("#company_telephone").css("border", "1px solid red");
               jQuery("#company_telephone_error").css("display", "");
               jQuery("#company_telephone_error").css("color", "red");
               jQuery("#company_telephone_error").html('Please enter a valid telephone number');
               return false;
          } else {
               jQuery("#company_telephone").removeClass("wrong");
               jQuery("#company_telephone").next("span").removeClass("wrong");
               jQuery("#company_telephone").addClass("right");
               jQuery("#company_telephone").next("span").addClass("right");
               jQuery("#company_telephone").css("border", "1px solid #ccc");
               jQuery("#company_telephone_error").css("display", "none");
               return true;
          }
     }

     function system_email_1() {
          var system_email = $("#system_email").val()
          var regexp = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
          if (system_email == '') {
               jQuery("#system_email").removeClass("right");
               jQuery("#system_email").next("span").removeClass("right");
               jQuery("#system_email").addClass("wrong");
               jQuery("#system_email").next("span").addClass("wrong");
               jQuery("#system_email").css("border", "1px solid red");
               jQuery("#system_email_error").css("display", "");
               jQuery("#system_email_error").css("color", "red");
               jQuery("#system_email_error").html('This field is required');
               return false;
          } else if (system_email.search(regexp) == -1) {
               jQuery("#system_email").removeClass("right");
               jQuery("#system_email").next("span").removeClass("right");
               jQuery("#system_email").addClass("wrong");
               jQuery("#system_email").next("span").addClass("wrong");
               jQuery("#system_email").css("border", "1px solid red");
               jQuery("#system_email_error").css("display", "");
               jQuery("#system_email_error").css("color", "red");
               jQuery("#system_email_error").html('Please enter a valid email');
               return false;
          } else {
               jQuery("#system_email").removeClass("wrong");
               jQuery("#system_email").next("span").removeClass("wrong");
               jQuery("#system_email").addClass("right");
               jQuery("#system_email").next("span").addClass("right");
               jQuery("#system_email").css("border", "1px solid #ccc");
               jQuery("#system_email_error").css("display", "none");
               return true;
          }
     }


     //secound page
     function secound_form() {
          var status = true;
          if (company_starttime_1() == false) {
               status = false;
          }
          if (company_endtime_1() == false) {
               status = false;
          }
          if (company_timezone_1() == false) {
               status = false;
          }
          if (bname_1() == false) {
               status = false;
          }
          if (bcountry_1() == false) {
               status = false;
          }
          if (bstate_1() == false) {
               status = false;
          }
          if (bdistrict_1() == false) {
               status = false;
          }
          if (bcity_1() == false) {
               status = false;
          }
          if (bzip_1() == false) {
               status = false;
          }
          if (baddress_1() == false) {
               status = false;
          }
          if (status == true) {
               $(".third_form").removeClass("d-none ");
               $(".secound_form").addClass("d-none");
          }
          return status;
     }

     function first_form_back() {
          $(".first_form").removeClass("d-none ");
          $(".secound_form").addClass("d-none");
     }

     function company_starttime_1() {
          var company_starttime = $("#company_starttime").val()
          if (company_starttime == '') {
               jQuery("#company_starttime").removeClass("right");
               jQuery("#company_starttime").next("span").removeClass("right");
               jQuery("#company_starttime").addClass("wrong");
               jQuery("#company_starttime").next("span").addClass("wrong");
               jQuery("#company_starttime").css("border", "1px solid red");
               jQuery("#company_starttime_error").css("display", "");
               jQuery("#company_starttime_error").css("color", "red");
               jQuery("#company_starttime_error").html('This field is required');
               return false;
          } else {
               jQuery("#company_starttime").removeClass("wrong");
               jQuery("#company_starttime").next("span").removeClass("wrong");
               jQuery("#company_starttime").addClass("right");
               jQuery("#company_starttime").next("span").addClass("right");
               jQuery("#company_starttime").css("border", "1px solid #ccc");
               jQuery("#company_starttime_error").css("display", "none");
               return true;
          }
     }

     function company_endtime_1() {
          var company_endtime = $("#company_endtime").val()
          if (company_endtime == '') {
               jQuery("#company_endtime").removeClass("right");
               jQuery("#company_endtime").next("span").removeClass("right");
               jQuery("#company_endtime").addClass("wrong");
               jQuery("#company_endtime").next("span").addClass("wrong");
               jQuery("#company_endtime").css("border", "1px solid red");
               jQuery("#company_endtime_error").css("display", "");
               jQuery("#company_endtime_error").css("color", "red");
               jQuery("#company_endtime_error").html('This field is required');
               return false;
          } else {
               jQuery("#company_endtime").removeClass("wrong");
               jQuery("#company_endtime").next("span").removeClass("wrong");
               jQuery("#company_endtime").addClass("right");
               jQuery("#company_endtime").next("span").addClass("right");
               jQuery("#company_endtime").css("border", "1px solid #ccc");
               jQuery("#company_endtime_error").css("display", "none");
               return true;
          }
     }

     function company_timezone_1() {
          var company_timezone = $("#company_timezone").val()
          if (company_timezone == '') {
               jQuery("#company_timezone").removeClass("right");
               jQuery("#company_timezone").next("span").removeClass("right");
               jQuery("#company_timezone").addClass("wrong");
               jQuery("#company_timezone").next("span").addClass("wrong");
               jQuery("#company_timezone").css("border", "1px solid red");
               jQuery("#company_timezone_error").css("display", "");
               jQuery("#company_timezone_error").css("color", "red");
               jQuery("#company_timezone_error").html('This field is required');
               return false;
          } else {
               jQuery("#company_timezone").removeClass("wrong");
               jQuery("#company_timezone").next("span").removeClass("wrong");
               jQuery("#company_timezone").addClass("right");
               jQuery("#company_timezone").next("span").addClass("right");
               jQuery("#company_timezone").css("border", "1px solid #ccc");
               jQuery("#company_timezone_error").css("display", "none");
               return true;
          }
     }

     function bname_1() {
          var bname = $("#bname").val()
          if (bname == '') {
               jQuery("#bname").removeClass("right");
               jQuery("#bname").next("span").removeClass("right");
               jQuery("#bname").addClass("wrong");
               jQuery("#bname").next("span").addClass("wrong");
               jQuery("#bname").css("border", "1px solid red");
               jQuery("#bname_error").css("display", "");
               jQuery("#bname_error").css("color", "red");
               jQuery("#bname_error").html('This field is required');
               return false;
          } else {
               jQuery("#bname").removeClass("wrong");
               jQuery("#bname").next("span").removeClass("wrong");
               jQuery("#bname").addClass("right");
               jQuery("#bname").next("span").addClass("right");
               jQuery("#bname").css("border", "1px solid #ccc");
               jQuery("#bname_error").css("display", "none");
               return true;
          }
     }

     function bcountry_1() {
          // var bcountry = $("#bcountry").val()
          var bcountry = $("#bcountry option:selected").val()
          if (bcountry == '') {
               jQuery("#bcountry").removeClass("right");
               jQuery("#bcountry").next("span").removeClass("right");
               jQuery("#bcountry").addClass("wrong");
               jQuery("#bcountry").next("span").addClass("wrong");
               jQuery("#bcountry").css("border", "1px solid red");
               jQuery("#bcountry_error").css("display", "");
               jQuery("#bcountry_error").css("color", "red");
               jQuery("#bcountry_error").html('This field is required');
               return false;
          } else {
               jQuery("#bcountry").removeClass("wrong");
               jQuery("#bcountry").next("span").removeClass("wrong");
               jQuery("#bcountry").addClass("right");
               jQuery("#bcountry").next("span").addClass("right");
               jQuery("#bcountry").css("border", "1px solid #ccc");
               jQuery("#bcountry_error").css("display", "none");
               return true;
          }
     }

     function bstate_1() {
          // var bstate = $("#bstate").val()
          var bstate = $("#bstate option:selected").val()
          if (bstate == '') {
               jQuery("#bstate").removeClass("right");
               jQuery("#bstate").next("span").removeClass("right");
               jQuery("#bstate").addClass("wrong");
               jQuery("#bstate").next("span").addClass("wrong");
               jQuery("#bstate").css("border", "1px solid red");
               jQuery("#bstate_error").css("display", "");
               jQuery("#bstate_error").css("color", "red");
               jQuery("#bstate_error").html('This field is required');
               return false;
          } else {
               jQuery("#bstate").removeClass("wrong");
               jQuery("#bstate").next("span").removeClass("wrong");
               jQuery("#bstate").addClass("right");
               jQuery("#bstate").next("span").addClass("right");
               jQuery("#bstate").css("border", "1px solid #ccc");
               jQuery("#bstate_error").css("display", "none");
               return true;
          }
     }

     function bdistrict_1() {
          // var bdistrict = $("#bdistrict").val()
          var bdistrict = $("#bdistrict option:selected").val()
          if (bdistrict == '') {
               jQuery("#bdistrict").removeClass("right");
               jQuery("#bdistrict").next("span").removeClass("right");
               jQuery("#bdistrict").addClass("wrong");
               jQuery("#bdistrict").next("span").addClass("wrong");
               jQuery("#bdistrict").css("border", "1px solid red");
               jQuery("#bdistrict_error").css("display", "");
               jQuery("#bdistrict_error").css("color", "red");
               jQuery("#bdistrict_error").html('This field is required');
               return false;
          } else {
               jQuery("#bdistrict").removeClass("wrong");
               jQuery("#bdistrict").next("span").removeClass("wrong");
               jQuery("#bdistrict").addClass("right");
               jQuery("#bdistrict").next("span").addClass("right");
               jQuery("#bdistrict").css("border", "1px solid #ccc");
               jQuery("#bdistrict_error").css("display", "none");
               return true;
          }
     }

     function bcity_1() {
          // var bcity = $("#bcity").val()
          var bcity = $("#bcity option:selected").val()
          if (bcity == '') {
               jQuery("#bcity").removeClass("right");
               jQuery("#bcity").next("span").removeClass("right");
               jQuery("#bcity").addClass("wrong");
               jQuery("#bcity").next("span").addClass("wrong");
               jQuery("#bcity").css("border", "1px solid red");
               jQuery("#bcity_error").css("display", "");
               jQuery("#bcity_error").css("color", "red");
               jQuery("#bcity_error").html('This field is required');
               return false;
          } else {
               jQuery("#bcity").removeClass("wrong");
               jQuery("#bcity").next("span").removeClass("wrong");
               jQuery("#bcity").addClass("right");
               jQuery("#bcity").next("span").addClass("right");
               jQuery("#bcity").css("border", "1px solid #ccc");
               jQuery("#bcity_error").css("display", "none");
               return true;
          }
     }

     function bzip_1() {
          var bzip = $("#bzip").val()
          var regexp = /^[0-9-+s()]*$/;
          if (bzip == '') {
               jQuery("#bzip").removeClass("right");
               jQuery("#bzip").next("span").removeClass("right");
               jQuery("#bzip").addClass("wrong");
               jQuery("#bzip").next("span").addClass("wrong");
               jQuery("#bzip").css("border", "1px solid red");
               jQuery("#bzip_error").css("display", "");
               jQuery("#bzip_error").css("color", "red");
               jQuery("#bzip_error").html('This field is required');
               return false;
          } else if (bzip.length != 6) {
               jQuery("#bzip").removeClass("right");
               jQuery("#bzip").next("span").removeClass("right");
               jQuery("#bzip").addClass("wrong");
               jQuery("#bzip").next("span").addClass("wrong");
               jQuery("#bzip").css("border", "1px solid red");
               jQuery("#bzip_error").css("display", "");
               jQuery("#bzip_error").css("color", "red");
               jQuery("#bzip_error").html('Please enter a valid zipcode number');
               return false;
          } else {
               jQuery("#bzip").removeClass("wrong");
               jQuery("#bzip").next("span").removeClass("wrong");
               jQuery("#bzip").addClass("right");
               jQuery("#bzip").next("span").addClass("right");
               jQuery("#bzip").css("border", "1px solid #ccc");
               jQuery("#bzip_error").css("display", "none");
               return true;
          }
     }

     function baddress_1() {
          var baddress = $("#baddress").val()
          if (baddress == '') {
               jQuery("#baddress").removeClass("right");
               jQuery("#baddress").next("span").removeClass("right");
               jQuery("#baddress").addClass("wrong");
               jQuery("#baddress").next("span").addClass("wrong");
               jQuery("#baddress").css("border", "1px solid red");
               jQuery("#baddress_error").css("display", "");
               jQuery("#baddress_error").css("color", "red");
               jQuery("#baddress_error").html('This field is required');
               return false;
          } else {
               jQuery("#baddress").removeClass("wrong");
               jQuery("#baddress").next("span").removeClass("wrong");
               jQuery("#baddress").addClass("right");
               jQuery("#baddress").next("span").addClass("right");
               jQuery("#baddress").css("border", "1px solid #ccc");
               jQuery("#baddress_error").css("display", "none");
               return true;
          }
     }


     //third page
     function third_form() {
          var status = true;
          if (dname_1() == false) {
               status = false;
          }
          if (designation_1() == false) {
               status = false;
          }
          if (status == true) {
               var company_logo = $("#company_logo").val()
               var company_name = $("#company_name").val()
               var company_address = $("#company_address").val()
               var company_city = $("#company_city").val()
               var company_state = $("#company_state").val()
               var company_zipcode = $("#company_zipcode").val()
               var company_Country = $("#company_Country").val()
               var company_telephone = $("#company_telephone").val()

               var system_email = $("#system_email").val()
               var company_phone = $("#company_phone").val()
               var company_gst = $("#company_gst").val()
               var company_esic = $("#company_esic").val()
               var company_pf = $("#company_pf").val()
               var company_tan = $("#company_tan").val()
               var company_pec = $("#company_pec").val()
               //step 2
               var company_starttime = $("#company_starttime").val()
               var company_endtime = $("#company_endtime").val()
               var company_timezone = $("#company_timezone").val()
               var bname = $("#bname").val()
               var bcountry = $("#bcountry").val()
               var bstate = $("#bstate").val()
               var bdistrict = $("#bdistrict").val()
               var bcity = $("#bcity").val()
               var bzip = $("#bzip").val()
               var baddress = $("#baddress").val()
               var dname = $("#dname").val()
               var designation = $("#designation").val()
               $.ajax({
                    headers: {
                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ url('himl_call_back') }}",
                    data: {
                         'company_logo': company_logo,
                         'company_name': company_name,
                         'company_address': company_address,
                         'company_city': company_city,
                         'company_state': company_state,
                         'company_zipcode': company_zipcode,
                         'company_Country': company_Country,
                         'company_telephone': company_telephone,
                         'system_email': system_email,
                         'company_phone': company_phone,
                         'company_gst': company_gst,
                         'company_esic': company_esic,
                         'company_pf': company_pf,
                         'company_tan': company_tan,
                         'company_pec': company_pec,

                         'company_starttime': company_starttime,
                         'company_endtime': company_endtime,
                         'company_timezone': company_timezone,
                         'bname': bname,
                         'bcountry': bcountry,
                         'bstate': bstate,
                         'bdistrict': bdistrict,
                         'bcity': bcity,
                         'bzip': bzip,
                         'baddress': baddress,
                         //step 3
                         'dname': dname,
                         'designation': designation,
                    },
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {

                         // console.log(response.html);
                         $('.load_view_html').html(response.html);
                         $('#show_images').prepend('<img id="theImg" class="company_logo" src="' + $("#ul_id").val() + '" />')
                         $(".foure_form").removeClass("d-none");
                         $(".third_form").addClass("d-none");
                    }
               });

          }
          return status;
     }

     function secound_form_back() {
          $(".secound_form").removeClass("d-none ");
          $(".third_form").addClass("d-none ");
     }

     function dname_1() {
          var dname = $("#dname").val()
          if (dname == '') {
               jQuery("#dname").removeClass("right");
               jQuery("#dname").next("span").removeClass("right");
               jQuery("#dname").addClass("wrong");
               jQuery("#dname").next("span").addClass("wrong");
               jQuery("#dname").css("border", "1px solid red");
               jQuery("#dname_error").css("display", "");
               jQuery("#dname_error").css("color", "red");
               jQuery("#dname_error").html('This field is required');
               return false;
          } else {
               jQuery("#dname").removeClass("wrong");
               jQuery("#dname").next("span").removeClass("wrong");
               jQuery("#dname").addClass("right");
               jQuery("#dname").next("span").addClass("right");
               jQuery("#dname").css("border", "1px solid #ccc");
               jQuery("#dname_error").css("display", "none");
               return true;
          }
     }

     function designation_1() {
          var designation = $("#designation").val()
          if (designation == '') {
               jQuery("#designation").removeClass("right");
               jQuery("#designation").next("span").removeClass("right");
               jQuery("#designation").addClass("wrong");
               jQuery("#designation").next("span").addClass("wrong");
               jQuery("#designation").css("border", "1px solid red");
               jQuery("#designation_error").css("display", "");
               jQuery("#designation_error").css("color", "red");
               jQuery("#designation_error").html('This field is required');
               return false;
          } else {
               jQuery("#designation").removeClass("wrong");
               jQuery("#designation").next("span").removeClass("wrong");
               jQuery("#designation").addClass("right");
               jQuery("#designation").next("span").addClass("right");
               jQuery("#designation").css("border", "1px solid #ccc");
               jQuery("#designation_error").css("display", "none");
               return true;
          }
     }
     //foure page
     function final_submit() {
          var input = document.getElementById('company_logo');
          var company_logo = input.files[0];

          var formData = new FormData();

          formData.append('company_logo', company_logo);
          formData.append('company_name', $("#company_name").val());
          formData.append('company_name', $("#company_name").val());
          formData.append('company_address', $("#company_address").val());
          formData.append('company_city', $("#company_city").val());
          formData.append('company_state', $("#company_state").val());
          formData.append('company_zipcode', $("#company_zipcode").val());
          formData.append('company_country', $("#company_Country").val());
          formData.append('company_telephone', $("#company_telephone").val());
          formData.append('company_phone_numbar', $("#company_phone").val());
          formData.append('system_email', $("#system_email").val());
          formData.append('company_gstin_numbar', $("#company_gst").val());
          formData.append('company_esic_numbar', $("#company_esic").val());
          formData.append('company_pf_numbar', $("#company_pf").val());
          formData.append('company_tan_numbar', $("#company_tan").val());
          formData.append('company_pec_numbar', $("#company_pec").val());
          //step 2
          formData.append('company_start_time', $("#company_starttime").val());
          formData.append('company_end_time', $("#company_endtime").val());
          formData.append('timezone', $("#company_timezone").val());
          formData.append('bname', $("#bname").val());
          formData.append('bcountry', $("#bcountry").val());
          formData.append('bstate', $("#bstate").val());
          formData.append('bdistrict', $("#bdistrict").val());
          formData.append('bcity', $("#bcity").val());
          formData.append('bzip', $("#bzip").val());
          formData.append('baddress', $("#baddress").val());
          formData.append('dname', $("#dname").val());
          formData.append('designation', $("#designation").val());
          $.ajax({
               headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               },
               url: "{{ url('finally_submit_data_company') }}",
               data: formData,
               type: 'POST',
               contentType: false,
               cache: false,
               processData: false,
               success: function(response) {
                    // console.log(response.html);

                    console.log(response.status);
                    if (response.status == 1) {
                         $(".final_submit").addClass("d-none");
                         window.location.href = "{{ route('home')}}";
                    } else {
                         $(".error_message_form").html(response.message);

                    }
               }
          });

     }

     function third_form_back() {
          $(".foure_form").addClass("d-none ");
          $(".third_form").removeClass("d-none ");
     }
</script>

</html>