<?php 

namespace MrCoto\MigrationWorkflow\Test\Helper;

class FileHandler
{

    /**
     * Delete folders and files recursively from $dirName starting point
     *
     * @param string $dirName
     * @return void
     */
    public function delete(string $dirName)
    {
        if (!file_exists($dirName)) return;
        foreach(glob("$dirName/*") as $file) {
            if(is_dir($file)) { 
                $this->delete($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirName);
    }

    /**
     * Copy content folder into another
     * @param string $source Source Folder
     * @param string $destination Destination Folder
     */
    public function copyFolder(string $source, string $destination)
    {
        $dir = opendir($source);
        @mkdir($destination);
        while(false !== ( $file = readdir($dir)) ) {
            if (in_array($file, ['.', '..']))
                continue;
            $srcFile = "$source/$file";
            $dstFile = "$destination/$file";
            is_dir($srcFile) ? $this->copyFolder($srcFile, $dstFile) : copy($srcFile, $dstFile);
        }
        closedir($dir);
    }

}