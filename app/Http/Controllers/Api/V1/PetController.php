<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use Illuminate\Http\Request;
use App\Http\Requests\V1\PetRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\V1\PetResource;
use Illuminate\Support\Facades\Validator;

class PetController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth:api', ['except' => ['index', 'show']]);
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PetResource::collection(Pet::latest()->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PetRequest $request)
    {
        $request->validated();

        $user = Auth::user();

        $pet = new Pet();
        $pet->user()->associate($user);
        $pet->family()->associate($request->input('family'));
        $url_image = $this->upload($request->file('image'));
        $pet->image = $url_image;
        $pet->name = $request->input('name');
        $pet->description = $request->input('description');

        $res = $pet->save();

        if ($res) {
            return response()->json(['message' => 'Pet create succesfully'], 201);
        }
        return response()->json(['message' => 'Error to create pet'], 500);
    }
    
    private function upload($image)
    {
        $path_info = pathinfo($image->getClientOriginalName());
        $pet_path = 'images/pet';

        $rename = uniqid() . '.' . $path_info['extension'];
        $image->move(public_path() . "/$pet_path", $rename);
        return "$pet_path/$rename";
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pet  $pet
     * @return \Illuminate\Http\Response
     */
    public function show(Pet $pet)
    {
        return new PetResource($pet);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pet  $pet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pet $pet)
    {
        Validator::make($request->all(), [
            'title' => 'max:191',
            'image' => 'image|max:1024',
            'description' => 'max:2000',
        ])->validate();

        if (Auth::id() !== $pet->user->id) {
            return response()->json(['message' => 'You don\'t have permissions'], 403);
        }

        if (!empty($request->input('title'))) {
            $pet->title = $request->input('title');
        }
        if (!empty($request->input('description'))) {
            $pet->description = $request->input('description');
        }
        if (!empty($request->file('image'))) {
            $url_image = $this->upload($request->file('image'));
            $pet->image = $url_image;
        }

        $res = $pet->save();

        if ($res) {
            return response()->json(['message' => 'Pet update succesfully']);
        }

        return response()->json(['message' => 'Error to update pet'], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pet  $pet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pet $pet)
    {
        $res = $pet->delete();

        if ($res) {
            return response()->json(['message' => 'Pet delete succesfully']);
        }

        return response()->json(['message' => 'Error to update pet'], 500);
    }
}
