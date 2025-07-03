<x-mail::message>
# Phiếu bảo hành mới #00008

<x-mail::button :url="route('admin.services.show', $service->id)">
Xem chi tiết tại đây
</x-mail::button>

Cảm ơn bạn đã mua hàng tại hệ thống của chúng tôi, <br>
<a href="https://sandienlanh.com.vn/">{{ config('app.name') }}</a>
</x-mail::message>
