<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoriesType;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class CategoriesTypesController extends Controller
{

    public function __construct()
    {
        $this->middleware(
            [
                'auth',
                'XSS',
            ]
        );
    }

    public function index()
    {
        $categoriesTypes = CategoriesType::where('created_by', '=', Auth::user()->creatorId())->orderBy('id','desc')->get();
        return view('categoryType.index',compact('categoriesTypes'));
    }

    public function create()
    {
        return view('categoryType.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->route('grade_type.index')->with('error', $messages->first());
        }
        try {
            DB::beginTransaction();

            $data = new CategoriesType();
            $data->name = $request->name;
            $data->created_by = Auth::user()->creatorId();
            $data->save();

            DB::commit();
            return redirect()->route('category_type.index')->with('success', __('Reimbursement Expense successfully created!'));
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->route('category_type.index')->with('error', $ex->getMessage());
        } catch (Throwable $ex) {
            DB::rollBack();
            return redirect()->route('category_type.index')->with('error', $ex->getMessage());
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $data = CategoriesType::find($id);
        return view('categoryType.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->route('category_type.index')->with('error', $messages->first());
        }
        try {
            DB::beginTransaction();

            $data = CategoriesType::find($id);
            $data->name = $request->name;
            $data->update();

            DB::commit();
            return redirect()->route('category_type.index')->with('success', __('Reimbursement Expense successfully updated!'));
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->route('category_type.index')->with('error', $ex->getMessage());
        } catch (Throwable $ex) {
            DB::rollBack();
            return redirect()->route('category_type.index')->with('error', $ex->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $data = CategoriesType::find($id)->delete();

            DB::commit();
            return redirect()->route('category_type.index')->with('success', __('Reimbursement Expense successfully deleted!'));
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->route('category_type.index')->with('error', $ex->getMessage());
        } catch (Throwable $ex) {
            DB::rollBack();
            return redirect()->route('category_type.index')->with('error', $ex->getMessage());
        }
    }
}
