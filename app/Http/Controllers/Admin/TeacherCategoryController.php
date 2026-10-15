<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeacherCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Illuminate\Validation\Rule;

class TeacherCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:admin.teacher_categories.view')->only(['index']);
        $this->middleware('permission:admin.teacher_categories.create')->only(['create', 'store']);
        $this->middleware('permission:admin.teacher_categories.edit')->only(['edit', 'update']);
        $this->middleware('permission:admin.teacher_categories.delete')->only(['destroy']);
    }

    public function index()
    {
        $categories = TeacherCategory::orderBy('order')->get();
        return view('admin.teacher_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.teacher_categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:teacher_categories,name',
            'order' => 'nullable|integer',
        ], [
            'name.unique' => 'Nama kategori sudah digunakan.',
        ]);

        $data['slug'] = Str::slug($data['name']);
        
        TeacherCategory::create($data);
        $this->forgetPublicCaches('teachers');

        return redirect()->route('admin.teacher-categories.index')->with('success', 'Kategori guru berhasil ditambahkan');
    }

    public function edit(TeacherCategory $teacherCategory)
    {
        return view('admin.teacher_categories.edit', compact('teacherCategory'));
    }

    public function update(Request $request, TeacherCategory $teacherCategory)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('teacher_categories', 'name')->ignore($teacherCategory->id)],
            'order' => 'nullable|integer',
        ], [
            'name.unique' => 'Nama kategori sudah digunakan.',
        ]);

        $data['slug'] = Str::slug($data['name']);
        
        $teacherCategory->update($data);
        $this->forgetPublicCaches('teachers');

        return redirect()->route('admin.teacher-categories.index')->with('success', 'Kategori guru berhasil diperbarui');
    }

    public function destroy(TeacherCategory $teacherCategory)
    {
        $teacherCategory->delete();
        $this->forgetPublicCaches('teachers');
        return redirect()->back()->with('success', 'Kategori guru berhasil dihapus');
    }
}
