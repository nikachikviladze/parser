@extends('master')
@section('content')


<div class="col-md-8">
    <a href="{{url('/')}}">BACK</a>

    <p>Upload Files</p>
    <ul class="list-group"> 
      @foreach (\Storage::files('public/DB') as $file)
      <li class="list-group-item"> {{basename($file) }}  

        <form action="{{route('file_delete', ['name'=>basename($file)])}}" method="post">
            @csrf
            <input type="hidden" name="_method" value="delete" />
            <button type="submit" class="btn btn-sm btn-danger float-right">Delete</button>
        </form>
    
    </li>

      @endforeach
    </ul> 

  </div>

@endsection