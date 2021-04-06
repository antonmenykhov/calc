<?php
require_once __DIR__ . '/SimpleXLSX.php';

function getBk6()
{
    class A
    {

    }

    ;

    
    if ($xlsx = SimpleXLSX::parse('table.xlsx')) {
        $massiv = $xlsx->rows(0);
        $currentArr = new A;
        $currentVar = new A;
        $currentCost = new A;
        $fullArr = [];
        for ($i=0; $i<=count($massiv); $i++) {
            if ((($massiv[$i][1] === "Раздел") or ($i==count($massiv)))){
                $oldArr = clone $currentArr;
                array_push($fullArr, $oldArr );
                $currentArr->name = $massiv[$i][0];
                $currentArr->arr = [];
            }elseif
             (($massiv[$i][2] === "Варианты") or ($massiv[$i][2] === "Общее") or ($massiv[$i][2] === "Количество") or ($massiv[$i][2] === "Опции"))
            {
                $oldVar = clone $currentVar;
                array_push($currentArr->arr, $oldVar);
                $currentVar->name=$massiv[$i][1];
                $currentVar->type=$massiv[$i][2];
                $currentVar->arr = [];
            }else{
                $currentCost->name = $massiv[$i][1];
                $currentCost->price = $massiv[$i][7];
                $currentCost->number = $massiv[$i][5];
                $oldCost = clone $currentCost;
                array_push($currentVar->arr, $oldCost);
            }

            
           
        }
        return $fullArr;
    } else {
        echo SimpleXLSX::parseError();
    }
}

function getBk8()
{
    class A
    {

    }

    ;
    $vars = new A;
    $kolvo = new A;
    $names = new A;
    if ($xlsx = SimpleXLSX::parse('table.xlsx')) {
        $massiv = $xlsx->rows();
        for ($i = 0; $i < count($massiv); $i++) {
            $key = $massiv[$i][3];
            $vars->$key = $massiv[$i][11];
            $kolvo->$key = $massiv[$i][10];
            $names->$key = $massiv[$i][2];

        }
        $all = array($vars, $kolvo, $names);
        return $all;
    } else {
        echo SimpleXLSX::parseError();
    }
}

echo json_encode(getBk6());
?>