<?php

namespace Bpwd\Media\Controllers;

//use App\Http\Controllers\Controller;
use Illuminate\Routing\Controller as Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;


class ManagerController extends Controller
{
    use DispatchesJobs, ValidatesRequests;
    /**
     * @var
     */
    public $file_location = null;
    public $dir_location = null;
    public $file_type = null;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->file_type = Input::get('type', 'Images'); // default set to Images.

        if ('Images' === $this->file_type) {
            $this->dir_location = Config::get('media.images_url');
            $this->file_location = Config::get('media.images_dir');
        } elseif ('Files' === $this->file_type) {
            $this->dir_location = Config::get('media.files_url');
            $this->file_location = Config::get('media.files_dir');
        } else {
            throw new \Exception('unexpected type parameter');
        }

        //$this->checkDefaultFolderExists('user');
        //$this->checkDefaultFolderExists('share');
    }

    public function index()
    {
        $working_dir = '/';
        //$working_dir .= (Config::get('lfm.allow_multi_user')) ? $this->getUserSlug() : Config::get('lfm.shared_folder_name');

        $extension_not_found = ! extension_loaded('gd') && ! extension_loaded('imagick');

        return view('media::index')
            ->with('working_dir', $working_dir)
            ->with('file_type', $this->file_type)
            ->with('extension_not_found', $extension_not_found);
    }

}
