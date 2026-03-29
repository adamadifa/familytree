<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\User;
use App\Models\FamilyMember;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

#[Layout('layouts.app')]
class UserManager extends Component
{
    use WithPagination;

    public $search = '';
    public $isModalOpen = false;
    
    // Form fields
    public $userId = null;
    public $name = '';
    public $email = '';
    public $password = '';
    public $role = 'member';
    public $family_member_id = null;

    public function mount()
    {
        abort_if(auth()->user()->role !== 'admin', 403);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->role = 'member';
        $this->family_member_id = null;
        $this->resetErrorBag();
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->family_member_id = $user->family_member_id;
        $this->isModalOpen = true;
    }

    public function saveUser()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->userId)],
            'role' => 'required|in:admin,member',
            'family_member_id' => 'nullable|exists:family_members,id'
        ];

        if (!$this->userId) {
            $rules['password'] = 'required|string|min:8';
        } else {
            $rules['password'] = 'nullable|string|min:8';
        }

        $validated = $this->validate($rules);

        if ($this->userId) {
            $user = User::findOrFail($this->userId);
            // Don't let the primary admin change their own role to member accidentally
            if ($user->id === auth()->id() && $validated['role'] === 'member') {
                $this->addError('role', 'Anda tidak dapat menghapus akses admin dari akun Anda sendiri.');
                return;
            }
            
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->role = $validated['role'];
            $user->family_member_id = $validated['family_member_id'];
            
            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }
            $user->save();
        } else {
            User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'family_member_id' => $validated['family_member_id'],
            ]);
        }

        $this->closeModal();
    }

    public function deleteUser($id)
    {
        if ($id == auth()->id()) {
            $this->addError('delete', 'Anda tidak dapat menghapus akun Anda sendiri.');
            return;
        }

        User::findOrFail($id)->delete();
    }

    public function render()
    {
        $query = User::with('familyMember');

        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
        }

        $users = $query->orderBy('id', 'desc')->paginate(10);
        
        // Fetch all family members for the dropdown (in reality, might want to search/exclude those already linked)
        $candidates = FamilyMember::orderBy('first_name')->get();

        return view('livewire.user-manager', [
            'users' => $users,
            'familyMembers' => $candidates
        ]);
    }
}
