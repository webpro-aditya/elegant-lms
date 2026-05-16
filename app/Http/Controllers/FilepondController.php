<?php

namespace App\Http\Controllers;

use App\Traits\Filepond;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;


class FilepondController extends Controller
{
    use Filepond;


    public function load(Request $request)
    {
        return $request->load;
    }

    public function upload(Request $request)
    {
        $file = $request->file('file');
        if (is_array($file)) {
            $path = [];
            foreach ($file as $item) {
                $filePath = $this->getBasePath() . '/upload/' . saasDomain() . '/' . uniqid();
                if (!File::isDirectory($filePath)) {
                    File::makeDirectory($filePath, 0777, true, true);
                }
                if ($item) {
                    $name = $item->getClientOriginalName();
                    if ($item->move($filePath, $name)) {
                        $path[] = $this->getServerIdFromPath($filePath);
                    }
                }
            }
            return $path;
        } else {
            $filePath = $this->getBasePath() . '/upload/' . saasDomain() . '/' . uniqid();
            if (!File::isDirectory($filePath)) {
                File::makeDirectory($filePath, 0777, true, true);
            }
            if ($file) {
                $name = $file->getClientOriginalName();
                if (!$file->move($filePath, $name)) {
                    return Response::make('Could not save file', 500);
                }
            }
            return Response::make($this->getServerIdFromPath($filePath), 200);
        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response|int
     */
    public function chunk(Request $request)
    {
        $id = $request->get('patch');

        // location of patch files
        $filePath = $this->getPathFromServerId($id);

        $fileName = $request->header('Upload-Name');
        if (empty($fileName)) {
            return Response::make('Missing upload name', 400);
        }
        $dir = $filePath . '/' . $fileName;

        // get patch data
        $offset = $request->header('Upload-Offset');
        $length = $request->header('Upload-Length');

        // should be numeric values, else exit
        if (!is_numeric($offset) || !is_numeric($length)) {
            return Response::make('Invalid offset or length', 400);
        }

        // write patch file for this request
        file_put_contents($dir . '.patch.' . $offset, fopen('php://input', 'rb'));

        // calculate total size of patches
        $size = 0;
        $patch = glob($dir . '.patch.*');
        foreach ($patch as $filename) {
            $size += filesize($filename);
        }

        // if total size equals length of file we have gathered all patch files
        if ($size == $length) {
            // create output file
            $file_handle = fopen($dir, 'wb');

            // Sort patches by offset to avoid zero-padding and massive IO
            usort($patch, function ($a, $b) {
                $aOffset = (int)explode('.patch.', $a)[1];
                $bOffset = (int)explode('.patch.', $b)[1];
                return $aOffset <=> $bOffset;
            });

            // write patches to file
            foreach ($patch as $filename) {
                $patch_handle = fopen($filename, 'rb');
                stream_copy_to_stream($patch_handle, $file_handle);
                fclose($patch_handle);
                unlink($filename);
            }
            // done with file
            fclose($file_handle);
        }

        return Response::make('', 204);
    }

    /**
     * Takes the given encrypted filepath and deletes
     * it if it hasn't been tampered with
     *
     * @param Request $request
     * @return mixed
     */
    public function delete(Request $request)
    {

        try {
            $filePath = $this->getPathFromServerId($request->getContent());
            File::deleteDirectory($filePath);
        } catch (\Exception $exception) {

        }

        return Response::make('');
    }
}
