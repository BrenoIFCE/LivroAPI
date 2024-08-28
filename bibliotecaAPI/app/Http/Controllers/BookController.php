<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;
class BookController extends Controller
{
    public function store(Request $request) : JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'pdf' => 'required|file|mimes:pdf|max:100000', // Máximo de 100MB
        ]);
        $pdfPath = $request->file('pdf')->store('pdfs', 'public');

        $book = Book::create([
            'title' => $request->input('title'),
            'author' => $request->input('author'),
            'pdf_path' => $pdfPath,
        ]);
        return response()->json(['message' => 'Livro criado com sucesso!', 'book' => $book], 201);
    }

        public function index(Request $request)
    {
        // Define a quantidade de itens por página (pode ser passada na request)
        $perPage = $request->input('per_page', 10);

        // Busca os livros de forma paginada
        $books = Book::paginate($perPage);

        // Comentários do usuário para cada livro
        $books = Book::with('comments.user')->paginate(10);
        // Retorna os livros em formato JSON
        return response()->json($books);
    }

    public function showPdf($id)
    {
        $book = Book::findOrFail($id);

        if (!$book->pdf_path) {
            return response()->json(['message' => 'PDF não encontrado.'], 404);
        }

        return response()->file(storage_path('app/public/' . $book->pdf_path));
    }
    public function addComment(Request $request, $bookId)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $book = Book::findOrFail($bookId);

        $comment = new Comment();
        $comment->user_id = auth()->id();
        $comment->book_id = $book->id;
        $comment->comment = $request->comment;
        $comment->save();

        return response()->json(['message' => 'Comentário adicionado com sucesso!']);
    }
}
