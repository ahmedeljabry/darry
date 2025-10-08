<?php
declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Owner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\DataTables\OwnersDataTable;
use Illuminate\Support\Facades\Hash;

class OwnersController extends Controller
{
    public function index(OwnersDataTable $dataTable)
    {
        return $dataTable->render('admin.owners.index');
    }

    public function create(): View
    {
        return view('admin.owners.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'full_name' => ['required','string','max:255'],
            'id_or_cr' => ['nullable','string','max:190'],
            'email' => ['nullable','email','max:255'],
            'password' => ['nullable','string','min:6','max:255'],
            'phone' => ['nullable','string','max:50'],
            'address' => ['nullable','string','max:500'],
            'owner_type' => ['nullable','in:PERSONAL,COMMERCIAL'],
            'status' => ['required','in:ACTIVE,INACTIVE'],
        ]);
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        Owner::create($data);
        return redirect()->route('admin.owners.index')->with('status', __('messages.success_created'));
    }

    public function edit(Owner $owner): View
    {
        return view('admin.owners.edit', compact('owner'));
    }

    public function update(Request $request, Owner $owner): RedirectResponse
    {
        $data = $request->validate([
            'full_name' => ['required','string','max:255'],
            'id_or_cr' => ['nullable','string','max:190'],
            'email' => ['nullable','email','max:255'],
            'password' => ['nullable','string','min:6','max:255'],
            'phone' => ['nullable','string','max:50'],
            'address' => ['nullable','string','max:500'],
            'owner_type' => ['nullable','in:PERSONAL,COMMERCIAL'],
            'status' => ['required','in:ACTIVE,INACTIVE'],
        ]);
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $owner->update($data);
        return redirect()->route('admin.owners.index')->with('status', __('messages.success_updated'));
    }

    public function destroy(Owner $owner): RedirectResponse
    {
        $owner->delete();
        return redirect()->route('admin.owners.index')->with('status', __('messages.success_deleted'));
    }
}

