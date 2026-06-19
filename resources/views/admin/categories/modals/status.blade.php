{{-- Delete Modal  --}}
<div class="modal fade" id="delete-category-{{ $category->id }}">
    <div class="modal-dialog">
        <div class="modal-content border-danger">
            <div class="modal-header border-danger">
                <h3 class="h5 modal-title text-danger">
                    <i class="fa-solid fa-trash-can"></i> Delete Category
                </h3>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete  <span class="fw-bold text-dark">"{{ $category->name }}"</span> category? <p>
                <p class="small mb-0">
                    This action will affectall the posts under this category. Posts without a category will fall under Uncategorized.
                </p>
            </div>
            <div class="modal-footer border-0">
                {{-- ルート名は admin.categories.destroy に設定します --}}
                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="post">
                    @csrf
                    @method('DELETE')

                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Edit Modal (カテゴリー編集) --}}
<div class="modal fade" id="edit-category-{{ $category->id }}">
    <div class="modal-dialog">
        <div class="modal-content border-warning">
            <div class="modal-header border-warning">
                <h3 class="h5 modal-title text-warning">
                    <i class="fa-solid fa-pen"></i> Edit Category
                </h3>
            </div>
            {{-- ルート名は admin.categories.update に設定します --}}
            <form action="{{ route('admin.categories.update', $category->id) }}" method="post">
                @csrf
                @method('PATCH')

                <div class="modal-body">
                    <label for="category-name-{{ $category->id }}" class="form-label"></label>
                    {{-- 現在の名前（$category->name）を最初から入力値として入れておきます --}}
                    <input type="text" name="name" id="category-name-{{ $category->id }}" 
                        class="form-control" value="{{ $category->name }}" required>
                </div>
                
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-warning btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning btn-sm">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>