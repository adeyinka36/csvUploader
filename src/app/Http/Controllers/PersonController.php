<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonRequest;
use App\Http\Requests\UpdatePersonRequest;
use App\Http\Resources\PersonResource;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $persons = Person::paginate(10);

        $response = [
            '_links' => [
                '_self' => [
                    'href' => $persons->url($persons->withQueryString()->currentPage())
                ],
                'next' => [
                    'href' => $persons->hasMorePages() ? $persons->withQueryString()->nextPageUrl() : null
                ],
                'previous' => [
                    'href' => $persons->onFirstPage() ? null : $persons->withQueryString()->previousPageUrl()
                ]
            ],
            'count' => $persons->count(),
            'total' => $persons->total(),
            'data' => PersonResource::collection($persons->items())
        ];

        return response()->json($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PersonRequest $request)
    {
        $person = Person::create($request->validated());
        return response()->json(new PersonResource($person), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Person $person)
    {
        return response()->json(new PersonResource($person), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePersonRequest $request, Person $person)
    {
        $person->update($request->validated());
        return response()->json(new PersonResource($person), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Person $person)
    {
        $person->delete();
        return response()->json(null, 204);
    }

    public function upload(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:2048',
        ]);

        // Process the uploaded file
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            Storage::disk('local')->put('uploads'.'/upload.csv', file_get_contents($file));

            Artisan::call('import:homeowners', ['file' => $file]);

            return response()->json(['message' => 'File uploaded successfully']);
        }

        // Return an error response if the file is not present
        return response()->json(['error' => 'No file uploaded'], 400);
    }
}
