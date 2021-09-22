<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DataExport;
use Storage;
use App\Http\Requests\FileUploadRequest;
use App\Http\Requests\DownloadRequest;

class PagesController extends Controller
{
    public function index(){

        $files = Storage::files('public/DB');

        return view('main', ['files'=>$files]);
    }
    public function upload(FileUploadRequest $request)
    {        
        $file = $request->file('file');        
        $filename = $file->getClientOriginalName();
        Storage::disk('local')->putFileAs('public/DB/', $file, $filename);

        return redirect()->back()->with('success', 'Success');

    }
    public function download(DownloadRequest $request){

        $results = collect();

        function cleanDB(){
            foreach(\DB::select('SHOW TABLES') as $table) {
                $table_array = get_object_vars($table);
                \Schema::drop($table_array[key($table_array)]);
            }
        }

        foreach ($request->db as $db) {
            try {
                $path = Storage::disk('local')->get('public/DB/'.$db) ;

                DB::unprepared($path);
                // dd(DB::unprepared($path));
    
                $tables =array_map('current', \DB::select('SHOW TABLES')) ;
                $name= null;
                foreach ( $tables as $c ) {
                    (str_contains ($c, '_posts'))?  $name=  $c : null;
                }
    
                $db1 = DB::connection('mysql')->table($name)->select('post_title', 'post_content')->get();
        
                $results = $results->merge($db1);
    
                cleanDB();
          
            } catch (\Exception $e) {
                return Response::json('SQL File error', 400);
            }
            
        }

        function string_parser($string){
            $x = preg_split('/\r\n|\r|\n/', strip_tags($string));
            $x= implode(' ', $x);
            $desc = preg_replace('/[\x00-\x1F\x7F]/', '', $x);
            return $desc;
        }

        // txt export
        if($request->type=='txt'){
            $text='';
            foreach($results as $key=>$result){
                $text .= $result->post_title. PHP_EOL . PHP_EOL . string_parser($result->post_content) .PHP_EOL .'____________'. PHP_EOL;
            }
            $myName = "data.txt";
            $headers = ['Content-type'=>'text/plain', 'Content-Disposition'=>sprintf('attachment; filename="%s"', $myName),'X-BooYAH'=>'WorkyWorky'];
            return Response::make($text, 200, $headers);
        }
        // xml export
        if($request->type=='xml'){
            $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><mydoc></mydoc>');
            $xml->addAttribute('version', '1.0');
            $xml->addChild('datetime', date('Y-m-d H:i:s'));
            
            foreach($results as $result ){
                $content = $xml->addChild('content');
                $content->addChild('title', $result->post_title);
                $content->addChild('description', htmlentities(string_parser($result->post_content), ENT_XML1));
            }
            $xml->saveXML('data.xml');
        
            $response = Response::make($xml->asXML(), 200);
            $response->header('Cache-Control', 'public');
            $response->header('Content-Description', 'File Transfer');
            $response->header('Content-Disposition', 'attachment; filename=data.xml');
            $response->header('Content-Transfer-Encoding', 'binary');
            $response->header('Content-Type', 'text/xml');
            
            return $response;
        }
        // csv export
        if($request->type=='csv'){
            return Excel::download(new DataExport($results), 'data.csv', \Maatwebsite\Excel\Excel::CSV);
        }
    }

    public function files()
    {
        return view('files');
    }
    public function destroy_file($file)
    {
        Storage::delete('public/DB/'.$file);
        return redirect()->back();
    }
}
