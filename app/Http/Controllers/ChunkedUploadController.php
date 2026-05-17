<?php

namespace App\Http\Controllers;

use App\Traits\Filepond;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

/**
 * Handles chunked file uploads independently of PHP memory limits.
 *
 * Flow:
 * 1. Client calls POST /chunked-upload/init with filename & filesize
 *    → returns upload_id (encrypted temp path) + total chunks expected
 * 2. Client sends each chunk via POST /chunked-upload/chunk
 *    with upload_id, chunk_index, total_chunks, and the file chunk
 *    → each chunk is streamed to disk via php://input (low memory)
 * 3. On the final chunk, all parts are assembled into the complete file
 *    → returns the same encrypted server ID that getPublicPathFromServerId() expects
 *
 * This ensures InstructorCourseSettingController needs zero changes.
 */
class ChunkedUploadController extends Controller
{
    use Filepond;

    /**
     * Initialize a chunked upload session.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function init(Request $request)
    {
        $filename = $request->input('filename');
        $filesize = $request->input('filesize');
        $chunkSize = $request->input('chunk_size', 5 * 1024 * 1024); // default 5MB

        if (empty($filename) || empty($filesize)) {
            return response()->json(['error' => 'Missing filename or filesize'], 400);
        }

        // Sanitize filename - remove path traversal characters
        $filename = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $filename);

        $totalChunks = (int)ceil($filesize / $chunkSize);

        // Create temp directory (same base path as Filepond trait uses)
        $filePath = $this->getBasePath() . '/upload/' . saasDomain() . '/' . uniqid('cu_');
        if (!File::isDirectory($filePath)) {
            File::makeDirectory($filePath, 0777, true, true);
        }

        // Store metadata so we know what to expect
        $meta = [
            'filename'     => $filename,
            'filesize'     => (int)$filesize,
            'chunk_size'   => (int)$chunkSize,
            'total_chunks' => $totalChunks,
            'received'     => [],
        ];
        file_put_contents($filePath . '/_meta.json', json_encode($meta));

        $uploadId = $this->getServerIdFromPath($filePath);

        return response()->json([
            'upload_id'    => $uploadId,
            'total_chunks' => $totalChunks,
        ]);
    }

    /**
     * Receive a single chunk and assemble the file when all chunks arrive.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chunk(Request $request)
    {
        $uploadId   = $request->input('upload_id');
        $chunkIndex = (int)$request->input('chunk_index');
        $totalChunks = (int)$request->input('total_chunks');

        if (empty($uploadId)) {
            return response()->json(['error' => 'Missing upload_id'], 400);
        }

        try {
            $filePath = $this->getPathFromServerId($uploadId);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid upload_id'], 400);
        }

        if (!File::isDirectory($filePath)) {
            return response()->json(['error' => 'Upload session not found'], 404);
        }

        // Read metadata
        $metaFile = $filePath . '/_meta.json';
        if (!file_exists($metaFile)) {
            return response()->json(['error' => 'Upload metadata not found'], 404);
        }
        $meta = json_decode(file_get_contents($metaFile), true);

        // Get the chunk file from the request
        $chunkFile = $request->file('chunk');
        if (!$chunkFile) {
            return response()->json(['error' => 'No chunk data received'], 400);
        }

        // Write chunk to disk with sequential naming
        $chunkPath = $filePath . '/chunk_' . str_pad($chunkIndex, 6, '0', STR_PAD_LEFT);
        $chunkFile->move($filePath, 'chunk_' . str_pad($chunkIndex, 6, '0', STR_PAD_LEFT));

        // Update metadata
        $meta['received'][] = $chunkIndex;
        $meta['received'] = array_unique($meta['received']);
        file_put_contents($metaFile, json_encode($meta));

        // Check if all chunks have been received
        if (count($meta['received']) >= $meta['total_chunks']) {
            // Assemble the final file
            $finalFile = $filePath . '/' . $meta['filename'];
            $finalHandle = fopen($finalFile, 'wb');

            for ($i = 0; $i < $meta['total_chunks']; $i++) {
                $partPath = $filePath . '/chunk_' . str_pad($i, 6, '0', STR_PAD_LEFT);
                if (file_exists($partPath)) {
                    $partHandle = fopen($partPath, 'rb');
                    stream_copy_to_stream($partHandle, $finalHandle);
                    fclose($partHandle);
                    unlink($partPath); // Clean up chunk
                }
            }

            fclose($finalHandle);

            // Remove metadata file
            if (file_exists($metaFile)) {
                unlink($metaFile);
            }

            // Return the encrypted server ID (same format as Filepond)
            // This is what InstructorCourseSettingController expects
            return response()->json([
                'completed' => true,
                'server_id' => $uploadId, // Same encrypted path
            ]);
        }

        return response()->json([
            'completed'      => false,
            'chunks_received' => count($meta['received']),
            'total_chunks'   => $meta['total_chunks'],
        ]);
    }
}
