@extends('layouts.app')

@section('title', '$user->name')

@section('content')
    <div class="row justify-content-center">
        <div class="col-8">
            <form action="{{ route('profile.update') }}" method="post" class="bg-white shadow rounded-3 p-5"
                enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <h2 class="h3 mb-3 fw-light text-muted">Update Profile</h2>

                <div class="row mb-3">
                    <div class="col-4">
                        @if ($user->avatar)
                            <img src="{{ $user->avatar }}" alt="{{ $user->name }}"
                                class="img-thumbnail rounded-circle d-block mx-auto avatar-lg">
                        @else
                            <i class="fa-solid fa-circle-user text-secondary d-block text-center icon-lg"></i>
                        @endif
                    </div>
                    <div class="col-auto align-self-end">
                        <input type="file" name="avatar" id="avatar" class="form-control form-control-sm mt-1"
                            aria-describedby="avatar-info">
                        <div id="avatar-info" class="form-text">
                            Acceptable formats: jpeg, jpg, png, gif only <br>
                            Max file size is 1048kb
                        </div>
                        {{-- Error --}}
                        @error('avatar')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label fw-bold">Name</label>
                    <input type="text" name="name" id="name" class="form-control"
                        value="{{ old('name', $user->name) }}">
                    {{-- Error --}}
                    @error('name')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label fw-bold">E-mail Address</label>
                    <input type="email" name="email" id="email" class="form-control"
                        value="{{ old('email', $user->email) }}">
                    {{-- Error --}}
                    @error('email')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="introduction" class="form-label fw-bold">Introduction</label>
                    <textarea name="introduction" id="introduction" rows="5" class="form-control" placeholder="Describe yourself">{{ old('introduction', $user->introduction) }}</textarea>
                    {{-- Error --}}
                    @error('introduction')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                {{-- Close Friends List Edit --}}
                <div class="mb-5">
                    <button type="button" class="btn btn-sm text-secondary btn-close-friend"
                        style="border: 1px solid #b8e0b8;" data-bs-toggle="modal" data-bs-target="#closeFriendsModal">
                        <i class="fa-solid fa-user-group"></i> Edit Close Friends
                    </button>
                </div>

                <button type="submit" class="btn btn-warning px-5">Save</button>
            </form>
            {{-- Close Friends Modal --}}
            <div class="modal fade" id="closeFriendsModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #d4edda; position: relative;">
                            <h4 class="modal-title w-100 text-center">Close Friends</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                style="position: absolute; right: 1rem;"></button>
                        </div>
                        <div class="modal-body">

                            {{-- 現在の親しい友達一覧 --}}
                            <p class="fw-bold text-muted small">Current Close Friends</p>
                            <div id="current-close-friends">
                                @forelse ($close_friends as $cf)
                                    <div class="row align-items-center mb-2">
                                        <div class="col-auto">
                                            @if ($cf->closeFriendUser->avatar)
                                                <img src="{{ $cf->closeFriendUser->avatar }}"
                                                    class="rounded-circle avatar-sm">
                                            @else
                                                <i class="fa-solid fa-circle-user text-secondary icon-sm"></i>
                                            @endif
                                        </div>
                                        <div class="col ps-0">
                                            <span class="fw-bold">{{ $cf->closeFriendUser->name }}</span>
                                        </div>
                                        <div class="col-auto">
                                            <button type="button" class="btn btn-outline-danger btn-sm btn-remove-friend"
                                                data-id="{{ $cf->close_friend_id }}">
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted small empty-msg">No close friends yet.</p>
                                @endforelse
                            </div>

                            <hr>

                            {{-- 追加できるフォロワー一覧 --}}
                            <p class="fw-bold text-muted small">Add from Followers</p>
                            <div id="followers-section">
                                @forelse ($followers as $follow)
                                    <div class="row align-items-center mb-2">
                                        <div class="col-auto">
                                            @if ($follow->follower->avatar)
                                                <img src="{{ $follow->follower->avatar }}"
                                                    class="rounded-circle avatar-sm">
                                            @else
                                                <i class="fa-solid fa-circle-user text-secondary icon-sm"></i>
                                            @endif
                                        </div>
                                        <div class="col ps-0">
                                            <span class="fw-bold">{{ $follow->follower->name }}</span>
                                        </div>
                                        <div class="col-auto">
                                            <button type="button" class="btn btn-primary btn-sm btn-add-friend"
                                                data-id="{{ $follow->follower_id }}"
                                                data-name="{{ $follow->follower->name }}"
                                                data-avatar="{{ $follow->follower->avatar }}">
                                                Add
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted small empty-followers-msg">No followers to add.</p>
                                @endforelse
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Addボタンを押したとき
            document.addEventListener('click', function(e) {
                if (!e.target.classList.contains('btn-add-friend')) return;

                const btn = e.target;
                const userId = btn.dataset.id;
                const userName = btn.dataset.name;
                const userAvatar = btn.dataset.avatar;

                fetch('/close-friend/store', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            close_friend_id: userId
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (!data.success) return;

                        // "No close friends yet." を消す
                        const emptyMsg = document.querySelector('#current-close-friends .empty-msg');
                        if (emptyMsg) emptyMsg.remove();

                        // Current Close Friendsリストに追加
                        const avatarHtml = userAvatar ?
                            `<img src="${userAvatar}" class="rounded-circle avatar-sm">` :
                            `<i class="fa-solid fa-circle-user text-secondary icon-sm"></i>`;

                        const newItem = document.createElement('div');
                        newItem.className = 'row align-items-center mb-2';
                        newItem.dataset.userId = userId;
                        newItem.innerHTML = `
                <div class="col-auto">${avatarHtml}</div>
                <div class="col ps-0"><span class="fw-bold">${userName}</span></div>
                <div class="col-auto">
                    <button type="button" class="btn btn-outline-danger btn-sm btn-remove-friend" data-id="${userId}">Remove</button>
                </div>`;
                        document.getElementById('current-close-friends').appendChild(newItem);

                        // Addボタンの行を消す
                        btn.closest('.row').remove();
                    });
            });

            // Removeボタンを押したとき
            document.addEventListener('click', function(e) {
                if (!e.target.classList.contains('btn-remove-friend')) return;

                const btn = e.target;
                const userId = btn.dataset.id;
                const userName = btn.closest('.row').querySelector('.fw-bold').textContent;
                const userAvatar = btn.closest('.row').querySelector('img') ?
                    btn.closest('.row').querySelector('img').src :
                    null;

                fetch('/close-friend/destroy', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            close_friend_id: userId
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (!data.success) return;

                        // Current Close Friendsリストから削除
                        btn.closest('.row').remove();

                        // リストが空になったら"No close friends yet."を表示
                        const list = document.getElementById('current-close-friends');
                        if (list.querySelectorAll('.row').length === 0) {
                            const empty = document.createElement('p');
                            empty.className = 'text-muted small empty-msg';
                            empty.textContent = 'No close friends yet.';
                            list.appendChild(empty);
                        }

                        // Add from Followersリストに戻す
                        const avatarHtml = userAvatar ?
                            `<img src="${userAvatar}" class="rounded-circle avatar-sm">` :
                            `<i class="fa-solid fa-circle-user text-secondary icon-sm"></i>`;

                        const restoredItem = document.createElement('div');
                        restoredItem.className = 'row align-items-center mb-2';
                        restoredItem.innerHTML = `
            <div class="col-auto">${avatarHtml}</div>
            <div class="col ps-0"><span class="fw-bold">${userName}</span></div>
            <div class="col-auto">
                <button type="button" class="btn btn-primary btn-sm btn-add-friend"
                    data-id="${userId}"
                    data-name="${userName}"
                    data-avatar="${userAvatar}">
                    Add
                </button>
            </div>`;

                        // "No followers to add."があれば消す
                        const followersSection = document.querySelector('#followers-section');
                        const emptyFollowers = followersSection.querySelector('.empty-followers-msg');
                        if (emptyFollowers) emptyFollowers.remove();

                        followersSection.appendChild(restoredItem);
                    });
            });

        });
    </script>
@endsection
