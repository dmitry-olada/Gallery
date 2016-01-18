<?php

/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 14.12.15
 * Time: 14:39
 */

namespace core;

class Loader
{
    /**
     * An associative array. Keys contain a namespace prefix and value.
     *
     * @var array
     */
    protected static $prefixes = [];
    /**
     * Register loader with SPL autoloader stack.
     */
    public static function register()
    {
        spl_autoload_register(array(self::class, 'loadClass'));
    }
    /**
     * Adds the base directory to the namespace prefix.
     *
     * @param string     $prefix   Namespace prefix.
     * @param string     $base_dir The base directory for class files in the namespace.
     * @param bool|false $prepend  If true adds the base directory to the top of the stack.
     */
    public static function addNamespacePath($prefix, $base_dir, $prepend = false)
    {
        $prefix = trim($prefix, '\\').'\\';
        $base_dir = rtrim($base_dir, DIRECTORY_SEPARATOR).'/';
        if (array_key_exists($prefix, self::$prefixes) === false) {
            self::$prefixes[$prefix] = array();
        }
        if ($prepend) {
            array_unshift(self::$prefixes[$prefix], $base_dir);
        } else {
            array_push(self::$prefixes[$prefix], $base_dir);
        }
    }
    /**
     * Loads the file for a given class name.
     *
     * @param string $class Absolute class name.
     *
     * @return mixed If successfully, boolean false if unsuccessfully.
     */
    public static function loadClass($class)
    {
        $prefix = $class;
        while (false !== $pos = strrpos($prefix, '\\')) {
            $prefix = substr($class, 0, $pos + 1);
            $relative_class = substr($class, $pos + 1);
            $mapped_file = self::loadMappedFile($prefix, $relative_class);
            if ($mapped_file) {
                return $mapped_file;
            }
            $prefix = rtrim($prefix, '\\');
        }
        return false;
    }
    /**
     * Loads a file corresponding to the namespace prefix and relative class name.
     *
     * @param string $prefix         Namespace prefix.
     * @param string $relative_class Relative class name.
     *
     * @return mixed If file has been loaded successfully, boolean false if unsuccessfully.
     */
    protected static function loadMappedFile($prefix, $relative_class)
    {
        if (array_key_exists($prefix, self::$prefixes) === false) {
            return false;
        }
        foreach (self::$prefixes[$prefix] as $base_dir) {
            $file = $base_dir
                .str_replace('\\', '/', $relative_class)
                .'.php';
            if (self::requireFile($file)) {
                return $file;
            }
        }
        return false;
    }
    /**
     * If the file exists, load it.
     *
     * @param string $file The file for download.
     *
     * @return boolean true if the file exist, boolean false if it doesn't exist.
     */
    protected static function requireFile($file)
    {
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
        return false;
    }

}