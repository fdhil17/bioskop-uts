<?php
namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TicketController extends Controller
{
    // PROVIDER: Semua tiket
    public function index()
    {
        return response()->json([
            'status' => 'success',
            'data' => Ticket::all()
        ]);
    }

    // PROVIDER: Tiket by member_id
    public function byMember($memberId)
    {
        $tickets = Ticket::where('member_id', $memberId)->get();
        return response()->json([
            'status' => 'success',
            'data' => $tickets
        ]);
    }

    // PROVIDER: Tiket by movie_id
    public function byMovie($movieId)
    {
        $tickets = Ticket::where('movie_id', $movieId)
                         ->where('status', 'booked')
                         ->get();
        return response()->json([
            'status' => 'success',
            'data' => $tickets
        ]);
    }

    // CONSUMER: Beli tiket
    public function store(Request $request)
    {
        // Validasi member ke MemberService
        $memberResponse = Http::get('http://localhost:8001/api/members/' . $request->member_id);
        if ($memberResponse->failed()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Member tidak ditemukan'
            ], 404);
        }

        // Validasi film ke MovieService
        $movieResponse = Http::get('http://localhost:8002/api/movies/' . $request->movie_id);
        if ($movieResponse->failed()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Film tidak ditemukan'
            ], 404);
        }

        $movie = $movieResponse->json()['data'];

        // Cek kursi tersedia
        if ($movie['seat_available'] < $request->quantity) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kursi tidak tersedia'
            ], 400);
        }

        // Hitung total harga
        $totalPrice = $movie['price'] * $request->quantity;

        // Simpan tiket
        $ticket = Ticket::create([
            'member_id'   => $request->member_id,
            'movie_id'    => $request->movie_id,
            'quantity'    => $request->quantity,
            'total_price' => $totalPrice,
            'status'      => 'booked',
        ]);

        // Kurangi kursi di MovieService
        Http::patch('http://localhost:8002/api/movies/' . $request->movie_id . '/seat', [
            'change' => -$request->quantity
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Tiket berhasil dibeli',
            'data'    => $ticket,
            'member'  => $memberResponse->json()['data'],
            'movie'   => $movie,
        ], 201);
    }

    // CONSUMER: Batalkan tiket
    public function cancel($id)
    {
        $ticket = Ticket::find($id);
        if (!$ticket || $ticket->status === 'cancelled') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Tiket tidak valid'
            ], 404);
        }

        $ticket->update(['status' => 'cancelled']);

        // Kembalikan kursi ke MovieService
        Http::patch('http://localhost:8002/api/movies/' . $ticket->movie_id . '/seat', [
            'change' => $ticket->quantity
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Tiket berhasil dibatalkan',
            'data'    => $ticket
        ]);
    }
}
