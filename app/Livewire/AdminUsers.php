<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AdminUsers extends Component
{
    public $users;
    public $editingId = null;
    public $name = '';
    public $email = '';
    public $role = 'kitchen';
    public $password = '';
    public $showForm = false;
    public $roles = ['admin', 'kitchen', 'waiter'];

    public function mount()
    {
        $this->loadUsers();
    }

    public function loadUsers()
    {
        $this->users = User::query()
            ->orderByRaw("CASE role
                WHEN 'admin' THEN 1
                WHEN 'kitchen' THEN 2
                WHEN 'waiter' THEN 3
                ELSE 4
            END")
            ->orderBy('name')
            ->get();
    }

    public function openCreateForm()
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingId = null;
    }

    public function openEditForm($id)
    {
        $user = User::findOrFail($id);
        $this->editingId = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->password = '';
        $this->showForm = true;
    }

    public function saveUser()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . ($this->editingId ?? 'NULL'),
            'role' => 'required|in:admin,kitchen,waiter',
        ];

        if (!$this->editingId) {
            $rules['password'] = 'required|string|min:8';
        } elseif ($this->password) {
            $rules['password'] = 'string|min:8';
        }

        $this->validate($rules);

        if ($this->editingId) {
            $user = User::findOrFail($this->editingId);
            $user->name = $this->name;
            $user->email = $this->email;
            $user->role = $this->role;

            if ($this->password) {
                $user->password = bcrypt($this->password);
            }

            $user->save();
            session()->flash('notify', 'Používateľ aktualizovaný!');
        } else {
            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'role' => $this->role,
                'password' => bcrypt($this->password),
            ]);
            session()->flash('notify', 'Používateľ vytvorený!');
        }

        $this->resetForm();
        $this->loadUsers();
    }

    public function deleteUser($id)
    {
        // Don't allow deleting the current user
        if ((int) $id === (int) Auth::id()) {
            session()->flash('notify', 'Nemôžete vymazať svoj vlastný účet!');
            return;
        }

        User::findOrFail($id)->delete();
        session()->flash('notify', 'Používateľ vymazaný!');
        $this->loadUsers();
    }

    public function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->role = 'kitchen';
        $this->password = '';
        $this->showForm = false;
        $this->editingId = null;
    }

    public function render()
    {
        return view('livewire.admin-users');
    }
}
