@extends('layouts.app')

@section('title', 'Admin: Categories')

@section('content')
<form action="{{ route('admin.categories.store') }}" method="post" class="mb-4">
        @csrf
        <div class="row g-2 align-items-center">
            <div class="col-sm-4"> 
                <input type="text" name="name" class="form-control" placeholder="Add a category" required autofocus>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-plus"></i> Add
                </button>
            </div>
        </div>
    </form>
    <table class="table table-hover align-middle bg-white border text-secondary w-75 text-center">
        <thead class="small table-warning text-secondary">
            <tr>
                <th>#</th>
                <th>NAME</th>
                <th>COUNT</th>
                <th>LAST UPDATE</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($all_categories as $category)
                <tr>
                    <td>
                        @if ($category->id !== 6)
                            {{ $category->id }}
                        @endif
                    </td>
                    <td>
                        {{ $category->name }}

                        @if ($category->id === 6)
                            <div class="text-secondary" style="font-size: 0.75rem;">
                                Hidden posts are not included
                            </div>
                        @endif
                    </td>
                    <td>{{ $category->categoryPost->count() }}</td>
                    <td>{{ $category->updated_at }}</td>
                    <td>
                        @if ($category->id !== 6)
                            <div class="d-flex gap-2 justify-content-end">
                                
                                {{-- 1. Edit  --}}
                                <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#edit-category-{{ $category->id }}" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </button>

                                {{-- 2. Delete --}}
                                <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#delete-category-{{ $category->id }}" title="Delete">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>

                            </div>
                        @endif

                        @include('admin.categories.modals.status')
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        {{ $all_categories->links() }}
    </div>
@endsection