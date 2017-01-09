<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3e7ff809b57514cc0183d5cd38bdc7d4
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'August\\Pitbull\\' => 15,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'August\\Pitbull\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'August\\Pitbull\\Pitbull' => __DIR__ . '/../..' . '/src/Pitbull.php',
        'August\\Pitbull\\PitbullServiceProvider' => __DIR__ . '/../..' . '/src/PitbullServiceProvider.php',
        'August\\Pitbull\\Traits\\Pitbull' => __DIR__ . '/../..' . '/src/traits/Pitbull.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit3e7ff809b57514cc0183d5cd38bdc7d4::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit3e7ff809b57514cc0183d5cd38bdc7d4::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit3e7ff809b57514cc0183d5cd38bdc7d4::$classMap;

        }, null, ClassLoader::class);
    }
}
