<?php

abstract class FileSystem {
    public static function getGlobalLayout() {
        $file = Config::$dirroot . '/app/layout/layout.php';

        if (!file_exists($file))
            $file = null;

        return $file;
    }

    public static function getModulePath($module) {
        $module = strtolower($module);
        $path = Config::$dirroot . '/app/modules/' . $module;

        if (!file_exists($path))
            $path = null;

        return $path;
    }

    public static function getModuleController($module) {
        $file = self::getModulePath($module) . '/' . $module . 'controller.php';

        if (!file_exists($file))
            $file = null;

        return $file;
    }

    public static function getModuleModel($module) {
        $file = self::getModulePath($module) . '/' . strtolower($module) . '.php';

        if (!file_exists($file))
            $file = null;

        return $file;
    }

    public static function getModuleView($module, $view=null) {
        $file = self::getModulePath($module) . '/view/' . ($view?$view:'default') . '.php';

        if (!file_exists($file))
            $file = null;

        return $file;
    }
}
