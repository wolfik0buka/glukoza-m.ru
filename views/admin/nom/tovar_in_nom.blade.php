<tr id="str{{ $result->id }}">
    <td>{{ str_repeat('0', 5 - strlen($result->id)) . $result->id }} </td>
    <td>
        <img src="/img/resize/resize.php?src=/img/catalog/pic_{{ $result->id }}.jpg&w=60&q=100">
    </td>
    <td>{{ $result->name }}</td>
    <td class="price">
        <input value="{{ number_format($result->price, 2, ',', ' ') }}" type="text">
    </td>
    <td>
        <input value="{{ $result->sale }}" type="checkbox" id="sale{{ $result->id }}"
               onChange="saleSwitch({{ $result->id }}, this.value)" {{ $sale }}>
    </td>
    <td>
        <input value="{{ $result->hit }}" type="checkbox" id="hit{{ $result->id }}"
               onChange="hitSwitch({{ $result->id }}, this.value)" {{ $hit }}>
    </td>
    <td>
        <input value="{{ $result->podzakaz }}" type="checkbox" id="podzakaz{{ $result->id }}"
               onChange="podzakazSwitch({{ $result->id }}, this.value)" {{ $podzakaz }}>
    </td>
    <td>
        <a class="btn btn-sm btn-primary" href="/admin_new/index.php?page=nom&id={{ $result->id }}">
            <i class="fa fa-pencil"></i>
        </a>
        <a class="btn btn-sm btn-danger" onClick="delTovar({{ $result->id }})">
            <i class="fa fa-times"></i>
        </a>
    </td>
</tr>