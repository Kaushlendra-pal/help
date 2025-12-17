            {{-- display Controller code --}}

                  {{-- php artisan storage:link --}} vv important link run 

   public function index()
    {
        $photos = Photo::latest()->get();
        return view('photos.index', compact('photos'));
    }

                           {{-- Store file page Controller --}}
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'images.*' => 'required|image|mimes:jpg,jpeg,png'
        ]);

        $imagePaths = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('photos', 'public');
                $imagePaths[] = $path;
            }
        }

        Photo::create([
            'title' => $request->title,
            'images' => $imagePaths
        ]);

        return redirect()->route('photos.index')->with('success', 'Photos uploaded successfully');
    }

                          {{-- Single user data Show Controller --}}

    public function show(Photo $photo)
    {
        return view('photos.show', compact('photo'));
    }

                        {{-- Edit Controller page is open --}}

    public function edit(Photo $photo)
    {
        return view('photos.edit', compact('photo'));
    }

                     {{-- Update Controller  --}}

    public function update(Request $request, Photo $photo)
    {
        $request->validate([
            'title' => 'required',
            'images.*' => 'image|mimes:jpg,jpeg,png'
        ]);

        $imagePaths = $photo->images;

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('photos', 'public');
                $imagePaths[] = $path;
            }
        }

        $photo->update([
            'title' => $request->title,
            'images' => $imagePaths
        ]);

        return redirect()->route('photos.index')->with('success', 'Photos updated successfully');
    }

                             {{--   Delete controller code --}}
    public function destroy(Photo $photo)
    {
        $photo->delete();
        return redirect()->route('photos.index')->with('success', 'Photo deleted');
    }



                                        {{-- Form blade code --}}
    <form action="{{ route('photos.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="images[]" multiple required><br><br>
    <button type="submit">Upload</button>
</form>


                                   {{-- Display blade code --}}
@foreach($photos as $photo)
    @foreach($photo->images as $img)
        <img src="{{ asset('storage/'.$img) }}" width="100">
    @endforeach

    <br>
    <a href="{{ route('photos.show', $photo->id) }}">View</a>
    <a href="{{ route('photos.edit', $photo->id) }}">Edit</a>

    <form action="{{ route('photos.destroy', $photo->id) }}" method="POST">
        @csrf
        @method('DELETE')
        <button>Delete</button>
    </form>
    <hr>
@endforeach

                                      {{--  Single user show --}}
<h2>{{ $photo->title }}</h2>

@foreach($photo->images as $img)
    <img src="{{ asset('storage/'.$img) }}" width="150">
@endforeach

{{-- Edit blade  --}}
<form action="{{ route('photos.update', $photo->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <p>Old Images:</p>
    @foreach($photo->images as $img)
        <img src="{{ asset('storage/'.$img) }}" width="100">
    @endforeach

    <br><br>
    <input type="file" name="images[]" multiple><br><br>

    <button>Update</button>
</form>
