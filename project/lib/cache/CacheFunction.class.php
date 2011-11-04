<?php
class CacheFunction {
    const CLASS_CACHE = "sfFileCache";
    const AUTOMATIC_CLEANING_FACTOR = 0;

    public static function cache($location, $callable, $arguments = array(), $lifetime = 86400) {
      if (sfConfig::get('sf_debug')) {
          return call_user_func_array($callable, $arguments);
      } else {
          $class_cache = self::CLASS_CACHE;
          $cache_dir = sfConfig::get('sf_app_cache_dir').DIRECTORY_SEPARATOR.$location;
          $function_cache = new sfFunctionCache(new $class_cache(array('cache_dir' => $cache_dir,
                                                                       'lifetime' => $lifetime,
                                                                       'automatic_cleaning_factor' => self::AUTOMATIC_CLEANING_FACTOR)));
          return $function_cache->call($callable, $arguments);
      }
    }
}
