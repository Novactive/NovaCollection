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

include __DIR__.'/../bootstrap.php';

$version  = (string)$_SERVER['argv'][1];
$isBigger = (string)$_SERVER['argv'][2];

$php56Content = implode(',', file(__DIR__."/results{$version}.data"));
$content      = "[{$php56Content}]";
$data         = json_decode($content);

JpGraph::load();
JpGraph::module('line');
JpGraph::module('mgraph');

$stepPoints = new Collection(
    $isBigger ? [30000, 50000, 100000, 500000, 1000000, 5000000, 10000000] : [
        10, 100, 250, 500, 750, 1000, 2000, 5000, 10000, 20000, 30000, 40000, 50000,
    ]
);
$types      = new Collection(['Array', 'NovaC']);
$methods    = new Collection(['map', 'filter', 'each', 'combine']);

$dataCollection = new Collection($data);

$mgraph = new MGraph();

foreach ($methods as $index => $method) {
    $graph = new Graph(800, 600);
    $graph->SetScale('intlin');
    $graph->title->Set($method.' - '.$version);
    $graph->SetMarginColor('white');
    $graph->SetFrame(false);
    $graph->SetMargin(30, 50, 30, 30);
    $graph->yaxis->HideZeroLabel();
    $graph->ygrid->SetFill(true, '#EFEFEF@0.5', '#BBCCFF@0.5');
    $graph->xgrid->Show();
    $graph->xaxis->SetTickLabels($stepPoints->toArray());
    $graph->legend->SetShadow('gray@0.4', 5);
    $graph->legend->SetPos(0.1, 0.1, 'left', 'top');
    foreach ($types as $type) {
        $plotsValues = $dataCollection->filter(
            function ($value) use ($method, $type, $stepPoints) {
                return $value->method == $method && $value->type == $type &&
                       in_array($value->iterations, $stepPoints->toArray());
            }
        )->map(
            function ($value) {
                return (float)$value->time;
            }
        )->keyCombine(
            $stepPoints->map(
                function ($value) {
                    return (string)$value;
                }
            )
        );

        $linePlot = new LinePlot($plotsValues->values()->toArray());

        $linePlot->SetLegend($type);
        $graph->Add($linePlot);
    }
    $mgraph->Add($graph, 0, $index * 300);
}
$mgraph->Stroke();
