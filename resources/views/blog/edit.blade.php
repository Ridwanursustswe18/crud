<form method="POST" action="{{ route('blogs.update', $blog->id) }}" enctype="multipart/form-data">
    @csrf
    @method('patch')

    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" class="form-control" name="title" value="{{ $blog->title }}">
    </div>
    
    <div class="form-group">
        <label for="description">Content</label>
        <textarea class="form-control" name="description">{{ $blog->description }}</textarea>
    </div>
    <div>
    <img src="{{ asset('storage/'. $blog->image) }}" alt = "{{$blog->title}}" >
    </div>
    <div class="form-group">
        <label for="image">Image</label>
        <input type="file" class="form-control-file" name="image">
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
</form>
