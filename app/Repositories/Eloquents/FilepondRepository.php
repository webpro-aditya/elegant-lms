<?php

namespace App\Repositories\Eloquents;

use App\Traits\Filepond;
use Illuminate\Support\Facades\File;
use App\Repositories\Interfaces\FilepondRepositoryInterface;
use Illuminate\Validation\ValidationException;

class FilepondRepository implements FilepondRepositoryInterface
{
    use Filepond;

    public function load(object $request): mixed
    {
        return $request->load;
    }
    public function upload(object $request): array|string
    {
        $file = $request->file('file');

        if (!isset($file)) {
            throw ValidationException::withMessages([
                'file' => trans('validation.file.required')
            ]);
        }

        // Base upload directory
        $basePath = $this->getBasePath() . '/upload/' . saasDomain();

        // Create base directory only once
        if (!File::isDirectory($basePath)) {
            File::makeDirectory($basePath, 0777, true, true);
        }

        // Handle multiple files
        if (is_array($file)) {

            $paths = [];

            foreach ($file as $item) {

                if (!$item) continue;

                // Create unique folder per file
                $filePath = $basePath . '/' . uniqid();

                if (!File::isDirectory($filePath)) {
                    File::makeDirectory($filePath, 0777, true, true);
                }

                $name = $item->getClientOriginalName();
                $destination = $filePath . '/' . $name;

                // 🔥 STREAM COPY (FASTER THAN move)
                $input = fopen($item->getRealPath(), 'rb');
                $output = fopen($destination, 'wb');

                stream_copy_to_stream($input, $output);

                fclose($input);
                fclose($output);

                $paths[] = $this->getServerIdFromPath($filePath);
            }

            return $paths;
        } else {

            // Single file
            $filePath = $basePath . '/' . uniqid();

            if (!File::isDirectory($filePath)) {
                File::makeDirectory($filePath, 0777, true, true);
            }

            $name = $file->getClientOriginalName();
            $destination = $filePath . '/' . $name;

            // 🔥 STREAM COPY (FASTER)
            $input = fopen($file->getRealPath(), 'rb');
            $output = fopen($destination, 'wb');

            stream_copy_to_stream($input, $output);

            fclose($input);
            fclose($output);

            return $this->getServerIdFromPath($filePath);
        }
    }
    public function chunk(object $request): bool
    {
        $id = $request->get('patch');
        $filePath = $this->getPathFromServerId($id);

        $fileName = $_SERVER['HTTP_UPLOAD_NAME'];
        $file = $filePath . '/' . $fileName;

        $offset = $_SERVER['HTTP_UPLOAD_OFFSET'];

        $input = fopen('php://input', 'rb');
        $output = fopen($file, 'c'); // create if not exists

        fseek($output, $offset);
        stream_copy_to_stream($input, $output);

        fclose($input);
        fclose($output);

        return true;
    }

    public function delete(object $request): bool
    {
        $filePath = $this->getPathFromServerId($request->getContent());
        File::deleteDirectory($filePath);
        return true;
    }
}
