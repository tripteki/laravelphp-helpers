<?php

namespace Tripteki\Helpers\Helpers;

use ReflectionClass;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class ProjectHelper
{
    /**
     * @param string $path
     * @param string $namespace
     * @return bool
     */
    public function putNamespace($path, $namespace)
    {
        if (is_writeable($path)) {

            $file = file_get_contents($path);
            $search = $this->searchline($file, "namespace")."\n\n";
            $replace = "use ".$namespace.";\n";

            if (! $this->searchline($file, $replace)) $this->putAfter($path, $search, $replace);

            return true;

        } else {

            return false;
        }
    }

    /**
     * @param string $path
     * @param string $trait
     * @return bool
     */
    public function putTrait($path, $trait)
    {
        if (is_writeable($path) && trait_exists($trait)) {

            $this->putNamespace($path, $trait);

            $trait = Str::afterLast($trait, "\\");

            $file = "class".Str::before(Str::after(file_get_contents($path), "class"), "function");

            if ($search = $this->searchline($file, "use")) {

                if (! $this->searchline($search, $trait)) {

                    $replace = Str::replaceLast(";", ", ".$trait.";", $search);

                    $this->put($path, $search, $replace);
                }

            } else {

                if (! $this->put($path, "//", "use ".$trait.";")) $this->put($path, "{\n", "{\n\t"."use ".$trait.";\n");
            }

            return true;

        } else {

            return false;
        }
    }

    /**
     * @param string $path
     * @param string $name
     * @param string $middleware
     * @return bool
     */
    public function putMiddleware($path, $name, $middleware)
    {
        $path = $path ?? app_path("Http/Kernel.php");

        if (is_writeable($path) && class_exists($middleware)) {

            $this->putNamespace($path, $middleware);

            $middleware = Str::afterLast($middleware, "\\")."::class";

            $file = file_get_contents($path);

            $from = $this->searchline($file, "routeMiddleware =");
            $to = "];";

            if ($from) {

                $content = $from.Str::before(Str::after($file, $from), $to).$to;

                $keyvalue = '"'.$name.'"'." => ".$middleware.",\n";
                $end = $this->searchline($content, $to);
                $start = Str::before($content, $end);

                $pre = $start.$end;

                if (! $this->searchline($pre, $keyvalue)) {

                    $token = Str::replace($to, "", $end.$end.$keyvalue);
                    $post = $start.$token.$end;

                    $this->put($path, $pre, $post);
                }
            }

            return true;

        } else {

            return false;
        }
    }

    /**
     * @param string $baseroutepath
     * @param string $routepath
     * @return bool
     */
    public function putRoute($baseroutepath, $routepath)
    {
        $baseroutepath = base_path("routes/".$baseroutepath);

        if (is_writeable($baseroutepath)) {

            $file = file_get_contents($baseroutepath);
            $routepath = 'require __DIR__."/'.$routepath.'";';

            if (! $this->searchline($file, $routepath)) {

                (new Filesystem)->append($baseroutepath, "\n".$routepath);

                return true;

            } else {

                return false;
            }

        } else {

            return false;
        }
    }

    /**
     * @param string $package
     * @param string $version
     * @return void
     */
    public function packageToDepComposer($package, $version)
    {
        $this->packageTo(base_path("composer.json"), "require", $package, $version);
        $this->installComposer($package, $version);
    }

    /**
     * @param string $package
     * @param string $version
     * @return void
     */
    public function packageToDepDevComposer($package, $version)
    {
        $this->packageTo(base_path("composer.json"), "require-dev", $package, $version);
        $this->installComposer($package, $version, true);
    }

    /**
     * @param string $package
     * @param string $version
     * @return void
     */
    public function packageToDepPackage($package, $version)
    {
        $this->packageTo(base_path("package.json"), "dependencies", $package, $version);
    }

    /**
     * @param string $package
     * @param string $version
     * @return void
     */
    public function packageToDepDevPackage($package, $version)
    {
        $this->packageTo(base_path("package.json"), "devDependencies", $package, $version);
    }

    /**
     * @param string $package
     * @param string $version
     * @param bool $isDev
     * @return void
     */
    public function installComposer($package, $version, $isDev = false)
    {
        if (app()->runningInConsole()) {

            $composer = $this->option("composer");
            $command = [];

            if ($composer !== "global") $command = [ "php", $composer, ];
            else $command = [ "composer", ];

            $command = array_merge($command, [ "require", $package.":".$version, ]);

            if ($isDev) $command = array_merge($command, [ "--dev", ]);

            shell_exec(implode(" ", array_merge([ "COMPOSER_MEMORY_LIMIT=-1", ], $command)));
        }
    }

    /**
     * @param string $path
     * @param string $token
     * @param string $package
     * @param string $version
     * @return bool
     */
    public function packageTo($path, $token, $package, $version)
    {
        if (is_writeable($path)) {

            $content = json_decode(file_get_contents($path), true);

            if (! array_key_exists($token, $content)) {

                $content[$token] = [];
            }

            $content[$token][$package] = $version;

            file_put_contents($path, json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

            return true;

        } else {

            return false;
        }
    }

    /**
     * @param string $content
     * @param string $search
     * @return string|null
     */
    public function searchline($content, $search)
    {
        $before = Str::afterLast(Str::before($content, $search), "\n");
        $middle = $search;
        $after = Str::before(Str::after($content, $search), "\n");

        $line = $before.$middle.$after;

        if (Str::contains($content, $line)) {

            return $line;
        }

        return null;
    }

    /**
     * @param string $path
     * @param string $search
     * @param string $content
     * @return bool
     */
    public function put($path, $search, $content)
    {
        if (is_writeable($path)) {

            $file = file_get_contents($path);

            file_put_contents($path, Str::replace($search, $content, $file));

            return true;

        } else {

            return false;
        }
    }

    /**
     * @param string $path
     * @param string $search
     * @param string $content
     * @return bool
     */
    public function putBefore($path, $search, $content)
    {
        if (is_writeable($path)) {

            $file = file_get_contents($path);

            $before = Str::before($file, $search);
            $center = $search;
            $after = Str::after($file, $search);

            file_put_contents($path, $before.$content.$center.$after);

            return true;

        } else {

            return false;
        }
    }

    /**
     * @param string $path
     * @param string $search
     * @param string $content
     * @return bool
     */
    public function putAfter($path, $search, $content)
    {
        if (is_writeable($path)) {

            $file = file_get_contents($path);

            $before = Str::before($file, $search);
            $center = $search;
            $after = Str::after($file, $search);

            file_put_contents($path, $before.$center.$content.$after);

            return true;

        } else {

            return false;
        }
    }

    /**
     * @param string $class
     * @return string
     */
    public function classToFile($class)
    {
        return (new ReflectionClass($class))->getFileName();
    }
};
