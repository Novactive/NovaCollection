<?php
/**
 * Novactive Collection.
 *
 * @author    Luke Visinoni <l.visinoni@novactive.us, luke.visinoni@gmail.com>
 * @author    Sébastien Morel <s.morel@novactive.us, morel.seb@gmail.com>
 * @copyright 2017 Novactive
 * @license   MIT
 */
use JpGraph\JpGraph;
use Novactive\Collection\Collection;
use Novactive\Collection\Factory;
use Novactive\Tests\Perfs\ArrayMethodCollection;
use Novactive\Tests\Perfs\ForeachMethodCollection;

include __DIR__.'/../bootstrap.php';

$version  = (string)$_SERVER['argv'][1];
$fileName = __DIR__."/results{$version}.data";
if (!file_exists($fileName)) {

    echo "Data file does not exist.";
    exit(1);
}
$php56Content = implode(',', file($fileName));
$content      = "[{$php56Content}]";
$data         = json_decode($content);

$configs = file(__DIR__.'/config.conf');

$graphWidth  = 800;
$graphHeight = 400;

$methods = $graphsLimit = [];
foreach ($configs as $config) {
    list($var, $value) = explode('=', $config);
    $value = trim($value, "()\n");
    $value = explode(' ', $value);
    if ($var == 'ITERATIONS') {
        $graphsLimit = [
            array_slice($value, 0, 11),
            array_slice($value, 10, 11),
            array_slice($value, 20, 9),
        ];
    }
    if ($var == 'METHODS') {
        $methods = Factory::create($value);
    }
}

JpGraph::load();
JpGraph::module('line');
JpGraph::module('mgraph');

$secondGraphSteps = Factory::create();
$classes          = Factory::create([Collection::class, ArrayMethodCollection::class, ForeachMethodCollection::class]);

$dataCollection = Factory::create($data);

$createGraph = function ($title, $axis) use ($graphWidth, $graphHeight) {
    $graph = new Graph($graphWidth, $graphHeight);
    $graph->SetScale('intlin');
    $graph->title->Set($title);
    $graph->SetMarginColor('white');
    $graph->SetFrame(false);
    $graph->SetMargin(30, 50, 30, 30);
    $graph->yaxis->HideZeroLabel();
    $graph->ygrid->SetFill(true, '#EFEFEF@0.5', '#BBCCFF@0.5');
    $graph->xgrid->Show();
    $graph->xaxis->SetTickLabels($axis);
    $graph->legend->SetShadow('gray@0.4', 5);
    $graph->legend->SetPos(0.1, 0.1, 'left', 'top');

    return $graph;
};

$mgraph = new MGraph();

foreach ($methods as $yindex => $method) {
    foreach ($graphsLimit as $xindex => $axisList) {
        if (count($axisList) == 0) {
            continue;
        }
        $graph = $createGraph($method.' - '.$version, $axisList);
        foreach ($classes as $class) {
            /** @var Collection $plotsValues */
            $plotsValues = $dataCollection->dump()->filter(
                function ($value) use ($method, $class, $axisList) {
                    return $value->method == $method && $value->type == $class &&
                           in_array($value->iterations, $axisList);
                }
            )->dump()->map(
                function ($value) {
                    return (float)$value->time;
                }
            )->dump()->keyCombine($axisList);

            $linePlot = new LinePlot($plotsValues->values()->toArray());
            $linePlot->SetLegend(end(explode('\\', $class)));
            $graph->Add($linePlot);
        }
        $mgraph->Add($graph, $xindex * $graphWidth, $yindex * $graphHeight);
    }
}
$mgraph->Stroke();
