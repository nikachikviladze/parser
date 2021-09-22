<?php

namespace App\Http\Controllers;

use DB;
use Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DataExport;
use Storage;
use App\Http\Requests\FileUploadRequest;
use App\Http\Requests\DownloadRequest;
use Facades\App\Repository\XmlExportRepository;
use Facades\App\Repository\TextExportRepository;
use Helper;

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

        Helper::cleanDatabase();

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
    
                Helper::cleanDatabase();
          
            } catch (\Exception $e) {
                return Response::json('SQL File error', 400);
            }
            
        }

        if($request->type=='txt'){
            return TextExportRepository::dataExport($results);
        }
        if($request->type=='xml'){
            return XmlExportRepository::dataExport($results);
        }

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
