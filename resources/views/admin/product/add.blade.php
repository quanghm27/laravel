@extends('admin.layout.index')

@section('title')
	Add a product
@endsection

@section('content')
<div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Product
                            <small>Add A Product</small>
                        </h1>
                    </div>
                    <!-- /.col-lg-12 -->
                    <div class="col-lg-7" style="padding-bottom:120px">
                        @if(session('thongbao'))
                            <div class="alert alert-success">
                                {{session('thongbao')}}
                            </div>
                        @endif
                         <form method="POST" action="{!! route('product.store') !!}">
                            <div class="form-group">
                                <label>Product Name</label>
                                <input type="text" class="form-control" name="productName" placeholder="Please enter a name" />
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" name="productDescription" placeholder="Please enter description"></textarea>
                                 
                            </div>
                            <div class="form-group">
                               <div class="input-group col-xs-6">
		                            <span class="input-group-addon">VND</span>
	                                <input type="number" class="form-control" name="productPrice" placeholder="Please enter price" />
                            	</div>
                            </div>
                            <div class="form-group">
	                            <div class="input-group col-xs-6">
	                            	<span class="input-group-addon">Amount</span>
	                                <input type="number" class="form-control" name="productQuantity" placeholder="Please enter amount" />
	                            </div>
                            </div>
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <button type="submit" class="btn btn-default">Add</button>
                            <button type="reset" class="btn btn-default">Reset</button>
                        <form>
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
@endsection