@extends('layouts.app')
 
@section('title', 'Message Index')
 
@section('content')
    <div class="container w-75 mx-auto mt-3 align-items-center">
        <h3 class="h1 text-center">Messages</h3>
        <div class="d-flex justify-content-center mt-4">
            <a href="{{route('message.create')}}" class="btn btn-outline-primary border-2 text-decoration-none fw-bold">
                <i class="fa-solid fa-plus"></i> Start New Conversation
            </a>
        </div>
        <div class="mt-5">
            @if ($all_messages->count() > 0)
            @foreach ($all_messages as $message)
            <table class="table table-hover bg-light align-middle">
                <tbody>
                    <tr>
                        <td>
                            @if ($message->receiver_id === Auth::user()->id)
                                {{$message->sender->name}}
                            @else
                                {{$message->receiver->name}}
                            @endif
                        </td>
                        <td>{{$message->message}}</td>
                        <td>{{$message->created_at}}</td>
                    </tr>
                </tbody>
            </table>
            @endforeach
            @else
            <h3 class="h2 text-center text-secondary">No Conversations yet.</h3>
            
            @endif
        </div>
    </div>
@endsection
 