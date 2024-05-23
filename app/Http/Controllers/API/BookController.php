<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Models\Book;
use OpenApi\Annotations as OA;
use Validator;

/**
 * Class BookController.
 * 
 * @author Billy <billy.422023018@civitas.ukrida.ac.id>
 */
class BookController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/books",
     *      tags={"book"},
     *      summary="Display a listing of items",
     *      operationId="index",
     *      @OA\Response(
     *          response="200",
     *          description="successful",
     *          @OA\JsonContent()
     *      )
     * )
     */
    public function index()
    {
        return Book::get();
    }

    /**
     * @OA\Post(
     *      path="/api/books",
     *      tags={"book"},
     *      summary="Store a newly created item",
     *      operationId="store",
     *      @OA\Response(
     *          response=400,
     *          description="Invalid input",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful",
     *          @OA\JsonContent()
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Request body description",
     *          @OA\JsonContent(
     *              ref="#/components/schemas/Book",
     *              example={
     *                  "title": "Ketika Cinta Bertasbih",
     *                  "author": "Bambang Sutejo",
     *                  "publisher": "Erlangga",
     *                  "publication_year": "2016",
     *                  "cover": "https://tse4.mm.bing.net/th?id=OIP.8M-LvH9_prdEitEAYOEX0gHaEy&pid=Api&P=0&h=180",
     *                  "description": ""Ketika Cinta Bertasbih" is a popular Indonesian novel written by Habiburrahman El Shirazy, published in 2007. It tells the story of Khairul Azzam, a dedicated and principled young man studying at Al-Azhar University in Cairo. After his father's death, Azzam takes on the responsibility of supporting his family back in Indonesia while continuing his studies. He works as a tempeh and meatball maker to earn money.,
     *              }
     *          )
     *      )
     * )
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|unique:books',
                'author' => 'required|max:100',
            ]);

            if ($validator->fails()) {
                throw new HttpException(400, $validator->messages()->first());
            }

            $book = new Book;
            $book->fill($request->all())->save();
            return $book;

        } catch (\Exception $exception) {
            throw new HttpException(400, "Invalid data: {$exception->getMessage()}");
        }
    }

    /**
     * @OA\Get(
     *      path="/api/books/{id}",
     *      tags={"book"},
     *      summary="Display the specified item",
     *      operationId="show",
     *      @OA\Response(
     *          response=404,
     *          description="Item not found",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Invalid input",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID of item that needs to be displayed",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      )
     * )
     */
    public function show($id)
    {
        $book = Book::find($id);
        if (!$book) {
            throw new HttpException(404, "Item not found");
        }
        return $book;
    }

    /**
     * @OA\Put(
     *      path="/api/books/{id}",
     *      tags={"book"},
     *      summary="Update the specified item",
     *      operationId="update",
     *      @OA\Response(
     *          response=404,
     *          description="Item not found",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Invalid input",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID of item that needs to be updated",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Request body description",
     *          @OA\JsonContent(
     *              ref="#/components/schemas/Book",
     *              example={
     *                  "title": "Ketika Cinta Bertasbih",
     *                  "author": "Bambang Sutejo",
     *                  "publisher": "Erlangga",
     *                  "publication_year": "2016",
     *                  "cover": "https://tse4.mm.bing.net/th?id=OIP.8M-LvH9_prdEitEAYOEX0gHaEy&pid=Api&P=0&h=180",
     *                  "description": ""Ketika Cinta Bertasbih" is a popular Indonesian novel written by Habiburrahman El Shirazy, published in 2007. It tells the story of Khairul Azzam, a dedicated and principled young man studying at Al-Azhar University in Cairo. After his father's death, Azzam takes on the responsibility of supporting his family back in Indonesia while continuing his studies. He works as a tempeh and meatball maker to earn money.,
     *              }
     *          )
     *      )
     * )
     */
    public function update(Request $request, $id)
    {
        $book = Book::find($id);
        if (!$book) {
            throw new HttpException(404, "Item not found");
        }

        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|unique:books',
                'author' => 'required|max:100',
            ]);

            if ($validator->fails()) {
                throw new HttpException(400, $validator->messages()->first());
            }

            $book->fill($request->all())->save();
            return response()->json(['message' => 'Updated successfully'], 200);

        } catch (\Exception $exception) {
            throw new HttpException(400, "Invalid data: {$exception->getMessage()}");
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/books/{id}",
     *      tags={"book"},
     *      summary="Remove the specified item",
     *      operationId="destroy",
     *      @OA\Response(
     *          response=404,
     *          description="Item not found",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Invalid input",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID of item that needs to be removed",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      )
     * )
     */
    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        if (!$book) {
            throw new HttpException(404, "Item not found");
        }

        try {
            $book->delete();
            return response()->json(['message' => 'Deleted successfully'], 200);

        } catch (\Exception $exception) {
            throw new HttpException(400, "Invalid data: {$exception->getMessage()}");
        }
    }
}