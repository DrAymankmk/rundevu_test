@extends('includes_admin.mainlayout')
@section('content')

    <!-- Right sidebar Ends-->
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <div class="page-header-left">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i
                                            data-feather="home"> </i> {{ trans('admin.dashboard') }} </a></li>
                                <li class="breadcrumb-item active">@lang('admin.doctor.waiting_list')
                                    ({{ $data['patients_waiting']->total() }})
                                </li>
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
                        @if( count($data['patients_waiting']) > 0)
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="display dataTable" id="basic-1">
                                        <thead>
                                        <tr>
                                            <th style="display: none">#</th>
                                            <th>{{ trans('admin.image') }}</th>
                                            <th>{{ trans('admin.name') }}</th>
                                            <th>{{ trans('admin.age') }}</th>
                                            <th> {{ trans('admin.status') }}</th>
                                            <th> {{ trans('admin.action') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($data['patients_waiting'] as $index=>$waiting)
                                            <tr>
                                                <td style="display: none">{{$index + 1}}</td>
                                                <td><img class="img-80 rounded-circle" src="{{ $waiting->user->image }}"
                                                         alt="{{ $waiting->user->name ?? null }}"
                                                         style="width:80px;height:80px">
                                                </td>
                                                <td>{{ $waiting->user->name ?? null }}</td>
                                                <td>{{ \Carbon\Carbon::parse($waiting->user->dob)->diff(\Carbon\Carbon::now())->y  }}</td>
                                                @if($waiting->reservation_status->name_en == 'Done')
                                                <td style="color: #2358EF;font-weight: bold">{{ app()->getLocale() == 'en' ? $waiting->reservation_status->name_en : $waiting->reservation_status->name_ar }}</td>
                                                @else
                                                <td style="color: #ff0000;font-weight: bold">{{ app()->getLocale() == 'en' ? $waiting->reservation_status->name_en : $waiting->reservation_status->name_ar }}</td>
                                                @endif
                                                <td>
                                                    <form action="{{route('drug-sections',$waiting->id)}}"
                                                          method="get" style="display: inline-block">
                                                        <button class="btn btn-info btn-sm" type="submit"
                                                                data-whatever="@test"
                                                                title="@lang('admin.reservation') {{ $waiting->booking_number }}"><i
                                                                class="fa fa-eye"></i></button>

                                                    </form>
                                                </td>


                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{ $data['patients_waiting']->links() }}
                        @else

                            <h4 class="text-center" style="color: #ff0000">{{ trans('admin.no_data') }} </h4>
                        @endif

                    </div>
                </div>
            </div>


        </div>
    </div>

@endsection
