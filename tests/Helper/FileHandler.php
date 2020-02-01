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

}