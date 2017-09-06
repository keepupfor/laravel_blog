<?php
/**
 * Created by PhpStorm.
 * User: xd
 * Date: 2017/9/6
 * Time: 9:10
 */
namespace App\Services;

use Dflydev\ApacheMimeTypes\PhpRepository;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
class UploadsManager
{
    protected $disk;
    protected $mimeDetect;

    public function __construct(PhpRepository $memeDetect)
    {
        $this->disk = Storage::disk(config('blog.uploads.storage'));
        $this->mimeDetect = $memeDetect;
    }

    /**
     * 创建新目录
     * */
    public function createDirectory($folder)
    {
        $folder = $this->cleanFolder($folder);
        if ($this->disk->exists($folder)) {
            return "'$folder' 目录已存在";
        }
        return $this->disk->makeDirectory($folder);
    }

    /**
     * 删除目录
     * */
    public function deleteDirectory($folder)
    {
        $folder = $this->cleanFolder($folder);
        $filesFolders = array_merge(
            $this->disk->directories($folder),
            $this->disk->files($folder)
        );
        if (!empty($filesFolders)) {
            return "目录必须为空才可以删除";
        }
        return $this->disk->deleteDirectory($folder);
    }

    /**
     * 删除文件
     */
    public function deleteFile($path)
    {
        $path = $this->cleanFolder($path);
        if (!$this->disk->exists($path)) {
            return "文件不存在";
        }
        return $this->disk->delete($path);

    }

    /**
     *保存文件
     */
    public function saveFile($path,$content)
    {
        $path = $this->cleanFolder($path);
        if ($this->disk->exists($path)) {
            return "文件已存在";
        }
        return $this->disk->put($path,$content);


    }
    public function folderInfo($folder)
    {
        $folder = $this->cleanFolder($folder);
        $breadcrumbs = $this->breadcrumbs($folder);
        $slice = array_slice($breadcrumbs, -1);//取数组最后一个元素
        $folderName = current($slice);//取出数组中的当前元素的值
        $subfolders = [];
        foreach (array_unique($this->disk->directories($folder)) as $subfolder) {
            $subfolders["/$subfolder"] = basename($subfolder);
        }
        $files = [];
        foreach ($this->disk->files($folder) as $path) {
            $files[] = $this->fileDetails($path);
        }
        return compact(
            'folder', 'folderName', 'breadcrumbs', 'subfolders', 'files'
        );

    }

    protected function cleanFolder($folder)
    {
        return '/' . trim(str_replace('..', '', $folder), '/');
    }

    /**
     * 返回当前目录路径
     * @param $folder
     * @return array
     */
    protected function breadcrumbs($folder)
    {
        $folder = trim($folder, '/');
        $crumbs = ['/' => 'root'];
        if (empty($folder)) {
            return $crumbs;
        }
        $folders = explode('/', $folder);
        $build = '';
        foreach ($folders as $folder) {
            $build .= '/' . $folder;
            $crumbs[$build] = $folder;
        }
        return $crumbs;
    }

    /**
     * 返回文件详细信息数组
     */
    protected function fileDetails($path)
    {
        $path = '/' . ltrim($path, '/');

        return [
            'name' => basename($path),
            'fullPath' => $path,
            'webPath' => $this->fileWebpath($path),
            'mimeType' => $this->fileMimeType($path),
            'size' => $this->fileSize($path),
            'modified' => $this->fileModified($path),
        ];
    }

    /**
     * 返回文件完整的web路径
     */
    public function fileWebpath($path)
    {
        $path = rtrim(config('blog.uploads.webpath'), '/') . '/' . ltrim($path, '/');
        return url($path);
    }

    /**
     * 返回文件MIME类型
     */
    public function fileMimeType($path)
    {
        return $this->mimeDetect->findType(
            pathinfo($path, PATHINFO_EXTENSION)
        );
    }

    /**
     * 返回文件大小
     */
    public function fileSize($path)
    {
        return $this->disk->size($path);
    }

    /**
     * 返回最后修改时间
     */
    public function fileModified($path)
    {
        return Carbon::createFromTimestamp(
            $this->disk->lastModified($path)
        );
    }

}