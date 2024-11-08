<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitaad26f9a3c6560e8894e2f13078aba6c
{
    public static $files = array (
        'e65c8a739303e688867e7f3749b111d2' => __DIR__ . '/..' . '/wpshop/wpshop-settings/autoload.php',
        '7166494aeff09009178f278afd86c83f' => __DIR__ . '/..' . '/yahnis-elsts/plugin-update-checker/load-v4p13.php',
        '5f5c8b4941155d27e9dd2b113a629bf6' => __DIR__ . '/../..' . '/src/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'Wpshop\\OmniVideo\\' => 17,
            'WPShop\\Container\\' => 17,
        ),
        'P' => 
        array (
            'Psr\\Container\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Wpshop\\OmniVideo\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'WPShop\\Container\\' => 
        array (
            0 => __DIR__ . '/..' . '/wpshop/container/src',
        ),
        'Psr\\Container\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/container/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Psr\\Container\\ContainerExceptionInterface' => __DIR__ . '/..' . '/psr/container/src/ContainerExceptionInterface.php',
        'Psr\\Container\\ContainerInterface' => __DIR__ . '/..' . '/psr/container/src/ContainerInterface.php',
        'Psr\\Container\\NotFoundExceptionInterface' => __DIR__ . '/..' . '/psr/container/src/NotFoundExceptionInterface.php',
        'WPShop\\Container\\Container' => __DIR__ . '/..' . '/wpshop/container/src/Container.php',
        'WPShop\\Container\\Exception\\CyclicDependenciesException' => __DIR__ . '/..' . '/wpshop/container/src/Exception/CyclicDependenciesException.php',
        'WPShop\\Container\\Exception\\ExpectedInvokableException' => __DIR__ . '/..' . '/wpshop/container/src/Exception/ExpectedInvokableException.php',
        'WPShop\\Container\\Exception\\FrozenServiceException' => __DIR__ . '/..' . '/wpshop/container/src/Exception/FrozenServiceException.php',
        'WPShop\\Container\\Exception\\InvalidServiceIdentifierException' => __DIR__ . '/..' . '/wpshop/container/src/Exception/InvalidServiceIdentifierException.php',
        'WPShop\\Container\\Exception\\UnknownIdentifierException' => __DIR__ . '/..' . '/wpshop/container/src/Exception/UnknownIdentifierException.php',
        'WPShop\\Container\\Psr11\\Container' => __DIR__ . '/..' . '/wpshop/container/src/Psr11/Container.php',
        'WPShop\\Container\\Psr11\\ServiceLocator' => __DIR__ . '/..' . '/wpshop/container/src/Psr11/ServiceLocator.php',
        'WPShop\\Container\\ServiceIterator' => __DIR__ . '/..' . '/wpshop/container/src/ServiceIterator.php',
        'WPShop\\Container\\ServiceProviderInterface' => __DIR__ . '/..' . '/wpshop/container/src/ServiceProviderInterface.php',
        'WPShop\\Container\\ServiceRegistry' => __DIR__ . '/..' . '/wpshop/container/src/ServiceRegistry.php',
        'Wpshop\\OmniVideo\\Admin\\Settings' => __DIR__ . '/../..' . '/src/Admin/Settings.php',
        'Wpshop\\OmniVideo\\AssetsProvider' => __DIR__ . '/../..' . '/src/AssetsProvider.php',
        'Wpshop\\OmniVideo\\Blocks' => __DIR__ . '/../..' . '/src/Blocks.php',
        'Wpshop\\OmniVideo\\PosterManager' => __DIR__ . '/../..' . '/src/PosterManager.php',
        'Wpshop\\OmniVideo\\Shortcode' => __DIR__ . '/../..' . '/src/Shortcode.php',
        'Wpshop\\OmniVideo\\VideoBlock' => __DIR__ . '/../..' . '/src/VideoBlock.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitaad26f9a3c6560e8894e2f13078aba6c::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitaad26f9a3c6560e8894e2f13078aba6c::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitaad26f9a3c6560e8894e2f13078aba6c::$classMap;

        }, null, ClassLoader::class);
    }
}
