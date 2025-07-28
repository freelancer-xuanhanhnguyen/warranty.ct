@if(isset($data) && !count($data))
    <tr>
        <td class="text-center" colspan="{{$colspan ?? 100}}">Không có dữ liệu</td>
    </tr>
@endif
