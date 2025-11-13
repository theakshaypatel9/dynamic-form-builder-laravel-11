<?php

namespace App\Repositories;

use App\Models\Form;

class FormRepository
{
    public function all()
    {
        return Form::query();
    }

    public function find($id)
    {
        return Form::findOrFail($id);
    }

    public function create(array $data)
    {
        return Form::create($data);
    }

    public function update($id, array $data)
    {
        $form = $this->find($id);
        $form->update($data);
        return $form;
    }

    public function delete($id)
    {
        $form = $this->find($id);
        return $form->delete();
    }
}
