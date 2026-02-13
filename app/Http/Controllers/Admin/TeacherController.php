<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = User::where('role', 'teacher')
            ->orderBy('name')
            ->get();

        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('admin.teachers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'phone' => ['required','string','max:20','unique:users,phone'],
            'email' => ['nullable','email','max:255','unique:users,email'],
            'telegram_chat_id' => ['nullable','string','max:50','unique:users,telegram_chat_id'],
            'password' => ['required','string','min:6'],
        ]);

        $data['email'] = $data['email'] ?: null;
        $data['telegram_chat_id'] = $data['telegram_chat_id'] ?: null;
        $data['password'] = Hash::make($data['password']);
        $data['role'] = 'teacher';

        User::create($data);

        return redirect()
            ->route('admin.teachers.index')
            ->with('success', 'Teacher created successfully.');
    }

    public function edit(User $teacher)
    {
        if (!$teacher->isTeacher()) {
            abort(404);
        }

        return view('admin.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, User $teacher)
    {
        if (!$teacher->isTeacher()) {
            abort(404);
        }

        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'phone' => ['required','string','max:20', Rule::unique('users', 'phone')->ignore($teacher->id)],
            'email' => ['nullable','email','max:255', Rule::unique('users', 'email')->ignore($teacher->id)],
            'telegram_chat_id' => ['nullable','string','max:50', Rule::unique('users', 'telegram_chat_id')->ignore($teacher->id)],
            'password' => ['nullable','string','min:6'],
        ]);

        $data['email'] = $data['email'] ?: null;
        $data['telegram_chat_id'] = $data['telegram_chat_id'] ?: null;
        $data['role'] = 'teacher';

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $teacher->update($data);

        return redirect()
            ->route('admin.teachers.index')
            ->with('success', 'Teacher updated successfully.');
    }

    public function destroy(User $teacher)
    {
        if (!$teacher->isTeacher()) {
            abort(404);
        }

        $teacher->delete();

        return redirect()
            ->route('admin.teachers.index')
            ->with('success', 'Teacher deleted successfully.');
    }
}
