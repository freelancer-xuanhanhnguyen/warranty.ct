@isset($order->expired)
    @if ($order->expired)
        <span class="badge bg-warning" data-bs-toggle="tooltip"
              title="Đã hết bảo hành vào ngày {{ $order->end_date?->format(FORMAT_DATE) }}">Hết bảo hành</span>
    @else
        <span class="badge bg-info" data-bs-toggle="tooltip"
              title="Ngày bảo hành tiếp theo là {{ $order->next_date?->format(FORMAT_DATE) }} (tính từ ngày {{ $order->old_date?->format(FORMAT_DATE) }})">
            Còn bảo hành
        </span>
    @endif
@endisset
