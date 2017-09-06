<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UploadFileRequest;
use App\Http\Requests\UploadNewFolderRequest;
use App\Services\UploadsManager;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class UploadController extends Controller
{
    protected $manager;
    public function __construct(UploadsManager $manager)
    {
        $this->manager=$manager;
    }
    public function index(Request $request)
    {
        $folder=$request->get('folder');
        $data=$this->manager->folderInfo($folder);
        return view('admin.upload.index',$data);
    }
    /**
     * 创建目录
     * */
    public function createFolder(UploadNewFolderRequest $request)
    {
        $new_folder=$request->get('new_folder');
        $folder=$request->get('folder').'/'.$new_folder;
        $result=$this->manager->createDirectory($folder);
        if ($result===true)
        {
            return redirect()->back()->withSuccess("目录 '$new_folder' 创建成功");
        }
        else{
            $error=$result?:'目录创建失败';
            return redirect()->back()->withErrors($error);
        }
    }
    /**
     * 删除文件
     */
    public function deleteFile(Request $request)
    {
        $del_file=$request->get('del_file');
        $path=$request->get('folder').'/'.$del_file;
        $result=$this->manager->deleteFile($path);
        if ($result===true)
        {
            return redirect()->back()->withSuccess("文件 '$del_file' 删除成功");
        }
        else{
            $error=$result?:'文件删除失败';
            return redirect()->back()->withErrors($error);
        }
    }

    /**
     *删除目录
     */
    public function deleteFolder(Request $request)
    {
        $del_folder=$request->get('del_folder');
        $folder=$request->get('folder').'/'.$del_folder;
        $result=$this->manager->deleteDirectory($folder);
        if ($result===true)
        {
            return redirect()->back()->withSuccess("目录 '$del_folder' 删除成功");
        }
        else{
            $error=$result?:'目录删除失败';
            return redirect()->back()->withErrors($error);
        }
    }
    public function uploadFile(UploadFileRequest $request)
    {
           $file=$_FILES['file'];
           $fileName=$request->get('file_name');
           $fileName=$fileName?:$file['name'];
           $path=str_finish($request->get('folder'),'/').$fileName;
           $content=File::get($file['tmp_name']);
           $result=$this->manager->saveFile($path,$content);
        if ($result===true)
        {
            return redirect()->back()->withSuccess("文件 '$fileName' 上传成功");
        }
        else{
            $error=$result?:'文件上传失败';
            return redirect()->back()->withErrors($error);
        }
    }
}
