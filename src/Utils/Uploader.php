<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class Uploader
{

    public function save(UploadedFile $file, string $name, string $directory) : string{

        //création d'un nouveau nom puor le fichier
        $newFileName = $name."-".uniqid().".".$file->guessExtension();
        //sauvegarde du fichier sur le serveur en le renommant
        $file->move($directory, $newFileName);

        return $newFileName;

    }


}