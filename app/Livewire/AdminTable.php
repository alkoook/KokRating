<?php
namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class AdminTable extends Component
{
use WithPagination;

public $editing = null;
public $editingName = '';
public $editingEmail = '';
public $searchTerm = '';

public function mount()
{
$this->resetEditing();
}

public function edit($adminId)
{
$admin = User::find($adminId);

if ($admin) {
$this->editing = $admin->id;
$this->editingName = $admin->name;
$this->editingEmail = $admin->email;
}
}

public function save($adminId)
{
$admin = User::find($adminId);

if ($admin) {
$admin->update([
'name' => $this->editingName,
'email' => $this->editingEmail,
]);
}

$this->resetEditing();
}

public function cancel()
{
$this->resetEditing();
}

private function resetEditing()
{
$this->editing = null;
$this->editingName = '';
$this->editingEmail = '';
}

public function updatedSearchTerm()
{
$this->resetPage();
}

public function render()
{
return view('livewire.admin-table', [
'admins' => User::where('email', 'like', '%' . $this->searchTerm . '%')->paginate(10)
]);
}
}