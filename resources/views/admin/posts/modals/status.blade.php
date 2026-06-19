@if ($post->trashed())
    {{-- Unhide (表示に戻す) --}}
    <div class="modal fade" id="unhide-post-{{ $post->id }}">
        <div class="modal-dialog">
            <div class="modal-content border-primary">
                <div class="modal-header border-primary">
                    <h3 class="h5 modal-title text-primary">
                        <i class="fa-solid fa-eye"></i> Unhide Post
                    </h3>
                </div>
                <div class="modal-body">
                    Are you sure you want to unhide this post?
                    <div class="mt-3">
                        @if ($post->image)
                        <img src="{{ $post->image }}" alt="post id {{ $post->id }}" class="d-block mx-auto rounded"
                        style="max-width: 100%; max-height: 200px; object-fit: cover;">
                        @endif
                    </div>
                    
                    <div class="mt-3 small text-center">
                        {{ $post->description }}
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <form action="{{ route('admin.posts.unhide', $post->id) }}" method="post">
                        @csrf
                        @method('PATCH')

                        <button type="button" class="btn btn-outline-primary btn-sm"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm">Unhide</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="modal fade" id="hide-post-{{ $post->id }}">
        <div class="modal-dialog">
            <div class="modal-content border-danger">
                <div class="modal-header border-danger">
                    <h3 class="h5 modal-title text-danger">
                        <i class="fa-solid fa-eye-slash"></i> Hide Post
                    </h3>
                </div>
                <div class="modal-body">
                    Are you sure you want to hide this post?
                    <div class="mt-3">
                        @if ($post->image)
                        <img src="{{ $post->image }}" alt="post id {{ $post->id }}" class="d-block mx-auto rounded"
                        style="max-width: 100%; max-height: 200px; object-fit: cover;">
                        @endif
                    </div>
                    
                    <div class="mt-3 small text-center">
                        {{ $post->description }}
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <form action="{{ route('admin.posts.hide', $post->id) }}" method="post">
                        @csrf
                        @method('DELETE')

                        <button type="button" class="btn btn-outline-danger btn-sm"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger btn-sm">Hide</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif
