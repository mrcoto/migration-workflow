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
        $stubDir = __DIR__."/../.stub/$stubName.stub";
        if (!file_exists($stubDir)) {
            throw new \InvalidArgumentException("Stub file doesn't exists");
        }
        $this->stubName = $stubName;
        $this->stub = file_get_contents($stubDir);
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