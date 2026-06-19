<div class="mt-3">
    {{-- Show all comments here --}}
    @if ($post->comments->isNotEmpty())
    <hr>
        {{-- call relationship to retrieve all comments under a post (check Post Model) --}}
        <li class="list-group">
            @foreach ($post->comments->take(3) as $comment)
            {{-- take() - limit the number of display --}}
                <li class="list-group-item border-0 p-0 mb-3">
                    <a href="{{ route('profile.show', $comment->user->id) }}" class="text-decoration-none text-dark fw-bold">{{ $comment->user->name }}</a>
                    &nbsp;
                    <p class="d-inline hw-light">{{ $comment->body }}</p>

                    <form action="{{ route('comment.destroy', $comment->id) }}" method="post">
                        @csrf
                        @method('DELETE')

                        <span class="text-uppercase text-muted xsmall">{{ date('M d, Y', strtotime($comment->created_at)) }}</span>

                        {{-- If the AUTH user is the owner of the COMMENT, show delete btn --}}
                        @if (Auth::user()->id === $comment->user->id)
                            &middot;
                            <button type="submit" class="border-0 bg-transparent text-danger p-0 xsmall">Delete</button>                        
                        @endif
                    </form>
                </li>
            @endforeach
            @if ($post->comments->count() > 3)
                <li class="list-group-item border-0 pw-0 pt-0">
                    <a href="{{ route('post.show', $post->id) }}" class="text-decoration-none small">
                        View all {{ $post->comments->count()}} comments
                    </a>
                </li>
            @endif
        </li>
    @endif

    <form action="{{ route('comment.store', $post->id) }}" method="post">
        @csrf

        <div class="input-group">
            <textarea name="comment_body{{ $post->id }}" cols="30" rows="1" class="form-control form-control-sm" placeholder="Add a comment...">{{ old('comment_body' . $post->id) }}</textarea>
            <button type="submit" class="btn btn-outline-secondary btn-sm" title="Post"><i class="fa-regular fa-paper-plane"></i></button>
        </div>
        {{-- Error --}}

        @error('comment_body' . $post->id)
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </form>
</div>