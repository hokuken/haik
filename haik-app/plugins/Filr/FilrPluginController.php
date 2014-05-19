<?php
namespace Hokuken\Haik\Plugin\Filr;

use BaseController;
use Config;
use File;
use Input;
use Redirect;
use View;
use Filr;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;

class FilrPluginController extends BaseController {

    /**
     * Show file list
     *
     * @return View
     */
    public function index()
    {
        $files_collection = Filr::all();
        $files = array();

        foreach ($files_collection as $i => $file)
        {
            $files[$i] = $file->toArray();
            $files[$i]['formatted_size'] = $file->formattedSize;
            if ($file->type === 'image')
            {
                $filepath = Config::get('haik.file.path').'/'.$file['filepath'];
                $imagesize = getimagesize($filepath);
                $files[$i]['dimension'] = array('width' => $imagesize[0], 'height' => $imagesize[1]);
            }
        }
        // !TODO: cache data
        return View::make('file.list')->with(array(
            'haik' => Config::get('haik'),
            'files' => $files
        ));
    }

    /**
     * Show upload form
     */
    public function showForm()
    {
        return View::make('file.upload');
    }

    /**
     * Upload file
     *
     * @return Redirect
     */
    public function upload()
    {
        $name = 'file';
        if ( ! Input::hasFile($name))
            return Redirect::route('plugin.filr.upload');
        if ( ! Input::file($name)->isValid())
            throw new UploadException("Uploaded file is not valid");

        $file = new Filr;
        $uploaded_file = Input::file($name);

        $file->title = $uploaded_file->getClientOriginalName();
        $file->size = $uploaded_file->getSize();
        $file->setType($uploaded_file);
        $file->setFilePath();

        $file->save();

        // move to storage
        $path = Config::get('haik.file.path').'/'.$file->filepath;
        $dirname = dirname($path);
        $basename = basename($path);
		File::makeDirectory($dirname, 0777, true, true);
		$uploaded_file->move($dirname, $basename);

        return Redirect::route('plugin.filr.upload');
    }

}
