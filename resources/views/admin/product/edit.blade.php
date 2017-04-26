@extends('admin.layout.index')

@section('title')
	{{$data->name}}
@endsection

@section('content')
<div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Product
                            <small>Edit Product: {{$data->name}}</small>
                        </h1>
                    </div>
                    <!-- /.col-lg-12 -->
                    <div class="col-lg-7" style="padding-bottom:120px">
                        @if(session('thongbao'))
                            <div class="alert alert-success">
                                {{session('thongbao')}}
                            </div>
                        @endif
                        <form method="POST" action="{{ route('product.update',$data['id']) }}">
                        	<input type="hidden" name="_token" value="{{ csrf_token() }}" />
							<input type="hidden" name="_method" value="PUT">
							<input type="hidden" name="id" value="{{ $data['id'] }}">
                            <div class="form-group">
                                <label>Product Name</label>
                                <input class="form-control" name="productName" value="{!! old('productName', isset($data) ? $data['name'] : null) !!}" placeholder="Please enter name" />
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" name="productDescription" placeholder="Please enter description">{!! old('productDescription', isset($data) ? $data['description'] : null) !!}</textarea>
                                 
                            </div>
                            <div class="form-group">
                               <div class="input-group col-xs-6">
		                            <span class="input-group-addon">VND</span>
	                                <input type="number" class="form-control" name="productPrice" value="{!! old('productPrice', isset($data) ? $data['price'] : null) !!}" placeholder="Please enter price" />
                            	</div>
                            </div>
                            <div class="form-group">
	                            <div class="input-group col-xs-6">
	                            	<span class="input-group-addon">Amount</span>
	                                <input type="number" class="form-control" name="productQuantity" value="{!! old('productQuantity', isset($data) ? $data['quantity'] : null) !!}" placeholder="Please enter amount" />
	                            </div>
                            </div>
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <button type="submit" class="btn btn-default">Edit</button>
                            <button type="reset" class="btn btn-default">Reset</button>
                        <form>
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
@endsection