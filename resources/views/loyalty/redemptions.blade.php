@extends('includes_admin.mainlayout')
@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                            <li class="breadcrumb-item active">@lang('main.loyalty_redemptions')</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            @include('loyalty.partials.nav')

            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('main.users')</th>
                            <th>@lang('main.loyalty_coupons')</th>
                            <th>@lang('main.loyalty_code')</th>
                            <th>@lang('main.all_points')</th>
                            <th>@lang('admin.status')</th>
                            <th>@lang('admin.Actions')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data['redemptions'] as $redemption)
                            <tr>
                                <td>{{ $redemption->id }}</td>
                                <td>{{ optional($redemption->user)->name }}</td>
                                <td>{{ optional($redemption->coupon)->service_name_ar }}</td>
                                <td>{{ $redemption->code }}</td>
                                <td>{{ $redemption->points_spent }}</td>
                                <td>{{ $redemption->status }}</td>
                                <td>
                                    @if(in_array($redemption->status, ['pending', 'otp_sent']))
                                        @if($redemption->status === 'pending')
                                            <a href="{{ route('loyalty.redemptions.send-otp', $redemption->id) }}"
                                               class="btn btn-sm btn-warning">@lang('main.loyalty_send_otp')</a>
                                        @endif
                                        <button class="btn btn-sm btn-success" data-toggle="modal"
                                                data-target="#confirmRedemption{{ $redemption->id }}">
                                            @lang('main.loyalty_confirm')
                                        </button>
                                    @else
                                        <span class="text-muted">{{ $redemption->used_at }}</span>
                                    @endif
                                </td>
                            </tr>

                            <div class="modal fade" id="confirmRedemption{{ $redemption->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form method="POST" action="{{ route('loyalty.redemptions.confirm', $redemption->id) }}">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">@lang('main.loyalty_confirm_redemption')</h5>
                                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>OTP</label>
                                                    <input type="text" name="otp_code" class="form-control" maxlength="6" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">@lang('main.loyalty_confirm')</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        </tbody>
                    </table>

                    {{ $data['redemptions']->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
