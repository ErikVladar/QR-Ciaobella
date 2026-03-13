<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithFileUploads;

class AdminCategories extends Component
{
    use WithFileUploads;

    public $categories;
    public $editingId = null;
    public $name = '';
    public $image = null;
    public $hasPrilohy = false;
    public $showForm = false;

    public function mount()
    {
        $this->loadCategories();
    }

    public function loadCategories()
    {
        $this->categories = Category::query()->latest()->get();
    }

    public function openCreateForm()
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingId = null;
    }

    public function openEditForm($id)
    {
        $category = Category::findOrFail($id);
        $this->editingId = $id;
        $this->name = $category->name;
        $this->hasPrilohy = $category->has_prilohy;
        $this->image = null;
        $this->showForm = true;
    }

    public function saveCategory()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:5120',
            'hasPrilohy' => 'boolean',
        ]);

        if ($this->editingId) {
            $category = Category::findOrFail($this->editingId);
            $category->name = $this->name;
            $category->has_prilohy = $this->hasPrilohy;

            if ($this->image) {
                $path = $this->image->store('categories', 'public');
                $category->image_path = $path;
            }

            $category->save();
            session()->flash('notify', 'Kategória aktualizovaná!');
        } else {
            $imagePath = null;
            if ($this->image) {
                $imagePath = $this->image->store('categories', 'public');
            }

            Category::create([
                'name' => $this->name,
                'image_path' => $imagePath,
                'has_prilohy' => $this->hasPrilohy,
            ]);
            session()->flash('notify', 'Kategória vytvorená!');
        }

        $this->resetForm();
        $this->loadCategories();
    }

    public function deleteCategory($id)
    {
        Category::findOrFail($id)->delete();
        session()->flash('notify', 'Kategória vymazaná!');
        $this->loadCategories();
    }

    public function resetForm()
    {
        $this->name = '';
        $this->image = null;
        $this->hasPrilohy = false;
        $this->showForm = false;
        $this->editingId = null;
    }

    public function render()
    {
        return view('livewire.admin-categories');
    }
}
