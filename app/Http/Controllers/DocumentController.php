<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use App\Models\DeductionOption;
use Illuminate\Support\Facades\Auth;
use DataTables;
use Form;
use Illuminate\Support\Facades\Crypt;

class DocumentController extends Controller
{
     public function index(Request $request)
     {

          if (Auth::user()->can('Manage Document Type'))
          {
               $documents = Document::where('created_by', '=', Auth::user()->creatorId());
               if ($request->ajax())
               {
                    return DataTables::of($documents)
                         ->addIndexColumn()  // adds the "No" column
                         ->addColumn('required_field', function ($row)
                         {
                              return $row->is_required == 1
                                   ? '<div class="badge bg-success p-2 px-3 rounded status-badge7">' . __('Required') . '</div>'
                                   : '<div class="badge bg-danger p-2 px-3 rounded status-badge7">' . __('Not Required') . '</div>';
                         })
                         ->addColumn('action', function ($row)
                         {
                              $btn = '<span>';
                              if (Auth::user()->can('Edit Document Type'))
                              {
                                   $btn .= '<div class="action-btn bg-info ms-2">'
                                        . '<a href="#" class="btn btn-sm align-items-center" '
                                        . 'data-url="' . url('document/' . $row->id . '/edit') . '" '
                                        . 'data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" '
                                        . 'title="' . __('Edit') . '" data-title="' . __('Edit Document Type') . '">'
                                        . '<i class="ti ti-pencil text-white"></i>'
                                        . '</a>'
                                        . '</div>';
                              }
                              if (Auth::user()->can('Delete Document Type'))
                              {
                                   $btn .= '<div class="action-btn bg-danger ms-2">'
                                        . '<form method="POST" action="' . route('document.destroy', $row->id) . '" style="display:inline;">'
                                        . csrf_field()
                                        . method_field('DELETE')
                                        . '<button type="submit" class="btn btn-sm align-items-center bs‑pass‑para" '
                                        . 'title="' . __('Delete') . '">'
                                        . '<i class="ti ti-trash text-white"></i>'
                                        . '</button>'
                                        . '</form>'
                                        . '</div>';
                              }
                              $btn .= '</span>';
                              return $btn;
                         })
                         ->rawColumns(['required_field', 'action'])
                         ->make(true);
               }
               else
               {
                    $documents = $documents->get();
                    return view('document.index', compact('documents'));
               }
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }

     public function create()
     {
          if (\Auth::user()->can('Create Document Type'))
          {
               return view('document.create');
          }
          else
          {
               return response()->json(['error' => __('Permission denied.')], 401);
          }
     }

     public function store(Request $request)
     {
          if (\Auth::user()->can('Create Document Type'))
          {
               $validator = \Validator::make(
                    $request->all(),
                    [
                         // 'name' => 'required|max:20',
                         'name' => 'required',
                         'is_required' => 'required',
                    ]
               );
               if ($validator->fails())
               {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
               }

               $document              = new Document();
               $document->name        = $request->name;
               $document->is_required = $request->is_required;
               $document->created_by  = \Auth::user()->creatorId();
               $document->save();

               return redirect()->route('document.index')->with('success', __('Document type successfully created.'));
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }

     public function show(Document $document)
     {
          return redirect()->route('document.index');
     }

     public function edit(Document $document)
     {
          if (\Auth::user()->can('Edit Document Type'))
          {
               if ($document->created_by == \Auth::user()->creatorId())
               {

                    return view('document.edit', compact('document'));
               }
               else
               {
                    return response()->json(['error' => __('Permission denied.')], 401);
               }
          }
          else
          {
               return response()->json(['error' => __('Permission denied.')], 401);
          }
     }

     public function update(Request $request, Document $document)
     {

          if (\Auth::user()->can('Edit Document Type'))
          {
               if ($document->created_by == \Auth::user()->creatorId())
               {
                    $validator = \Validator::make(
                         $request->all(),
                         [
                              // 'name' => 'required|max:20',
                              'name' => 'required',
                         ]
                    );
                    if ($validator->fails())
                    {
                         $messages = $validator->getMessageBag();

                         return redirect()->back()->with('error', $messages->first());
                    }


                    $document->name        = $request->name;
                    $document->is_required = $request->is_required;
                    $document->save();

                    return redirect()->route('document.index')->with('success', __('Document type successfully updated.'));
               }
               else
               {
                    return redirect()->back()->with('error', __('Permission denied.'));
               }
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }

     public function destroy(Document $document)
     {
          if (\Auth::user()->can('Delete Document Type'))
          {
               if ($document->created_by == \Auth::user()->creatorId())
               {
                    $document->delete();

                    return redirect()->route('document.index')->with('success', __('Document type successfully deleted.'));
               }
               else
               {
                    return redirect()->back()->with('error', __('Permission denied.'));
               }
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }
}
