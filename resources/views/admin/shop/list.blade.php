@extends('admin.layout.index')

@section('title')
	Shop List
@endsection

@section('content')
<div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Shop
                            <small>List Name of Shop</small>
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
                                <th>Delete</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $stt = 0 ?>
				            @foreach($shop as $sh)
				        <?php $stt ++ ?>
                                <tr class="odd gradeX" align="center">
                                    <td> {{ $stt }}</td>
                                    <td>{{$sh->name}}</td>
                                    <td><a href="{{ route('shop.edit',$sh->id) }}">Edit</a></td>
                                    <td>
						               	<form method="POST" action="{{ route('shop.destroy',$sh->id) }}">
											<input type="hidden" name="_token" value="{{ csrf_token() }}" />
											<input type="hidden" name="_method" value="DELETE">
											<input type="hidden" name="id" value="{{ $sh->id }}">
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