<?php 

if (!function_exists('fileUpload')) {
    function fileUpload($file, $destination)
    {

        $fileName = "";

        if (!$file) {
            return $fileName;
        }

        $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();

        if (!file_exists($destination)) {
            mkdir($destination, 0777, true);
        }
        $file->move($destination, $fileName);
        $fileName = $destination . $fileName;
        return $fileName;

    }
}

if (!function_exists('fileUpdate')) {
    function fileUpdate($databaseFile, $file, $destination)
    {

        $fileName = "";

        if ($file) {
            $fileName = fileUpload($file, $destination);

            if ($databaseFile && file_exists($databaseFile)) {

                unlink($databaseFile);

            }
        } elseif (!$file and $databaseFile) {
            $fileName = $databaseFile;
        }

        return $fileName;

    }
}