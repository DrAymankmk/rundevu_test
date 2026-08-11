@extends('includes_admin.mainlayout')
@section('content')

    <!-- Right sidebar Ends-->
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <div class="page-header-left">
                            <h3>اشتراك العملاء</h3>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i
                                                data-feather="home"> </i> الرئيسية </a></li>
                                <li class="breadcrumb-item active">عدد اشتراكات
                                    العملاء {{ count($data['subscribing']) }}</li>
                            </ol>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">

                <div class="col-sm-12">
                    <div class="card">
                        @if( count($data['subscribing']) > 0)
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="display dataTable" id="basic-1">
                                        <thead>
                                        <tr>
                                            <th>رقم المسلسل</th>
                                            <th>البريد الالكترونى</th>
                                            <th>حاله الاشتراك</th>
                                            <th>الاجراء</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($data['subscribing'] as $index=>$subscribe)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $subscribe->email }}</td>
                                                <td>
                                                    @if (auth()->user()->hasPermissionTo('تعديل اشتراك العملاء'))
                                                    <div class="media-body text-left icon-state">
                                                        <label class="switch">
                                                            <input type="checkbox"
                                                                   {{ $subscribe->status == 1 ? 'checked' : '' }}
                                                                   onchange="change_status_subscribe({{ $subscribe->id }},{{ $subscribe->status }})"><span
                                                                    class="switch-state bg-primary"></span>

                                                        </label>
                                                    </div>
                                                        @endif
                                                </td>
                                                <td>
                                                    @if (auth()->user()->hasPermissionTo('حذف اشتراك العملاء'))
                                                    <form action="{{ route('destroy-subscribe', $subscribe->id) }}"
                                                          method="post" style="display: inline-block">
                                                        {{ csrf_field() }}
                                                        {{ method_field('delete') }}
                                                        <button type="submit" class="btn btn-danger delete btn-sm"><i
                                                                    class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
@endif
                                                </td>

                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <h4 class="text-center" style="color: #ff0000"> لا توجد اشتراكات مضافه </h4>
                        @endif


                    </div>
                </div>

            </div>
        </div>
    </div>


    <script>

        function change_status_subscribe(id, value) {
            if (value == 0) {
                value = 1;
            } else {
                value = 0;
            }
            axios.get('update-status-subscribe/' + id + '/' + value)
                .then(function (response) {

                    location.reload();
                })
                .catch(function (error) {
                    console.log(error);
                    alert(error);
                });
        };


    </script>

@endsection
