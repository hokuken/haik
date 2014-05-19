<?php

use Symfony\Component\HttpFoundation\File\File;

class Filr extends Eloquent {

    protected $table = 'haik_files';

    /**
     * Set type and mime_type
     *
     * @return $this for method chain
     */
    public function setType(File $file)
    {
        $this->mime_type = $file->getMimeType();
        $ext = '';
        if (is_a($file, 'Symfony\Component\HttpFoundation\File\UploadedFile'))
        {
            $ext = $file->getClientOriginalExtension();
        }
        else if (is_a($file, 'Symfony\Component\HttpFoundation\File\File'))
        {
            $ext = $file->getExtension();
        }

        $this->setTypeByExt($ext);
        if ($this->type) return $this;

        $this->setTypeByMimeType();
        return $this;
    }

    protected function setTypeByExt($ext)
    {
        switch ($ext)
        {
            // Image
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
            case 'bmp':
            case 'webp':
                $this->type = 'image';
                break;

            // Audio
			case 'mp3':
			case 'wav':
			case 'aiff':
			case 'aif':
			case 'm4a':
			case 'oga':
			case 'weba':
				$this->type = 'audio';
				break;

            // Video
			case 'mov':
			case 'mpeg':
			case 'mpg':
			case 'mp4':
			case 'ogv':
			case 'webm':
				$this->type = 'video';
				break;

            // Document
			case 'pdf':
			case 'doc':
			case 'docx':
			case 'xls':
			case 'xlsx':
			case 'ppt':
			case 'pptx':
			case 'pages':
			case 'numbers':
			case 'keynote':
				$this->type = 'doc';
				break;
        }
    }

    protected function setTypeByMimeType()
    {
        switch ($this->mime_type)
        {
            // Image
            case 'image/jpeg':
            case 'image/png':
            case 'image/gif':
            case 'image/bmp':
            case 'image/x-windows-bmp':
            case 'image/webp':
                $this->type = 'image';
                break;

            // Audio
			case 'audio/mpeg3':
			case 'audio/x-mpeg-3':
			case 'audio/wav':
			case 'audio/x-wav':
			case 'audio/aiff':
			case 'audio/x-aiff':
			case 'audio/ogg':
			case 'audio/webm':
				$this->type = 'audio';
				break;

            // Video
			case 'video/quicktime':
			case 'video/mpeg':
			case 'video/mp4':
			case 'video/ogg':
			case 'video/webm':
				$this->type = 'video';
				break;

            // Document
			case 'application/pdf':
			case 'application/msword':
			case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
			case 'application/vnd.ms-excel':
			case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
			case 'application/vnd.ms-powerpoint':
			case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
				$this->type = 'doc';
				break;

            default:
                $this->type = 'file';
        }

    }

    /**
     * Set file path
     *
     * @return $this for method chain
     */
    public function setFilePath()
    {
        if ($this->exists) return;
        $this->filepath = $this->createFilePath();
        return $this;
    }

    /**
     * Create new file path
     *
     * @return string new file path
     */
    protected function createFilePath()
    {
        $path = str_random(2) . '/' . str_random(2) . '/' . str_random(10);
        if ($this->where('filepath', $path)->count())
        {
            return $this->createFilePath();
        }
        return $path;
    }

    /**
     * Get GB/MB/KB/bytes Formatted size string
     *
     * @return string formatted size
     */
    public function getFormattedSizeAttribute()
    {
        $bytes = $this->size;

        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
}
