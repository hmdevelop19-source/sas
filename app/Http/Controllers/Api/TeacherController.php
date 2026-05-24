<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $query = Teacher::query()->with(['village', 'education']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        $teachers = $query->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => $teachers
        ]);
    }

    public function show($id)
    {
        $teacher = Teacher::with(['village.district.regency.province', 'education'])->find($id);

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Data guru tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $teacher
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|string|max:16|unique:teachers,nik',
            'nip' => 'nullable|string|max:255|unique:teachers,nip',
            'gender' => 'required|in:L,P',
            'place_of_birth' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'blood_type' => 'nullable|string|max:5',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
        ]);

        $teacher = Teacher::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data guru berhasil ditambahkan.',
            'data' => $teacher
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $teacher = Teacher::find($id);

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Data guru tidak ditemukan.'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|string|max:16|unique:teachers,nik,' . $id,
            'nip' => 'nullable|string|max:255|unique:teachers,nip,' . $id,
            'gender' => 'required|in:L,P',
            'place_of_birth' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'blood_type' => 'nullable|string|max:5',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
        ]);

        $teacher->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data guru berhasil diperbarui.',
            'data' => $teacher
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'data' => 'required|array',
        ]);

        $imported = 0;
        $failed = 0;

        foreach ($request->data as $item) {
            if (empty($item['name']) || empty($item['nik'])) {
                $failed++;
                continue;
            }

            $gender = 'L';
            if (isset($item['gender']) && strtoupper(substr($item['gender'], 0, 1)) === 'P') {
                $gender = 'P';
            }

            try {
                Teacher::firstOrCreate(
                    ['nik' => $item['nik']],
                    [
                        'name' => $item['name'],
                        'nip' => $item['nip'] ?? null,
                        'gender' => $gender,
                        'place_of_birth' => $item['place_of_birth'] ?? null,
                        'date_of_birth' => $item['date_of_birth'] ?? null,
                        'address' => $item['address'] ?? null,
                        'phone' => $item['phone'] ?? null,
                        'blood_type' => $item['blood_type'] ?? null,
                    ]
                );
                $imported++;
            } catch (\Exception $e) {
                $failed++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Berhasil mengimpor {$imported} guru. Gagal/Dilewati: {$failed} guru.",
        ]);
    }
}
