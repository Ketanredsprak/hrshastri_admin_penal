<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MobileMenu;
use App\Models\CompanyMobileMenu;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\Input;
use Storage;

class MobileMenuController extends Controller
{
     public int $menuPaddingIncrementValue = 25;
     public function index()
     {
          $data  = MobileMenu::orderBy("sequence_number", "ASC")->get();
          return view('MobileMenu.index', compact('data'));
     }

     public function create_mobile_menu()
     {
          $parentMenusList = ['' => trans('file.select option')] + MobileMenu::orderBy("sequence_number", "ASC")->get()->pluck("name", "id")->toArray();
          return view('MobileMenu.create', compact("parentMenusList"));
     }

     public function submit_mobile_menu(request $request)
     {
          try
          {
               DB::beginTransaction();

               $data  = new MobileMenu();
               $en_uploaded = '';

               if ($request->hasFile('icon'))
               {
                    // Get filename with the extension
                    $filenameWithExt = $request->file('icon')->getClientOriginalName();
                    //Get just filename
                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    // Get just ext
                    $extension = $request->file('icon')->getClientOriginalExtension();
                    // Filename to store
                    $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                    // Upload Image
                    $path = $request->file('icon')->storeAs('public/mobileIcon', $fileNameToStore);
                    $en_uploaded = $fileNameToStore;
               }

               $data->title      = $request->title;
               $data->name    = $request->name;
               $data->icon   = $en_uploaded;
               $data->sequence_number    = $request->sequence_number ?? 1;
               $data->parent_id    = $request->parent_id ?? 0;
               $data->save();
               DB::commit();
               return redirect()->back()->with('success', __('Mobile Menu successfully created.'));
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

     public function edit_mobile_menu($id)
     {
          $data  = MobileMenu::find($id);

          $parentMenusList = ['' => trans('file.select option')] +
               MobileMenu::
               // where("parent_id", 0)
               where("id", "!=", $data->id)
               ->orderBy("sequence_number", "ASC")->get()->pluck("name", "id")->toArray();
          return view('MobileMenu.edit', compact('data', "parentMenusList"));
     }

     public function update_mobile_menu(request $request)
     {
          try
          {
               DB::beginTransaction();
               $data  =  MobileMenu::find($request->id);
               $en_uploaded = $data->icon;

               if ($request->hasFile('icon'))
               {
                    // Get filename with the extension
                    $filenameWithExt = $request->file('icon')->getClientOriginalName();
                    //Get just filename
                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    // Get just ext
                    $extension = $request->file('icon')->getClientOriginalExtension();
                    // Filename to store
                    $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                    // Upload Image
                    $path = $request->file('icon')->storeAs('public/mobileIcon', $fileNameToStore);
                    $en_uploaded = $fileNameToStore;
               }

               $data->title    = $request->title;
               $data->name    = $request->name;
               $data->icon     = $en_uploaded;
               $data->sequence_number    = $request->sequence_number ?? 1;
               $data->parent_id    = $request->parent_id ?? 0;
               $data->save();
               DB::commit();
               return redirect()->back()->with('success', __('Mobile Menu successfully updated.'));
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

     public function delete_mobile_menu(request $request, $id)
     {
          try
          {
               DB::beginTransaction();
               $data  = MobileMenu::find($id);
               $data->delete();
               DB::commit();
               return redirect()->back()->with('success', __('Mobile Menu successfully deleted.'));
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



     public function renderSubMenu($userId, $menuData, $subMenuCount, $mainMenuId)
     {
          $menuListHtml = "";
          $user  = User::find($userId);

          foreach ($menuData as $row)
          {
               $check = CompanyMobileMenu::where('menu_id', $row->id)
                    ->where('company_id', $user->id)
                    ->first();
               if (!empty($check))
               {
                    $check = 'checked';
               }

               $menuListHtml .= '
                    <div style="padding-left:' . $subMenuCount . 'px">
                    
                        <input type="checkbox" id="menu_' . $row->id . '" name="menu_id[]" value="' . $row->id . '" ' . $check . ' class="childMenu ' . $mainMenuId . '" mainMenuId=' . $mainMenuId . '/>
                        <label for="menu_' . $row->id . '">' . $row->name . '</label>
                    </div>';

               if (count($row->SubMenu) > 0)
               {
                    $menuListHtml .= $this->renderSubMenu($userId, $row->SubMenu, ($subMenuCount + $this->menuPaddingIncrementValue), $mainMenuId);
               }
          }

          return $menuListHtml;
     }

     public function mobile_assign_menu($id)
     {
          $user  = User::find($id);

          $subMenuCount = 0;

          $data = MobileMenu::where("parent_id", 0)
               ->with("SubMenu")
               ->orderBy("sequence_number", "ASC")
               ->get();

          // return $data;

          $menuListHtml = "";
          foreach ($data as $row)
          {
               $check = CompanyMobileMenu::where('menu_id', $row->id)
                    ->where('company_id', $user->id)
                    ->first();
               if (!empty($check))
               {
                    $check = 'checked';
               }

               $menuListHtml .= '
                    <div>
                        <input type="checkbox" id="menu_' . $row->id . '" name="menu_id[]" value="' . $row->id . '" ' . $check . ' />
                        <label for="menu_' . $row->id . '">' . $row->name . '</label>
                    </div>';

               if (count($row->SubMenu) > 0)
               {
                    $subMenuCount = 0;
                    $menuListHtml .= $this->renderSubMenu($id, $row->SubMenu, ($subMenuCount + $this->menuPaddingIncrementValue), $row->id);
               }
          }

          // dd($menuListHtml);

          $selectdata  = CompanyMobileMenu::where('company_id', $id)->select('menu_id')->get();
          $selectdata_row = [];
          foreach ($selectdata as $row)
          {
               $selectdata_row[] = $row->menu_id;
          }

          return view('user.assign_mobile_icon', compact('data', 'user', 'selectdata_row', 'menuListHtml'));
     }
     public function assign_store_menu(request $request)
     {
          try
          {
               DB::beginTransaction();
               CompanyMobileMenu::where('company_id', $request->company_id)->delete();
               foreach ($request->menu_id as $row)
               {
                    $data = new CompanyMobileMenu();
                    $data->company_id = $request->company_id;
                    $data->menu_id    = $row;
                    $data->created_by = $request->company_id;
                    $data->save();
               }
               DB::commit();
               return redirect()->back()->with('success', __('Mobile Menu assign successfully'));
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
