@extends('master')
@section('content')
<div class="col-md-6">
    @include('messages')
    <a href="{{route('files')}}">Files Page</a>

    <form class="mb-5" action="/upload" method="post" enctype="multipart/form-data">
    @csrf

    <input type="file" name="file" required>

    <button type="submit" class="btn btn-secondary">Submit</button>
    </form>


    <form @submit.prevent="download" action="download">
        <div class="form-group">
          <label for="exampleFormControlSelect1">File Type</label>
          <select  v-model="data.type" name="type" class="form-control" required>
            <option value="xml">.Xml</option>
            <option value="csv">.CSV</option>
            <option value="txt">.TXT</option>
          </select>
        </div>
        <div class="form-group">
          <label for="exampleFormControlSelect2">Database</label>
          <select v-model="data.db" multiple class="form-control" name="db[]" required>
              @foreach ($files as $file)
              <option value="{{basename($file)}}">{{basename($file) }}</option>                          
              @endforeach
          </select>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Download</button>
        </div>
      </form>  

      <ul class="list-group" v-if="responseURL"> 
          Download Links : 
          <li class="list-group-item"> <small v-if="this.links.length>1">Combine Version : </small> <a :href="this.responseURL">@{{this.responseURL}}</a></li>
          <li class="list-group-item" v-for="(link,index) in this.links" :key="index"><a :href="link">@{{link}}</a></li>
      </ul> 

    
</div>
@endsection