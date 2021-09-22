@if(Session::has('success'))
<div class="alert alert-success">
	<strong>{{ session::get('success') }}</strong>
</div>
@endif

@if(session('error'))

<div class="alert alert-danger">
	 {{ session('error') }}
</div>
@endif



@if(count($errors)>0)
	@foreach($errors->all() as $error)
			<div class="alert alert-danger">
			   {{ $error }}
			</div>
	@endforeach
@endif
