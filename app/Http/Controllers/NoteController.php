<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Models\Note;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NoteController extends Controller
{


    /**
     * @OA\Get(
     *     path="/api/notes",
     *     summary="Get all notes untuk user yang login",
     *     tags={"Notes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of notes",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Daftar catatan berhasil diambil"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="notes", type="array", @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Catatan Pertama Saya"),
     *                     @OA\Property(property="content", type="string", example="Ini adalah isi catatan pertama saya"),
     *                     @OA\Property(property="user_id", type="integer", example=1),
     *                     @OA\Property(property="created_at", type="string", example="2025-08-24T10:10:00.000000Z")
     *                 )),
     *                 @OA\Property(property="total", type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Token tidak valid")
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $notes = auth()->user()->notes()->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar catatan berhasil diambil',
            'data' => [
                'notes' => $notes,
                'total' => $notes->count(),
            ],
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/notes",
     *     summary="Create note baru",
     *     tags={"Notes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","content"},
     *             @OA\Property(property="title", type="string", example="Catatan Pertama Saya"),
     *             @OA\Property(property="content", type="string", example="Ini adalah isi dari catatan pertama saya untuk test Backend Developer di Gencidev")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Note created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Catatan berhasil dibuat"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="note", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Catatan Pertama Saya"),
     *                     @OA\Property(property="content", type="string", example="Ini adalah isi dari catatan pertama saya"),
     *                     @OA\Property(property="user_id", type="integer", example=1),
     *                     @OA\Property(property="created_at", type="string", example="2025-08-24T10:10:00.000000Z")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Data yang dikirim tidak valid"),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="title", type="array", @OA\Items(type="string", example="Judul catatan wajib diisi"))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Gagal membuat catatan")
     *         )
     *     )
     * )
     */
    public function store(StoreNoteRequest $request): JsonResponse
    {
        try {
            $note = auth()->user()->notes()->create([
                'title' => $request->title,
                'content' => $request->content,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Catatan berhasil dibuat',
                'data' => [
                    'note' => $note,
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat catatan',
                'errors' => [
                    'server' => ['Terjadi kesalahan pada server']
                ]
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/notes/{id}",
     *     summary="Get specific note",
     *     tags={"Notes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Note retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Detail catatan berhasil diambil"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="note", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Catatan Pertama Saya"),
     *                     @OA\Property(property="content", type="string", example="Ini adalah isi dari catatan pertama saya"),
     *                     @OA\Property(property="user_id", type="integer", example=1),
     *                     @OA\Property(property="created_at", type="string", example="2025-08-24T10:10:00.000000Z")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Note not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Catatan tidak ditemukan"),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="note", type="array", @OA\Items(type="string", example="Catatan dengan ID tersebut tidak ditemukan atau bukan milik Anda"))
     *             )
     *         )
     *     )
     * )
     */
    public function show(string $id): JsonResponse
    {
        $note = auth()->user()->notes()->find($id);

        if (!$note) {
            return response()->json([
                'success' => false,
                'message' => 'Catatan tidak ditemukan',
                'errors' => [
                    'note' => ['Catatan dengan ID tersebut tidak ditemukan atau bukan milik Anda']
                ]
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail catatan berhasil diambil',
            'data' => [
                'note' => $note,
            ],
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/notes/{id}",
     *     summary="Update note",
     *     tags={"Notes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Judul Catatan yang Diperbarui"),
     *             @OA\Property(property="content", type="string", example="Ini adalah isi catatan yang telah diperbarui menggunakan endpoint PUT")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Note updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Catatan berhasil diperbarui"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="note", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Judul Catatan yang Diperbarui"),
     *                     @OA\Property(property="content", type="string", example="Ini adalah isi catatan yang telah diperbarui"),
     *                     @OA\Property(property="user_id", type="integer", example=1),
     *                     @OA\Property(property="updated_at", type="string", example="2025-08-24T10:15:00.000000Z")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Note not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Catatan tidak ditemukan")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Data yang dikirim tidak valid")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Gagal memperbarui catatan")
     *         )
     *     )
     * )
     */
    public function update(UpdateNoteRequest $request, string $id): JsonResponse
    {
        $note = auth()->user()->notes()->find($id);

        if (!$note) {
            return response()->json([
                'success' => false,
                'message' => 'Catatan tidak ditemukan',
                'errors' => [
                    'note' => ['Catatan dengan ID tersebut tidak ditemukan atau bukan milik Anda']
                ]
            ], 404);
        }

        try {
            $note->update($request->only(['title', 'content']));

            return response()->json([
                'success' => true,
                'message' => 'Catatan berhasil diperbarui',
                'data' => [
                    'note' => $note->fresh(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui catatan',
                'errors' => [
                    'server' => ['Terjadi kesalahan pada server']
                ]
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/notes/{id}",
     *     summary="Delete note",
     *     tags={"Notes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Note deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Catatan berhasil dihapus")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Note not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Catatan tidak ditemukan"),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="note", type="array", @OA\Items(type="string", example="Catatan dengan ID tersebut tidak ditemukan atau bukan milik Anda"))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Gagal menghapus catatan")
     *         )
     *     )
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $note = auth()->user()->notes()->find($id);

        if (!$note) {
            return response()->json([
                'success' => false,
                'message' => 'Catatan tidak ditemukan',
                'errors' => [
                    'note' => ['Catatan dengan ID tersebut tidak ditemukan atau bukan milik Anda']
                ]
            ], 404);
        }

        try {
            $note->delete();

            return response()->json([
                'success' => true,
                'message' => 'Catatan berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus catatan',
                'errors' => [
                    'server' => ['Terjadi kesalahan pada server']
                ]
            ], 500);
        }
    }
}