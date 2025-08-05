<x-mail::message>
# Kính chào, {{ $order?->customer?->name }}

<p style="color: #718096;margin-bottom: 0">Sản phẩm <b>{{ $order?->product?->name }}</b> sẽ đến hạn bảo hành định kỳ vào ngày mai.</p>
<p style="color: #718096;margin-bottom: 0">Quý khách vui lòng mang sản phảm đến cửa hàng, hoặc gửi sản phẩm cần bảo hành về để kỹ thuật viên trực tiếp kiểm tra và bảo hành cho sản phẩm ạ.</p>

<x-mail::button :url="route('services.request', [$order->id])">
    Tạo yêu cầu bảo hành ngay
</x-mail::button>

<p style="color: #718096;margin-bottom: 0;text-align: center;">Cảm ơn quý khách hàng đã mua hàng tại hệ thống của chúng tôi.</p>
</x-mail::message>
