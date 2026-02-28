<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;


trait FileStore
{

    public static function saveFile(UploadedFile $file)
    {
        if(isset($file)){
            $current_date  = Carbon::now()->format('d-m-Y');

            $blockedExtensions = [
                'php', 'php3', 'php4', 'php5', 'php7', 'php8', 'phtml', 'pht', 'phps',
                'exe', 'msi', 'deb', 'rpm', 'dmg', 'pkg',
                'sh', 'bash', 'bat', 'cmd', 'com', 'scr', 'ps1', 'vbs', 'wsf',
                'js', 'jsp', 'jspx', 'asp', 'aspx', 'cgi', 'pl', 'py', 'rb',
                'html', 'htm', 'xhtml', 'shtml', 'xml',
                'jar', 'war', 'ear',
                'htaccess', 'htpasswd', 'ini', 'conf',
                'sql', 'db', 'sqlite', 'mdb',
                'swf', 'action', 'app', 'bin', 'run'
            ];

            $extension = strtolower($file->getClientOriginalExtension());
            if (in_array($extension, $blockedExtensions, true)) {
                return null;
            }
            if(!File::isDirectory('uploads/file/'.$current_date)){
                File::makeDirectory('uploads/file/'.$current_date, 0777, true, true);
            }

            $file_name = uniqid().'.'.$file->extension();
            $file->storeAs('uploads/file/'.$current_date.'/', $file_name);
            return 'uploads/file/'.$current_date.'/'.$file_name;
        }else{
            return null ;
        }
    }
}
