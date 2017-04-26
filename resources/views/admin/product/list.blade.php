@extends('admin.layout.index')

@section('title')
	Shop List
@endsection

@section('content')
<div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Product
                            <small>List of Products</small>
                        </h1>
                    </div>
                    <!-- /.col-lg-12 -->
                    @if(session('thongbao'))
                            <div class="alert alert-success">
                                {{session('thongbao')}}
                            </div>
                     @endif
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr align="center">
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Delete</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                       	<?php $stt = 0 ?>
				            @foreach($product as $p)
				        <?php $stt ++ ?>
                                <tr class="odd gradeX" align="center">
                                    <td> {{ $stt }}</td>
                                    <td>{{$p->name}}</td>
                                    <td>{{$p->description}}</td>
                                    <td>{{$p->price}}</td>
                                    <td>{{$p->quantity}}</td>
                                    <td><a href="{{ route('product.edit',$p->id) }}">Edit</a></td>
                                    <td>
						               	<form method="POST" action="{{ route('product.destroy',$p->id) }}">
											<input type="hidden" name="_token" value="{{ csrf_token() }}" />
											<input type="hidden" name="_method" value="DELETE">
											<input type="hidden" name="id" value="{{ $p->id }}">
											<button onclick="return xacnhanxoa('Bạn Có Chắc Muốn Xóa Không')" type="submit" id="delete" class="btn btn-link">Delete</button>
										</form>
					                </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
@endsection