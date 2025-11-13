<?php

namespace App\Repositories;

use App\Models\FormSubmission;

class FormSubmissionRepository
{
    public function byForm($formId)
    {
        return FormSubmission::where('form_id', $formId);
    }

    public function find($id)
    {
        return FormSubmission::findOrFail($id);
    }

    public function create(array $data)
    {
        return FormSubmission::create($data);
    }

    public function update($id, array $data)
    {
        $s = $this->find($id);
        $s->update($data);
        return $s;
    }

    public function delete($id)
    {
        $s = $this->find($id);
        return $s->delete();
    }
}
