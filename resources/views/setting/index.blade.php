@extends('includes_admin.mainlayout')
@section('content')

    <style>
        .setting-copy-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .setting-copy-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            min-height: 36px;
            padding: 7px 12px;
            border-radius: 6px;
            white-space: nowrap;
        }

        .setting-content-text {
            margin-bottom: 0;
            line-height: 1.9;
            overflow-wrap: anywhere;
        }
    </style>

    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <div class="page-header-left">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i
                                            data-feather="home"> </i> {{trans('admin.dashboard')}} </a></li>
                                <li class="breadcrumb-item active"> {{$setting->title}}

                                </li>
                            </ol>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Right sidebar Ends-->
        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row starter-main">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header setting-copy-header">
                            <h5>{{$setting->title}}</h5>
                            <button class="btn btn-primary setting-copy-btn" type="button" id="copy-setting-content">
                                <i class="fa fa-copy"></i>
                                <span>@lang('admin.copy')</span>
                            </button>
                        </div>
                        <div class="card-body">
                            <ul>
                                <li>
                                    <p class="setting-content-text" id="setting-copy-content">{{ $setting->content }}</p>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var copyButton = document.getElementById('copy-setting-content');
            var content = document.getElementById('setting-copy-content');

            if (!copyButton || !content) {
                return;
            }

            copyButton.addEventListener('click', function () {
                var text = content.innerText.trim();

                function markCopied() {
                    var label = copyButton.querySelector('span');
                    var oldText = label ? label.innerText : '';

                    if (label) {
                        label.innerText = "{{ app()->getLocale() == 'ar' ? 'تم النسخ' : 'Copied' }}";
                    }

                    setTimeout(function () {
                        if (label) {
                            label.innerText = oldText;
                        }
                    }, 1500);
                }

                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(text).then(markCopied);
                    return;
                }

                var textarea = document.createElement('textarea');
                textarea.value = text;
                textarea.setAttribute('readonly', 'readonly');
                textarea.style.position = 'fixed';
                textarea.style.top = '-9999px';
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
                markCopied();
            });
        });
    </script>
@endsection
