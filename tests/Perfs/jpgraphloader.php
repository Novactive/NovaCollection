<?php
/**
 * Novactive Collection.
 *
 * @author    Luke Visinoni <l.visinoni@novactive.us, luke.visinoni@gmail.com>
 * @author    Sébastien Morel <s.morel@novactive.us, morel.seb@gmail.com>
 * @copyright 2017 Novactive
 * @license   MIT
 */
namespace JpGraph;

/**
 * Class JpGraph
 *
 * @package JpGraph
 */
class JpGraph
{
    static $loaded = false;
    static $modules = array ();

    /**
     *
     */
    static function load()
    {
        if (self::$loaded !== true) {
            include_once __DIR__.'/../../vendor/jpgraph/jpgraph/src/jpgraph.php';
            self::$loaded = true;
        }
    }

    /**
     * @param $moduleName
     *
     * @throws \Exception
     */
    static function module($moduleName)
    {
        self::load();
        if (!in_array($moduleName, self::$modules)) {
            $path = __DIR__.'/../../vendor/jpgraph/jpgraph/src/jpgraph_'.$moduleName.'.php';
            if (file_exists($path)) {
                include_once $path;
            } else {
                throw new \Exception('The JpGraphs\'s module "'.$moduleName.'" does not exist');
            }
        }
    }
}
