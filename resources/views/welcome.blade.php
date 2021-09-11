<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>

<div id="app" class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            @if(count($errors)>0)
                @foreach($errors->all() as $error)
                    <div class="alert alert-danger">{{ $error }}</div>
                @endforeach
            @endif
        
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
                      <option value="{{\File::name($file)}}">{{\File::name($file) }}</option>                          
                      @endforeach
                  </select>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Download</button>
                </div>
              </form>  

              <p v-if="responseURL"> Download Link : <a :href="this.responseURL">@{{this.responseURL}}</a></p> 
            

              <img v-if="loader" src="https://c.tenor.com/I6kN-6X7nhAAAAAj/loading-buffering.gif" alt="">
        </div>
    </div>
</div>

<script src="js/vue.js"></script>
<script src="/js/axios.min.js"></script>
<script src="/js/custom.js"></script>

</body>
</html>