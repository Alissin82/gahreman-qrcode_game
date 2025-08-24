<div>
    <div class="flex items-center justify-between ">
        <button type="button" style="background:#71717a;padding:8px 12px;border-radius:12px; margin: auto;" onclick="let p = window.open('','_blank').document; p.write(page.innerHTML);">
            آماده سازی برای چاب
        </button>
    </div>
    <div class="space-y-6 printer hide" id="page">
        <div>
            @foreach ($items as $item)
            <div>
                <img src="{{ $item['src'] }}" alt="QR: {{ $item['label'] }}" style="width:100%;image-rendering:pixelated; image-rendering:crisp-edges;">
                <div>{{ $item['label'] }}</div>
            </div>
            <div style="break-after:page"></div>
            @endforeach
        </div>
    </div>
</div>

<style>
    .hide {
        display: none
    }
</style>
