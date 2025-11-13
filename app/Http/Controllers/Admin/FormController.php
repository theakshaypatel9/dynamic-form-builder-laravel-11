<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\FormSubmission;
use Illuminate\Http\Request;
use App\Repositories\FormRepository;
use App\Repositories\FormSubmissionRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class FormController extends Controller
{
    protected $forms;
    protected $subs;

    public function __construct(FormRepository $forms, FormSubmissionRepository $subs)
    {
        $this->forms = $forms;
        $this->subs = $subs;
    }

    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $query = $this->forms->all()->select(['id', 'title', 'created_by']);
                return DataTables::of($query)
                    ->addColumn('actions', function ($row) {
                        return view('admin.forms.partials.actions', compact('row'))->render();
                    })
                    ->rawColumns(['actions'])
                    ->make(true);
            }
            return view('admin.forms.index');
        } catch (\Throwable $th) {
            dd($th);
            return view('admin.forms.index');
        }
    }

    public function submissionIndex(Form $form)
    {
        try {
            return view('admin.forms.submissions', compact('form'));
        } catch (\Throwable $th) {
            dd($th);
            return view('admin.forms.submissions', compact('form'));
        }
    }

    public function create()
    {
        try {
            return view('admin.forms.create');
        } catch (\Throwable $th) {
            dd($th);
            return view('admin.forms.create');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'config' => 'required|array'
        ]);

        try {
            DB::beginTransaction();
            $data = [
                'title' => $request->title,
                'description' => $request->description,
                'config' => $request->config,
                'created_by' => Auth::user()->id,
            ];
            $form = $this->forms->create($data);
            DB::commit();
            return response()->json(['success' => true, 'form' => $form]);
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th);
            return response()->json(['error' => true]);
        }
    }

    public function show($id)
    {
        try {
            $form = $this->forms->find($id);
            return view('admin.forms.show', compact('form'));
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function edit($id)
    {
        try {
            $form = $this->forms->find($id);
            return view('admin.forms.edit', compact('form'));
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'config' => 'required|array'
        ]);

        try {
            DB::beginTransaction();
            $data = [
                'title' => $request->title,
                'description' => $request->description,
                'config' => $request->config,
            ];
            // dd('update', $data, $id, $request->all());
            $this->forms->update($id, $data);
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th);
            return response()->json(['success' => false]);
        }
    }

    public function destroy($id)
    {
        try {
            $this->forms->delete($id);
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            dd($th);
            return response()->json(['error' => true]);
        }
    }

    public function submissionsDataTable(Request $request, $formId)
    {
        try {
            $query = $this->subs->byForm($formId)->select(['id', 'user_id', 'created_at', 'data']);
            return DataTables::of($query)
                ->addColumn('actions', function ($row) use ($formId) {
                    return view('admin.forms.partials.sub_actions', [
                        'row' => $row,
                        'formId' => $formId
                    ])->render();
                })
                ->editColumn('data', function ($row) {
                    // show small preview
                    return '<pre style="">' . json_encode($row->data, JSON_PRETTY_PRINT) . '</pre>';
                })
                ->rawColumns(['data', 'actions'])
                ->make(true);
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function submissionShow($formId, $submissionId)
    {
        try {
            $form = Form::findOrFail($formId);
            $submission = FormSubmission::findOrFail($submissionId);
            return view('admin.forms.submissions.show', compact('form', 'submission'));
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function submissionDestroy($formId, $submissionId)
    {
        try {
            $submission = FormSubmission::findOrFail($submissionId);
            $submission->delete();
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            dd($th);
            return response()->json(['error' => true]);
        }
    }
}
