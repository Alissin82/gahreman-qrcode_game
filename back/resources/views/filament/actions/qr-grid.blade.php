<div>
    <div
        id="page"
        style="
            display: {{ count($items) === 1 ? 'flex' : 'grid' }};
            {{ count($items) === 1
                ? 'align-items:center; justify-content:center; min-height:200px;'
                : 'grid-template-columns: repeat(2, 1fr); gap:1rem; height:50vh; overflow-y:auto;'
            }}
            margin-left: auto;
            margin-right: auto;
            padding: 1rem;
            box-sizing: border-box;
        "
    >
        @foreach ($items as $item)
            <div
                style="
                    position:relative;
                    border:#0a0a0a 1px solid;
                    padding:1rem;
                    border-radius:0.5rem;
                    overflow:hidden;
                    {{ count($items) === 1 ? 'width:100%; max-width:400px; height:auto;' : '' }}
                "
            >
                <img
                    src="{{ $item['src'] }}"
                    alt="QR: {{ $item['label'] }}"
                    style="width:100%; height:auto; display:block;"
                >
                <div style="text-align:center; margin-top:0.5rem;">{{ $item['label'] }}</div>

                <div
                    style="
                        position:absolute;
                        top:0;
                        left:0;
                        width:100%;
                        height:100%;
                        background:rgba(0,0,0,0.5);
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        opacity:0;
                        transition:opacity 0.3s;
                    "
                        onmouseover="this.style.opacity=1"
                        onmouseout="this.style.opacity=0"
                >
                    <a
                        href="{{ $item['src'] }}"
                        download="{{$item['label']}}"
                        style="
                            background:#2563eb;
                            color:white;
                            padding:10px 16px;
                            border-radius:8px;
                            cursor:pointer;
                            border:none;
                            font-size:14px;
                        "
                    >
                        دانلود
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
