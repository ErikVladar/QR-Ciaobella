<?php

namespace App\Livewire;

use App\Models\PizzaAddition;
use Livewire\Component;

class AdminAdditions extends Component
{
    public $additions;
    public $editingId = null;
    public $name = '';
    public $price = '';
    public $showForm = false;

    public function mount(): void
    {
        $this->loadAdditions();
    }

    public function loadAdditions(): void
    {
        $this->additions = PizzaAddition::query()
            ->orderBy('name')
            ->get();
    }

    public function openCreateForm(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function openEditForm(int $id): void
    {
        $addition = PizzaAddition::findOrFail($id);

        $this->editingId = $id;
        $this->name = $addition->name;
        $this->price = (string) $addition->price;
        $this->showForm = true;
    }

    public function saveAddition(): void
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        if ($this->editingId) {
            $addition = PizzaAddition::findOrFail($this->editingId);
            $addition->update($validated);
            session()->flash('notify', 'Príloha aktualizovaná!');
        } else {
            PizzaAddition::create($validated);
            session()->flash('notify', 'Príloha vytvorená!');
        }

        $this->resetForm();
        $this->loadAdditions();
    }

    public function deleteAddition(int $id): void
    {
        PizzaAddition::findOrFail($id)->delete();
        session()->flash('notify', 'Príloha vymazaná!');
        $this->loadAdditions();
    }

    public function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->price = '';
        $this->showForm = false;
    }

    public function render()
    {
        return view('livewire.admin-additions');
    }
}
