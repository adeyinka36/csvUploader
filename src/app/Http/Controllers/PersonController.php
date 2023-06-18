<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonRequest;
use App\Http\Requests\UpdatePersonRequest;
use App\Http\Resources\PersonResource;
use App\Models\Person;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
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
    public function store(PersonRequest $request): JsonResponse
    {
        $person = Person::create($request->validated());
        return response()->json(new PersonResource($person), 201);
    }

    /**
     * Display the specified resource.
     */
        public function show(Person $person): JsonResponse
        {
        return response()->json(new PersonResource($person), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePersonRequest $request, Person $person): JsonResponse
    {
        $person->update($request->validated());
        return response()->json(new PersonResource($person), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Person $person): JsonResponse
    {
        $person->delete();
        return response()->json(null, 204);
    }

    public function upload(Request $request): JsonResponse
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:2048',
        ]);

        // Process the uploaded file
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            Storage::disk('local')->put('uploads'.'/upload.csv', file_get_contents($file));

//            this could be done as a job but i run it synchronously here so that immediate update on its success or failure can be provided to the frontend user
            Artisan::call('import:homeowners', ['file' => $file]);

            return response()->json(['message' => 'File uploaded successfully']);
        }

        // Return an error response if the file is not present
        return response()->json(['error' => 'No file uploaded'], 400);
    }
}
