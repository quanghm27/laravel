@extends('admin.layout.index')

@section('content')
<div id="page-wrapper">
		<div class="container-fluid">
        	<form method="POST" action="{!! route('payOff.store') !!}">
				<div class="form-group">
					<label>Thanh Toan</label>
					<input class="form-control" name="userId" placeholder="Please enter user Id" />
					<input class="form-control" name="productId" placeholder="Please enter product Id" />
					<input class="form-control" name="quantity" placeholder="Please enter quantity" />
				</div>
				<input type="hidden" name="_token" value="{{csrf_token()}}">
				<button type="submit" class="btn btn-default">Add</button>
				<button type="reset" class="btn btn-default">Reset</button>
			<form>     
		</div>
</div>
@endsection