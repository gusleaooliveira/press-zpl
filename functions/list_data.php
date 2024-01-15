<?php
function calculate($text, $position)
{
    $cont = substr_count($text, "\n");
    $position += ($cont == 0) ? 55 : 35 * $cont;
    return $position;
}

function writeRightColumn($data2, $y, $positionY)
{
    $cont = substr_count($data2, "\n");
    $response = "";

    if ($cont == 0) {
        $response = "\t ^FO{$y},{$positionY} ^CI28 ^A0N,25,25 ^FB500,5,0 ^FD    {$data2}   ^FS \n";
    } else {
        $linhas = explode("\n", $data2);
        foreach ($linhas as $linha) {
            $response .= "\t ^FO{$y},{$positionY} ^CI28 ^A0N,25,25 ^FB500,5,0 ^FD  {$linha}   ^FS  \n";
            $positionY += 30;
        }
    }

    return $response;
}

function listData($data1, $data2, $positionY)
{
    $x = 20;
    $y = 315;

    return "^FO{$x},{$positionY} ^CI28 ^A0N,25,25 ^FB500,5,0 ^FD  {$data1}  ^FS 
" . writeRightColumn($data2, $y, $positionY);
}
?>