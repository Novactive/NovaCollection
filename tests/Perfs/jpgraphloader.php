<?php
/**
 * Novactive Collection.
 *
 * @author    Luke Visinoni <l.visinoni@novactive.us, luke.visinoni@gmail.com>
 * @author    Sébastien Morel <s.morel@novactive.us, morel.seb@gmail.com>
 * @copyright 2017 Novactive
 * @license   MIT
 */
declare(strict_types=1);

namespace JpGraph;

/**
 * Class JpGraph.
 */
class JpGraph
{
    /**
     * @var bool
     */
    public static $loaded = false;

    /**
     * @var array
     */
    public static $modules = [];

    /**
     * Load.
     */
    public static function load(): void
    {
        if (true !== self::$loaded) {
            include_once __DIR__.'/../../vendor/jpgraph/jpgraph/src/jpgraph.php';
            self::$loaded = true;
        }
    }

    /**
     * @param $moduleName
     *
     * @throws \Exception
     */
    public static function module($moduleName): void
    {
        self::load();
        if (!\in_array($moduleName, self::$modules)) {
            $path = __DIR__.'/../../vendor/jpgraph/jpgraph/src/jpgraph_'.$moduleName.'.php';
            if (!file_exists($path)) {
                throw new \Exception('The JpGraphs\'s module "'.$moduleName.'" does not exist');
            }
            include_once $path;
        }
    }
}
