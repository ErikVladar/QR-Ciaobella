<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithFileUploads;

class AdminProducts extends Component
{
    use WithFileUploads;

    public $products;
    public $categories;
    public $editingId = null;
    public $name = '';
    public $price = '';
    public $category_id = '';
    public $image = null;
    public $description = '';
    public $showForm = false;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->products = Product::with('category')->latest()->get();
        $this->categories = Category::all();
    }

    public function openCreateForm()
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingId = null;
    }

    public function openEditForm($id)
    {
        $product = Product::findOrFail($id);
        $this->editingId = $id;
        $this->name = $product->name;
        $this->price = $product->price;
        $this->category_id = $product->category_id;
        $this->description = $product->description ?? '';
        $this->image = null;
        $this->showForm = true;
    }

    public function saveProduct()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:5120',
            'description' => 'nullable|string',
        ]);

        if ($this->editingId) {
            $product = Product::findOrFail($this->editingId);
            $product->name = $this->name;
            $product->price = $this->price;
            $product->category_id = $this->category_id;
            $product->description = $this->description;

            if ($this->image) {
                $path = $this->image->store('products', 'public');
                $product->image_path = $path;
            }

            $product->save();
            session()->flash('notify', 'Produkt aktualizovaný!');
        } else {
            $imagePath = null;
            if ($this->image) {
                $imagePath = $this->image->store('products', 'public');
            }

            Product::create([
                'name' => $this->name,
                'price' => $this->price,
                'category_id' => $this->category_id,
                'image_path' => $imagePath,
                'description' => $this->description,
            ]);
            session()->flash('notify', 'Produkt vytvorený!');
        }

        $this->resetForm();
        $this->loadData();
    }

    public function deleteProduct($id)
    {
        Product::findOrFail($id)->delete();
        session()->flash('notify', 'Produkt vymazaný!');
        $this->loadData();
    }

    public function resetForm()
    {
        $this->name = '';
        $this->price = '';
        $this->category_id = '';
        $this->image = null;
        $this->description = '';
        $this->showForm = false;
        $this->editingId = null;
    }

    public function render()
    {
        return view('livewire.admin-products');
    }
}
