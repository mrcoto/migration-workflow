<?php 

namespace MrCoto\MigrationWorkflow\Domain\ValueObject;

class Stub
{

    /** @var string $namespace */
    private $namespace;

    /** @var string $className */
    private $className;

    /** @var string $stubName */
    private $stubName;

    /** @var string $stubPath */
    private $stubPath;

    /** @var string $stubDir */
    private $stubDir;

    private const DEFAULT_PATH = __DIR__."/../.stub/";

    /** @var resource $stub */
    private $stub;

    public function __construct(
        string $namespace,
        string $className,
        string $stubName
    )
    {
        $this->namespace = $namespace;
        if (empty($namespace)) {
            throw new \InvalidArgumentException("namespace can't be empty");
        }
        $this->className = $className;
        if (empty($className)) {
            throw new \InvalidArgumentException("class name can't be empty");
        }
        $this->stubName = $stubName;
        $this->setStubPath(self::DEFAULT_PATH);
        $this->stub = file_get_contents($this->stubDir);
    }

    /**
     * Render stub
     *
     * @return string
     */
    public function render() : string
    {
        return str_replace([
            '$NAMESPACE',
            '$CLASSNAME'
        ], [
            $this->namespace, 
            $this->className
        ], $this->stub);
    }

    /**
     * Generate php stub file
     *
     * @return void
     */
    public function generate()
    {
        $path = $this->generateFolders();
        $className = $this->className;
        file_put_contents("$path/$className.php", $this->render());
    }

    /**
     * Set new stub path. Must have "/" at the end
     *
     * @param string $stubPath
     * @return void
     */
    public function setStubPath(string $stubPath)
    {
        $this->stubPath = $stubPath;
        $this->setStubDir($this->stubPath.$this->stubName.".stub");
        $this->stub = file_get_contents($this->stubDir);
    }

    /**
     * Set stub's dir
     *
     * @param string $stubDir
     * @return void
     */
    private function setStubDir(string $stubDir)
    {
        if (!file_exists($stubDir)) {
            throw new \InvalidArgumentException("Stub file doesn't exists");
        }
        $this->stubDir = $stubDir;
    }

    /**
     * Generate folders
     *
     * @return string New path
     */
    private function generateFolders() : string
    {
        $folders = explode("\\", $this->namespace);
        // Transform App to app
        if ($folders[0] == 'App') {
            $folders[0] = 'app';
        }
        $path = implode('/', $folders);
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        return $path;
    }

}