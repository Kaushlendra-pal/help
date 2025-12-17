php artisan storage:link
 {{-- create page --}}
<form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="text" name="title" placeholder="Title"><br><br>
    <input type="file" name="image"><br><br>
    <button type="submit">Save</button>
</form>



 {{-- store file --}}
 public function store(Request $request)
{
    $request->validate([
        'image' => 'required|image|mimes:jpg,png,jpeg|max:2048'
    ]);

    // store image in uploads folder
    $imagePath = $request->file('image')->store('uploads', 'public');

    Post::create([
        'image' => $imagePath
    ]);

    return redirect()->route('posts.index')->with('success','Post created');
}

 {{-- display page --}}
 <img src="{{ asset('storage/'.$post->image) }}" width="80">

 {{-- Edit page --}}
 <form action="{{ route('posts.update',$post->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <img src="{{ asset('storage/'.$post->image) }}" width="100"><br><br>
    <input type="file" name="image"><br><br>

    <button type="submit">Update</button>
</form>


 {{-- update --}}
 public function update(Request $request, Post $post)
{
    $request->validate([
        'title' => 'required',
        'image' => 'image|mimes:jpg,png,jpeg|max:2048'
    ]);

    if ($request->hasFile('image')) {

        // delete old image
        if ($post->image && Storage::disk('public')->exists($post->image)) {
            Storage::disk('public')->delete($post->image);
        }

        // store new image
        $imagePath = $request->file('image')->store('uploads', 'public');
    } else {
        $imagePath = $post->image;
    }

    $post->update([
        'title' => $request->title,
        'image' => $imagePath
    ]);

    return redirect()->route('posts.index')->with('success','Post updated');
}

 {{-- delete --}}
public function destroy(Post $post)
{
    if ($post->image && Storage::disk('public')->exists($post->image)) {
        Storage::disk('public')->delete($post->image);
    }

    $post->delete();

    return redirect()->route('posts.index')->with('success','Post deleted');
}
























 
 
 {{-- Display Page --}}


     {{-- php artisan storage:link --}} vv important link run 
public function index()
    {
        $posts = Post::all();
        return view('posts.index', compact('posts'));
    }

<table border="1">
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Image</th>
        <th>Action</th>
    </tr>

    @foreach($posts as $post)
    <tr>
        <td>{{ $post->id }}</td>
        <td>{{ $post->title }}</td>
        <td>
            <img src="{{ asset('images/'.$post->image) }}" width="80">
        </td>
        <td>
            <a href="{{ route('posts.show',$post->id) }}">Show</a>
            <a href="{{ route('posts.edit',$post->id) }}">Edit</a>

            <form action="{{ route('posts.destroy',$post->id) }}" method="POST" style="display:inline">
                @csrf
                @method('DELETE')
                <button type="submit">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>

{{--  Delete code --}}
    public function destroy(Post $post)
    {
        if (file_exists(public_path('images/'.$post->image))) {
            unlink(public_path('images/'.$post->image));
        }

        $post->delete();
        return redirect()->route('posts.index')->with('success','Post deleted');
    }


{{--    store --}}
<form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="text" name="title" placeholder="Title">
    <input type="file" name="image">
    <button type="submit">Save</button>
</form>

{{-- Controller  --}}
public function store(Request $request)
{
    $request->validate([
        'image' => 'required|image|mimes:jpg,png,jpeg|max:2048'
    ]);

    $imageName = time().'.'.$request->image->extension();
    $request->image->move(public_path('images'), $imageName);

    Post::create([
        'image' => $imageName
    ]);
    return redirect()->route('posts.index');
}


{{--Update code  --}}

<h2>Edit Post</h2>

<form action="{{ route('posts.update',$post->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <img src="{{ asset('images/'.$post->image) }}" width="100"><br><br>

    <input type="file" name="image"><br><br>

    <button type="submit">Update</button>
</form>

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required',
            'image' => 'image|mimes:jpg,png,jpeg|max:2048'
        ]);

        if ($request->hasFile('image')) {
            if (file_exists(public_path('images/'.$post->image))) {
                unlink(public_path('images/'.$post->image));
            }

            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images'), $imageName);
        } else {
            $imageName = $post->image;
        }

        $post->update([
            'title' => $request->title,
            'image' => $imageName
        ]);

        return redirect()->route('posts.index')->with('success','Post updated');
    }

