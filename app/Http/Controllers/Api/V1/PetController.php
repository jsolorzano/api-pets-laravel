<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use Illuminate\Http\Request;
use App\Http\Requests\V1\PetRequest;
use Illuminate\Support\Facades\Auth;

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
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pet  $pet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pet $pet)
    {
        //
    }
}
