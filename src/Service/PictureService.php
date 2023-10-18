<?php

namespace App\Service;


use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Config\Definition\Exception\Exception;


class PictureService
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function add(UploadedFile $picture, ?string $folder = '', ?int $width = 250, ?int $height = 250)
    {
        $fichier = md5(uniqid(rand(), true)) . '.webp';

        // Récupérer les informations sur l'image
        $pictureInfos = getimagesize($picture->getPathname());

        if ($pictureInfos === false) {
            throw new \Exception('Format d\'image incorrect');
        }

        $mime = $pictureInfos['mime'];

        // Gérer le format de l'image
        switch ($mime) {
            case 'image/png':
                $pictureSource = imagecreatefrompng($picture->getPathname());
                break;
            case 'image/jpeg':
                $pictureSource = imagecreatefromjpeg($picture->getPathname());
                break;
            case 'image/webp':
                $pictureSource = imagecreatefromwebp($picture->getPathname());
                break;
            default:
                throw new \Exception('Format d\'image incorrect');
        }

        // Recadrer l'image
        $imageWidth = $pictureInfos[0];
        $imageHeight = $pictureInfos[1];

        $squareSize = min($imageWidth, $imageHeight);
        $src_x = ($imageWidth - $squareSize) / 2;
        $src_y = ($imageHeight - $squareSize) / 2;

        $resizedPicture = imagecreatetruecolor($width, $height);
        imagecopyresampled($resizedPicture, $pictureSource, 0, 0, $src_x, $src_y, $width, $height, $squareSize, $squareSize);

        $path = $this->params->get('images_directory') . '/' . $folder;

        if (!file_exists($path . '/mini/')) {
            mkdir($path . '/mini/', 0755, true);
        }

        imagewebp($resizedPicture, $path . '/mini/' . $width . 'x' . $height . '-' . $fichier);
        $picture->move($path . '/', $fichier);

        return $fichier;
    }

    public function delete(string $fichier, ?string $folder = '', ?int $width = 250, ?int $height = 250)
    {
        if ($fichier !== 'default.webp') {
            $success = false;
            $path = $this->params->get('images_directory') . '/' . $folder;
            $mini = $path . '/mini/' . $width . 'x' . $height . '-' . $fichier;
            $original = $path . '/' . $fichier;

            if (file_exists($mini)) {
                unlink($mini);
                $success = true;
            }

            if (file_exists($original)) {
                unlink($original);
                $success = true;
            }

            return $success;
        }

        return false;
    }
}