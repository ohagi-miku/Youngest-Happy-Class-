@extends('layouts.app')
 
@section('title', 'Message Index')
 
@section('content')
    <div class="container w-75 mx-auto mt-3 align-items-center">
        <h3 class="h1 text-center">Messages</h3>
        <div class="d-flex justify-content-center mt-4">
            <a href="#" class="btn btn-outline-primary border-2 text-decoration-none fw-bold">
                <i class="fa-solid fa-plus"></i> Start New Conversation
            </a>
        </div>
        <div class="mt-5">
            @if ($all_messages->count() > 0)
            <table class="table table-hover bg-light align-middle">
                <tbody>
                    @foreach ($all_messages as $message)
                        @php
                            $isReceiver = $message->receiver_id === Auth::id();
                            $partner = $isReceiver ? $message->sender : $message->receiver;
                        @endphp
                        <tr onclick="window.location='{{ route('message.show', $partner->id) }}'" style="cursor: pointer;">
                            <td class="col-2">
                                @if ($partner->avatar)
                                    <img src="{{ $partner->avatar }}" alt="{{ $partner->name }}" class="rounded-circle avatar-md">
                                @else
                                    <i class="fa-solid fa-circle-user text-secondary icon-md"></i>
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
        </div>
    </div>
@endsection
 