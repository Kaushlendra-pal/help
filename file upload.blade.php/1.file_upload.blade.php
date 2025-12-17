    {{-- Display Page --}}

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

    <input type="text" name="title" value="{{ $post->title }}"><br><br>

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

