<?php

namespace Modules\WhatsappSupport\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;
use File;

trait ImageStore
{

    public static function saveAvatarImage($image, $height = null, $lenght = null)
    {
        if (isset($image)) {
            $current_date  = Carbon::now()->format('d-m-Y');
            $image_extention = str_replace('image/', '', Image::make($image)->mime());

            if ($height != null && $lenght != null) {
                $img = Image::make($image)->resize($height, $lenght);
            } else {
                $img = Image::make($image);
            }

            $img_name = 'public/whatsapp-support/avatar' . '/' . uniqid() . '.' . $image_extention;
            $img->save($img_name);
            return $img_name;
        } else {
            return null;
        }
    }
}
