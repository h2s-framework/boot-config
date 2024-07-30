<?php

namespace Siarko\BootConfig;

use Siarko\Paths\Exception\RootPathNotSet;
use Siarko\Paths\RootPath;
use Siarko\Utils\ArrayManager;
use Siarko\Utils\DynamicDataObject;
use Siarko\Utils\EnvLoader;

class BootConfig extends DynamicDataObject
{

    /**
     * @param EnvLoader $envLoader
     * @param RootPath $rootPath
     * @param ArrayManager $arrayManager
     * @param string $configFile
     * @throws RootPathNotSet
     */
    public function __construct(
        EnvLoader $envLoader,
        RootPath $rootPath,
        private readonly ArrayManager $arrayManager,
        private readonly string $configFile = 'config.php'
    ){
        parent::__construct(
            array_replace_recursive(
                $this->loadConfig($rootPath),
                $envLoader->getAllData()
            )
        );
    }

    /**
     * @param RootPath $rootPath
     * @return array
     * @throws RootPathNotSet
     */
    private function loadConfig(RootPath $rootPath): array
    {
        $configFile = $rootPath->get() . DIRECTORY_SEPARATOR . $this->configFile;
        if (file_exists($configFile)) {
            return require $configFile;
        }
        return [];
    }

    /**
     * @param array|string $name
     * @return mixed
     */
    public function getData(array|string $name): mixed
    {
        if(is_array($name)){
            $name = implode('.', $name);
        }
        return $this->arrayManager->get($name, $this->__data, null, '.');
    }

}