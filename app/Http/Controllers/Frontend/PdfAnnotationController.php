<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PdfAnnotation;
use Illuminate\Http\JsonResponse;

class PdfAnnotationController extends Controller
{
    public function save(Request $request): JsonResponse
    {
        $data      = $request->json()->all();
        $action    = $data['action']    ?? '';
        $lessonId  = (int)($data['lesson_id']  ?? 0);
        $courseId  = (int)($data['course_id']  ?? 0);
        $userId    = Auth::id();

        if (!$userId || !$lessonId) {
            return response()->json(['success' => false, 'error' => 'Unauthenticated or missing lesson'], 403);
        }

        switch ($action) {

            case 'highlight':
                PdfAnnotation::updateOrCreate(
                    [
                        'user_id'   => $userId,
                        'lesson_id' => $lessonId,
                        'type'      => 'highlight',
                        'annot_id'  => (int)$data['id'],
                    ],
                    [
                        'course_id' => $courseId,
                        'page_num'  => (int)($data['pageNum'] ?? 1),
                        'rects'     => json_encode($data['rects'] ?? []),
                        'text'      => substr($data['text'] ?? '', 0, 2000),
                        'color'     => preg_replace('/[^a-z]/', '', $data['color'] ?? 'yellow'),
                    ]
                );
                break;

            case 'delete_highlight':
                PdfAnnotation::where([
                    'user_id'   => $userId,
                    'lesson_id' => $lessonId,
                    'type'      => 'highlight',
                    'annot_id'  => (int)($data['id'] ?? 0),
                ])->delete();
                break;

            case 'comment':
                PdfAnnotation::updateOrCreate(
                    [
                        'user_id'   => $userId,
                        'lesson_id' => $lessonId,
                        'type'      => 'comment',
                        'annot_id'  => (int)$data['id'],
                    ],
                    [
                        'course_id' => $courseId,
                        'page_num'  => (int)($data['pageNum'] ?? 1),
                        'pos_x'     => (float)($data['x'] ?? 0),
                        'pos_y'     => (float)($data['y'] ?? 0),
                        'text'      => substr($data['text'] ?? '', 0, 5000),
                    ]
                );
                break;

            case 'update_comment':
                PdfAnnotation::where([
                    'user_id'   => $userId,
                    'lesson_id' => $lessonId,
                    'type'      => 'comment',
                    'annot_id'  => (int)($data['id'] ?? 0),
                ])->update(['text' => substr($data['text'] ?? '', 0, 5000)]);
                break;

            case 'delete_comment':
                PdfAnnotation::where([
                    'user_id'   => $userId,
                    'lesson_id' => $lessonId,
                    'type'      => 'comment',
                    'annot_id'  => (int)($data['id'] ?? 0),
                ])->delete();
                break;

            default:
                return response()->json(['success' => false, 'error' => 'Unknown action']);
        }

        return response()->json(['success' => true, 'action' => $action]);
    }

    // ── Load (all annotations for current user + lesson) ─────────
    public function load(Request $request): JsonResponse
    {
        $lessonId = (int)$request->get('lesson_id', 0);
        $userId   = Auth::id();

        if (!$userId || !$lessonId) {
            return response()->json(['success' => false, 'error' => 'Unauthenticated'], 403);
        }

        $rows = PdfAnnotation::where('user_id', $userId)
            ->where('lesson_id', $lessonId)
            ->get();

        $highlights = [];
        $comments   = [];

        foreach ($rows as $row) {
            if ($row->type === 'highlight') {
                $highlights[] = [
                    'id'      => $row->annot_id,
                    'pageNum' => $row->page_num,
                    'rects'   => json_decode($row->rects, true) ?? [],
                    'text'    => $row->text,
                    'color'   => $row->color,
                ];
            } elseif ($row->type === 'comment') {
                $comments[] = [
                    'id'      => $row->annot_id,
                    'pageNum' => $row->page_num,
                    'x'       => (float)$row->pos_x,
                    'y'       => (float)$row->pos_y,
                    'text'    => $row->text,
                ];
            }
        }

        return response()->json([
            'success'    => true,
            'highlights' => $highlights,
            'comments'   => $comments,
        ]);
    }
}