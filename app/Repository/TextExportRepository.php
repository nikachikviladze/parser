<?php

namespace App\Repository;
use Response;
use Helper;

class TextExportRepository{

    public static function dataExport($results)
    {

        $text='';
        foreach($results as $key=>$result){
            $text .= $result->post_title. PHP_EOL . PHP_EOL . Helper::stringParser($result->post_content) .PHP_EOL .'____________'. PHP_EOL;
        }
        $myName = "data.txt";
        $headers = ['Content-type'=>'text/plain', 'Content-Disposition'=>sprintf('attachment; filename="%s"', $myName),'X-BooYAH'=>'WorkyWorky'];
        return Response::make($text, 200, $headers);
    }
}
