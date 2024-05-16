<?php
// app/Http/Controllers/ExampleController.php

namespace App\Http\Api;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
/**
 * @OA\Tag(
 *     name="Users",
 *     description="API for managing users"
 * )
 *
 * @OA\Info(
 *     title="SREGEP API",
 *     version="1.0.0",
 *     description="API registrasi sregep"
 * )
 */
class ExampleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/example",
     *     tags={"Example"},
     *     summary="Get a list of examples",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="description", type="string")
     *             )
     *         )
     *     )
     * )
     */
    public function getExamples()
    {
        // Kode untuk mengambil daftar contoh
    }
}

?>