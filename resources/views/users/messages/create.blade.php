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
            <div class="row mt-3 align-middle">
                <div class="col-2">
                    <button class="btn btn-primary btn-sm text-center"><i class="fa-solid fa-camera"></i></button>
                </div>
                <div class="col-10">
                    <div class="input-group">
                        <input type="text" name="message" id="message" class="form-control form-control-sm" placeholder="new message...">
                        <button type="submit" class="btn btn-outline-primary btn-sm" title="Send Message"><i class="fa-solid fa-arrow-right"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
 