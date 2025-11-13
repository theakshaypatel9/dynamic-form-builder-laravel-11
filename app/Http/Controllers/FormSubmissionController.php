<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Form;
use Illuminate\Http\Request;
use App\Repositories\FormRepository;
use App\Repositories\FormSubmissionRepository;
use Illuminate\Container\Attributes\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;
use Yajra\DataTables\DataTables;

class FormSubmissionController extends Controller
{
    protected $forms;
    protected $subs;

    public function __construct(FormRepository $forms, FormSubmissionRepository $subs)
    {
        // $this->middleware('auth');
        $this->forms = $forms;
        $this->subs = $subs;
    }

    public function index()
    {
        try {
            $forms = Form::latest()->get();
            return view('user.forms.index', compact('forms'));
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $form = $this->forms->find($id);
            return view('user.forms.show', compact('form'));
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }

    public function store(Request $request, $formId)
    {
        DB::beginTransaction();
        try {
            $form = $this->forms->find($formId);
            $config = $form->config ?? [];
            $data = $request->all();

            $data = $request->except('_token');

            // dd('store', $request->all(), $formId);
            $submission = $this->subs->create([
                'form_id' => $form->id,
                'user_id' => Auth::user()->id,
                'data' => $data,
            ]);

            DB::commit();
            return redirect()->route('user.forms.index')->with('success', 'Form submitted successfully!');
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th->getMessage());
            return redirect()->route('user.forms.index')->with('error', 'Form submission failed!');
        }
    }

    public function edit($id)
    {
        try {
            $submission = $this->subs->find($id);
            $this->authorize('update', $submission);
            return view('forms.edit_submission', compact('submission'));
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $submission = $this->subs->find($id);
            $this->authorize('update', $submission);
            $submission = $this->subs->update($id, [
                'data' => $request->all(),
            ]);
            DB::commit();
            return response()->json(['success' => true, 'submission' => $submission]);
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th->getMessage());
            return response()->json(['error' => true]);
        }
    }
}
