<x-mail::message>
# Kính chào, {{ $service?->order?->customer?->name }}

<p style="color: #718096;margin-bottom: 0">Mã phiếu {{ \App\Models\Service::TYPE[$service->type] }}: #{{ $service->code }}</p>
<p style="color: #718096;margin-bottom: 0">Sản phẩm: {{ $service?->order?->product?->name }}</p>
<p style="color: #718096;margin-bottom: 0">Vấn đề sửa chữa: {{ $service->content }}</p>
<p style="color: #718096;margin-bottom: 0">Phụ phí: {{ $service->fee_total }} VND</p>
<p style="color: #718096;margin-bottom: 0">Ghi chú: {{ $service->note }}</p>
<p style="color: #718096;margin-bottom: 0">Trạng thái: {{ \App\Models\ServiceStatus::STATUS[$status?->code ?? 0] }}</p>

<x-mail::button :url="route('services.detail', [$service?->order?->customer?->email, $service->id])">
    Xem chi tiết tại đây
</x-mail::button>

<p style="color: #718096;margin-bottom: 0;text-align: center;">Cảm ơn quý khách hàng đã mua hàng tại hệ thống của chúng tôi.</p>
</x-mail::message>
