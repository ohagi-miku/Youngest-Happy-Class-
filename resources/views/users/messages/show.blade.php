@extends('layouts.app')
 
@section('title', 'show messages')
 
@section('content')
    <div class="w-50 mx-auto chat-wrapper d-flex flex-column">

        {{-- ヘッダー --}}
        <div class="chat-header d-flex align-items-center p-3 border-bottom bg-white">
            <a href="{{ route('message.index') }}" class="me-3 text-dark text-decoration-none">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            @if ($partner->avatar)
                <a href="{{ route('profile.show', $partner->id) }}"><img src="{{ $partner->avatar }}" class="rounded-circle avatar-sm me-2"></a>
            @else
                <a href="{{ route('profile.show', $partner->id) }}"><i class="fa-solid fa-circle-user text-secondary icon-sm me-2"></i></a>
            @endif
            <a href="{{ route('profile.show', $partner->id) }}" class="text-decoration-none text-dark"><span class="fw-bold">{{ $partner->name }}</span></a>
        </div>

        {{-- メッセージ履歴 --}}
        <div class="chat-body flex-grow-1 p-3" id="chat-body">
            @foreach ($messages as $message)
                @php $isMine = $message->sender_id === Auth::id(); @endphp
                <div class="d-flex mb-3 {{ $isMine ? 'justify-content-end' : 'justify-content-start' }}">
                    <div class="chat-bubble {{ $isMine ? 'chat-bubble-mine' : 'chat-bubble-theirs' }}">
                        @if ($message->image)
                            <img src="{{ asset('storage/' . $message->image) }}" class="chat-image mb-1" alt="image">
                        @endif
                        @if ($message->message)
                            <div>{{ $message->message }}</div>
                        @endif
                        <div class="chat-time">{{ $message->created_at->format('m-d H:i') }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- フッター入力欄 --}}
        <div class="chat-footer border-top bg-white p-2">
            <form action="{{ route('message.store', $partner->id) }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-center gap-2">
                @csrf

                <label for="image-input" class="mb-0 text-secondary" style="cursor: pointer;">
                    <i class="fa-solid fa-image fs-5"></i>
                </label>
                <input type="file" id="image-input" name="image" accept="image/*" class="d-none" onchange="previewImage(event)">

                <input type="text" name="message" class="form-control" placeholder="new message ..." autocomplete="off">

                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </form>

            {{-- 画像プレビュー --}}
            <div id="image-preview-wrapper" class="mt-2 d-none">
                <img id="image-preview" class="image-preview-thumb">
                <button type="button" class="btn btn-sm btn-link text-danger" onclick="clearImage()">delete</button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // 送信後に最新メッセージ位置までスクロール
        document.addEventListener('DOMContentLoaded', function () {
            const chatBody = document.getElementById('chat-body');
            chatBody.scrollTop = chatBody.scrollHeight;
        });

        function previewImage(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('image-preview').src = e.target.result;
                document.getElementById('image-preview-wrapper').classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        }

        function clearImage() {
            document.getElementById('image-input').value = '';
            document.getElementById('image-preview-wrapper').classList.add('d-none');
        }
    </script>
    @endpush
@endsection
 