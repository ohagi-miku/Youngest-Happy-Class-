@extends('layouts.app')
 
@section('title', 'Message Index')
 
@section('content')
    <div class="container w-75 mx-auto mt-3 align-items-center messages-container">
        <h3 class="h1 text-center">Messages</h3>
        
        <div class="mt-5">
            @if ($all_messages->count() > 0)
            <table class="table table-hover bg-light align-middle table-fixed-layout">
                <tbody>
                    @foreach ($all_messages as $message)
                        @php
                            $isReceiver = $message->receiver_id === Auth::id();
                            $partner = $isReceiver ? $message->sender : $message->receiver;
                        @endphp
                        <tr onclick="window.location='{{ route('message.show', $partner->id) }}'" style="cursor: pointer;">
                            <td class="col-2">
                                @if ($partner->avatar)
                                    <a href="{{ route('profile.show', $partner->id) }}" onclick="event.stopPropagation()"><img src="{{ $partner->avatar }}" alt="{{ $partner->name }}" class="rounded-circle avatar-md"></a>
                                @else
                                    <a href="{{ route('profile.show', $partner->id) }}" onclick="event.stopPropagation()"><i class="fa-solid fa-circle-user text-secondary icon-md"></i></a>
                                @endif
                            </td>
                            <td class="col-2">{{ $isReceiver ? $partner->name : 'You' }} :</td>
                            <td class="col-6">{{ $message->message }}</td>
                            <td>{{ $message->created_at->format('H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <h3 class="h2 text-center text-secondary">No Conversations yet.</h3>
            @endif

            {{-- 未会話のフォロワー一覧 --}}
            @if ($friend_users->count() > 0)
            <h5 class="mt-4 mb-2">Start new chat</h5>
            <table class="table table-hover bg-light align-middle">
                <tbody>
                    @foreach ($friend_users as $user)
                        <tr onclick="window.location='{{ route('message.show', $user->id) }}'" style="cursor: pointer;">
                            <td class="col-2">
                                @if ($user->avatar)
                                    <a href="{{ route('profile.show', $user->id) }}" onclick="event.stopPropagation()"><img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="rounded-circle avatar-md"></a>
                                @else
                                    <a href="{{ route('profile.show', $user->id) }}" onclick="event.stopPropagation()"></a><i class="fa-solid fa-circle-user text-secondary icon-md"></i>
                                @endif
                            </td>
                            <td class="col-2">{{ $user->name }}</td>
                            <td class="col-6 text-secondary">No messages yet.</td>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
@endsection
 