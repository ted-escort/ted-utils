<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitbd87c7e21a10dd4bba1b1eea6cd191dd
{
    public static $prefixLengthsPsr4 = array (
        't' => 
        array (
            'ted\\escort\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'ted\\escort\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitbd87c7e21a10dd4bba1b1eea6cd191dd::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitbd87c7e21a10dd4bba1b1eea6cd191dd::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitbd87c7e21a10dd4bba1b1eea6cd191dd::$classMap;

        }, null, ClassLoader::class);
    }
}
