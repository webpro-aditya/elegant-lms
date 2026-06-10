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
        $data = $request->all();

        $action   = $data['action'] ?? '';
        $lessonId = (int) ($data['lesson_id'] ?? 0);
        $courseId = (int) ($data['course_id'] ?? 0);
        $userId   = Auth::id();

        if (!$userId || !$lessonId) {
            return response()->json([
                'success' => false,
                'error'   => 'Unauthenticated or missing lesson'
            ], 403);
        }

        switch ($action) {
            case 'highlight':
                $annotId = (int) ($data['annot_id'] ?? 0);

                if ($annotId <= 0) {
                    return response()->json([
                        'success' => false,
                        'error'   => 'Missing annot_id'
                    ], 422);
                }

                PdfAnnotation::updateOrCreate(
                    [
                        'user_id'   => $userId,
                        'lesson_id' => $lessonId,
                        'course_id' => $courseId,
                        'type'      => 'highlight',
                        'annot_id'  => $annotId,
                    ],
                    [
                        'page_num' => (int) ($data['pageNum'] ?? 1),
                        'rects'    => is_array($data['rects'] ?? null)
                            ? json_encode($data['rects'])
                            : ($data['rects'] ?? '[]'),
                        'text'     => substr((string) ($data['text'] ?? ''), 0, 2000),
                        'color'    => preg_replace('/[^a-z]/', '', (string) ($data['color'] ?? 'yellow')),
                    ]
                );

                return response()->json([
                    'success'  => true,
                    'action'   => $action,
                    'annot_id' => $annotId,
                ]);

            case 'delete_highlight':
                $annotId = (int) ($data['annot_id'] ?? 0);

                if ($annotId <= 0) {
                    return response()->json([
                        'success' => false,
                        'error'   => 'Missing annot_id'
                    ], 422);
                }

                PdfAnnotation::where([
                    'user_id'   => $userId,
                    'lesson_id' => $lessonId,
                    'course_id' => $courseId,
                    'type'      => 'highlight',
                    'annot_id'  => $annotId,
                ])->delete();

                return response()->json([
                    'success'  => true,
                    'action'   => $action,
                    'annot_id' => $annotId,
                ]);

            case 'drawing':
                $annotId = (int) ($data['annot_id'] ?? 0);

                if ($annotId <= 0) {
                    return response()->json([
                        'success' => false,
                        'error'   => 'Missing annot_id'
                    ], 422);
                }

                PdfAnnotation::updateOrCreate(
                    [
                        'user_id'   => $userId,
                        'lesson_id' => $lessonId,
                        'course_id' => $courseId,
                        'type'      => 'drawing',
                        'annot_id'  => $annotId,
                    ],
                    [
                        'page_num' => (int) ($data['pageNum'] ?? 1),
                        'rects'    => is_array($data['rects'] ?? null)
                            ? json_encode($data['rects'])
                            : ($data['rects'] ?? '[]'),
                        'color'    => preg_replace('/[^a-z0-9#]/i', '', (string) ($data['color'] ?? 'black')),
                    ]
                );

                return response()->json([
                    'success'  => true,
                    'action'   => $action,
                    'annot_id' => $annotId,
                ]);

            case 'delete_drawing':
                $annotId = (int) ($data['annot_id'] ?? 0);

                if ($annotId <= 0) {
                    return response()->json([
                        'success' => false,
                        'error'   => 'Missing annot_id'
                    ], 422);
                }

                PdfAnnotation::where([
                    'user_id'   => $userId,
                    'lesson_id' => $lessonId,
                    'course_id' => $courseId,
                    'type'      => 'drawing',
                    'annot_id'  => $annotId,
                ])->delete();

                return response()->json([
                    'success'  => true,
                    'action'   => $action,
                    'annot_id' => $annotId,
                ]);

            case 'clear_drawings':
                PdfAnnotation::where([
                    'user_id'   => $userId,
                    'lesson_id' => $lessonId,
                    'course_id' => $courseId,
                    'type'      => 'drawing',
                ])->delete();

                return response()->json([
                    'success'  => true,
                    'action'   => $action,
                ]);

            case 'comment':
                $annotId = (int) ($data['annot_id'] ?? 0);

                if ($annotId <= 0) {
                    return response()->json([
                        'success' => false,
                        'error'   => 'Missing annot_id'
                    ], 422);
                }

                PdfAnnotation::updateOrCreate(
                    [
                        'user_id'   => $userId,
                        'lesson_id' => $lessonId,
                        'course_id' => $courseId,
                        'type'      => 'comment',
                        'annot_id'  => $annotId,
                    ],
                    [
                        'page_num' => (int) ($data['pageNum'] ?? 1),
                        'pos_x'    => (float) ($data['x'] ?? 0),
                        'pos_y'    => (float) ($data['y'] ?? 0),
                        'text'     => substr((string) ($data['text'] ?? ''), 0, 5000),
                    ]
                );

                return response()->json([
                    'success'  => true,
                    'action'   => $action,
                    'annot_id' => $annotId,
                ]);

            case 'update_comment':
                $annotId = (int) ($data['annot_id'] ?? 0);

                if ($annotId <= 0) {
                    return response()->json([
                        'success' => false,
                        'error'   => 'Missing annot_id'
                    ], 422);
                }

                PdfAnnotation::where([
                    'user_id'   => $userId,
                    'lesson_id' => $lessonId,
                    'course_id' => $courseId,
                    'type'      => 'comment',
                    'annot_id'  => $annotId,
                ])->update([
                    'text' => substr((string) ($data['text'] ?? ''), 0, 5000)
                ]);

                return response()->json([
                    'success'  => true,
                    'action'   => $action,
                    'annot_id' => $annotId,
                ]);

            case 'delete_comment':
                $annotId = (int) ($data['annot_id'] ?? 0);

                if ($annotId <= 0) {
                    return response()->json([
                        'success' => false,
                        'error'   => 'Missing annot_id'
                    ], 422);
                }

                PdfAnnotation::where([
                    'user_id'   => $userId,
                    'lesson_id' => $lessonId,
                    'course_id' => $courseId,
                    'type'      => 'comment',
                    'annot_id'  => $annotId,
                ])->delete();

                return response()->json([
                    'success'  => true,
                    'action'   => $action,
                    'annot_id' => $annotId,
                ]);

            default:
                return response()->json([
                    'success' => false,
                    'error'   => 'Unknown action'
                ], 400);
        }
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
        $drawings   = [];

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
            } elseif ($row->type === 'drawing') {
                $drawings[] = [
                    'id'      => $row->annot_id,
                    'pageNum' => $row->page_num,
                    'rects'   => json_decode($row->rects, true) ?? [],
                    'color'   => $row->color,
                ];
            }
        }

        return response()->json([
            'success'    => true,
            'highlights' => $highlights,
            'comments'   => $comments,
            'drawings'   => $drawings,
        ]);
    }
}
