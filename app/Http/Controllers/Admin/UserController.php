<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\User\UserService;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(protected UserService $userService) {}

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'role', 'is_active']);
        $users   = $this->userService->getAllUsers($filters, 10);
        return view('admin.users.index', compact('users', 'filters'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|min:8|confirmed',
            'role'                  => 'required|in:Admin,Manajer Gudang,Staff Gudang',
            'is_active'             => 'boolean',
        ]);

        if ($request->boolean('is_active')) {
            $validated['email_verified_at'] = now();
        } else {
            $validated['email_verified_at'] = null;
        }
        unset($validated['is_active']);

        try {
            $user = $this->userService->createUser($validated);
            return redirect()->route('admin.users.index')
                ->with('success', "User <strong>{$user->name}</strong> berhasil dibuat.");
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function edit(int $id)
    {
        $user = $this->userService->getUser($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => "required|email|unique:users,email,{$id}",
            'password' => 'nullable|min:8|confirmed',
            'role'     => 'required|in:Admin,Manajer Gudang,Staff Gudang',
            'is_active'=> 'boolean',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        if ($request->boolean('is_active')) {
            $validated['email_verified_at'] = now();
        } else {
            $validated['email_verified_at'] = null;
        }
        unset($validated['is_active']);

        try {
            $user = $this->userService->updateUser($id, $validated);
            return redirect()->route('admin.users.index')
                ->with('success', "User <strong>{$user->name}</strong> berhasil diperbarui.");
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->userService->deleteUser($id);
            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function toggleStatus(int $id)
    {
        try {
            $isActive = $this->userService->toggleStatus($id);
            $status   = $isActive ? 'diaktifkan' : 'dinonaktifkan';
            return back()->with('success', "User berhasil {$status}.");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
