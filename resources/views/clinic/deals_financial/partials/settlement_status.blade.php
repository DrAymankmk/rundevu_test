@php
    $status = $row['settlement_status'] ?? 'none';
    $pendingCount = (int) ($row['pending_count'] ?? 0);
    $completedCount = (int) ($row['completed_count'] ?? 0);
    $pendingDues = (float) ($row['pending_dues'] ?? 0);
    $completedDues = (float) ($row['completed_dues'] ?? 0);
@endphp

@if($status === 'none')
    <span class="text-muted">@lang('deals_financial.status_none_detail')</span>
@elseif($status === 'pending')
    <span class="badge bg-warning text-dark">@lang('deals_financial.status_pending_label')</span>
    <div class="small mt-1">
        {{ __('deals_financial.status_pending_detail', [
            'count' => $pendingCount,
            'amount' => number_format($pendingDues, 2),
        ]) }}
    </div>
@elseif($status === 'transfer_completed')
    <span class="badge bg-success">@lang('deals_financial.status_completed_label')</span>
    <div class="small mt-1">
        {{ __('deals_financial.status_completed_detail', [
            'count' => $completedCount,
            'amount' => number_format($completedDues, 2),
        ]) }}
    </div>
@else
    <span class="badge bg-info text-dark">@lang('deals_financial.status_mixed_label')</span>
    <div class="small mt-1">
        {{ __('deals_financial.status_mixed_detail', [
            'pending_count' => $pendingCount,
            'pending_amount' => number_format($pendingDues, 2),
            'completed_count' => $completedCount,
            'completed_amount' => number_format($completedDues, 2),
        ]) }}
    </div>
@endif
