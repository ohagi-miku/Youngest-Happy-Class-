@extends('layouts.app')
 
@section('title', 'Create New Message')
 
@section('content')
    <div class="container w-50 mx-auto">
        <form action="#" method="post">
            @csrf
            <div class="input-group">
                <select name="receiver" id="receiver" class="form-select">
                    <option value="" selected disabled>To ...</option>
                    @foreach ($friend_users as $user)
                        <option value="{{$user->id}}">{{$user->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="input-group mt-3">
                <input type="text" name="message" id="message" class="form-control form-control-sm" placeholder="new message...">
                <button type="submit" class="btn btn-outline-primary btn-sm" title="Send Message"><i class="fa-solid fa-arrow-right"></i></button>
            </div>
        </form>

    </div>
@endsection
 