@extends('includes_admin.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('manage-deals.index') }}">@lang('deals.manage_deals')</a></li>
                <li class="breadcrumb-item active">@lang('deals.assign_deal')</li>
            </ul>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="post" action="{{ route('manage-deals.assign.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">@lang('deals.select_deal')</label>
                            <select name="deal_id" class="form-control" required>
                                <option value="">@lang('deals.select_deal')</option>
                                @foreach($deals as $deal)
                                    <option value="{{ $deal->id }}" {{ (string) old('deal_id') === (string) $deal->id ? 'selected' : '' }}>
                                        {{ $deal->localizedTitle() }} — {{ number_format($deal->price_after, 2) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">@lang('deals.select_patient')</label>
                            <input type="text" id="patient_search" class="form-control" placeholder="@lang('deals.select_patient')" autocomplete="off">
                            <input type="hidden" name="patient_id" id="patient_id" value="{{ old('patient_id') }}" required>
                            <div id="patient_results" class="list-group mt-1"></div>
                            <div id="selected_patient" class="mt-2 text-muted"></div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">@lang('deals.save')</button>
                        <a href="{{ route('manage-deals.index') }}" class="btn btn-light">@lang('deals.cancel')</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    const input = document.getElementById('patient_search');
    const hidden = document.getElementById('patient_id');
    const results = document.getElementById('patient_results');
    const selected = document.getElementById('selected_patient');
    let timer = null;

    input.addEventListener('input', function () {
        clearTimeout(timer);
        const q = this.value.trim();
        hidden.value = '';
        selected.textContent = '';
        if (q.length < 2) {
            results.innerHTML = '';
            return;
        }
        timer = setTimeout(() => {
            fetch(`{{ route('manage-deals.patients') }}?q=${encodeURIComponent(q)}`)
                .then(r => r.json())
                .then(items => {
                    results.innerHTML = '';
                    items.forEach(item => {
                        const a = document.createElement('button');
                        a.type = 'button';
                        a.className = 'list-group-item list-group-item-action';
                        a.textContent = `${item.name} — ${item.phone || item.mobile_number || ''}`;
                        a.addEventListener('click', () => {
                            hidden.value = item.id;
                            selected.textContent = a.textContent;
                            results.innerHTML = '';
                            input.value = item.name;
                        });
                        results.appendChild(a);
                    });
                });
        }, 300);
    });
})();
</script>
@endsection
