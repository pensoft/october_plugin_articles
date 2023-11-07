<?php

namespace Pensoft\Articles\Components;

use Cms\Classes\ComponentBase;
use Pensoft\Articles\Models\Article;

/**
 * Gallery Component for displaying and downloading image galleries.
 */
class Gallery extends ComponentBase
{
    /**
     * Maximum allowed size for the ZIP file to download in bytes. (500MB)
     */
    const MAX_ZIP_SIZE = 500000000;

    public function componentDetails()
    {
        return [
            'name'        => 'Gallery related to an article',
            'description' => 'Display and handle downloads for Image galleries related to article'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    /**
     * Run the component and fetch galleries for the current article.
     */
    public function onRun()
    {
        $this->page['galleries'] = $this->loadGalleries();
    }

    /**
     * Handles the request for downloading a gallery as a ZIP archive.
     * 
     * @return mixed The download response or error message.
     */
    public function onDownload()
    {
        $galleryId = post('gallery_id');
    
        if (!$galleryId) {
            return "No gallery specified for download.";
        }
    
        $zipFile = $this->createZipArchive($galleryId);
    
        if (!$zipFile) {
            return "Error creating ZIP file.";
        }
    
        return $this->prepareDownloadResponse($zipFile);
    }

    /**
     * Creates a ZIP archive from the images in the specified gallery.
     * 
     * @param int $galleryId The ID of the gallery.
     * @return string|null The file path to the created ZIP file or null on failure.
     */
    protected function createZipArchive($galleryId)
    {
        if(!class_exists(\Pensoft\Media\Models\Galleries::class)){
            return null;
        }

        $gallery = \Pensoft\Media\Models\Galleries::find($galleryId);
        if (!$gallery) {
            return null;
        }
    
        $zipFileName = $this->getTemporaryZipFileName($gallery->name);
        $zip = new \ZipArchive;

        if ($zip->open($zipFileName, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            return null;
        }
    
        $images = $this->getImagePaths($gallery);
        if (empty($images)) {
            $zip->close();
            @unlink($zipFileName);
            return null;
        }
    
        foreach ($images as $image) {
            if (file_exists($image)) {
                $zip->addFile($image, basename($image));
            }
        }

        $zip->close();
    
        return $zipFileName;
    }

    /**
     * Generates a temporary file name for the ZIP archive.
     * 
     * @param string $galleryName The name of the gallery.
     * @return string The file path for the temporary ZIP file.
     */
    protected function getTemporaryZipFileName($galleryName)
    {
        $sanitizedGalleryName = $this->sanitizeFileName($galleryName);
        return sys_get_temp_dir() . DIRECTORY_SEPARATOR . $sanitizedGalleryName . '.zip';
    }

    /**
     * Prepares the HTTP response for downloading the ZIP file.
     * 
     * @param string $zipFile The file path to the ZIP file.
     * @return mixed The download response or error message.
     */
    protected function prepareDownloadResponse($zipFile)
    {
        if (file_exists($zipFile) && filesize($zipFile) < self::MAX_ZIP_SIZE) {
            return \Response::download($zipFile)->deleteFileAfterSend(true);
        }
        return "Error downloading or file size exceeded";
    }

    /**
     * Retrieves the local paths for all images in the specified gallery.
     * 
     * @param \Pensoft\Media\Models\Galleries $gallery The gallery model instance.
     * @return array An array of image file paths.
     */
    protected function getImagePaths($gallery)
    {
        $images = [];
    
        if (isset($gallery->images)) {
            foreach ($gallery->images as $image) {
                $localPath = $image->getLocalPath();
                if ($localPath) {
                    $images[] = $localPath;
                }
            }
        }
    
        return $images;
    }

    /**
     * Sanitizes a file name to ensure it's safe for the file system.
     * 
     * @param string $filename The original file name.
     * @return string The sanitized file name.
     */
    protected function sanitizeFileName($filename)
    {
        return preg_replace('/[^a-zA-Z0-9_-]/', '_', $filename);
    }

    /**
     * Loads galleries related to the current article.
     * 
     * @return array An array of Gallery models or an empty array if none are found.
     */
    protected function loadGalleries()
    {
        $articleId = $this->param('id');
        $article = Article::where('slug', $articleId)->first();

        if ($article && class_exists(\Pensoft\Media\Models\Galleries::class)) {
            return \Pensoft\Media\Models\Galleries::where('related', true)
                ->where('article_id', $article->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return [];
    }
}
