<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;

class ChatController extends Controller
{
  

    //  Eloquent ka use: Saare users fetch karna (khud ko chhor kar)
    public function index()
    {
        // Eloquent Query: 
        $users = User::where('id', '!=', auth()->id())->get();
        return view('chat.index', compact('users'));
    }

   
    //  Naya Message Save Karna (AJAX ke liye)
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'receiver_id' => 'required|exists:users,id',
        ]);

        // Eloquent Query: Database mein naya message insert karo
        $message = Message::create([
            'sender_id' => auth()->id(),          // Current logged-in user
            'receiver_id' => $request->receiver_id, // Jisko message bhejna hai
            'message' => $request->message,         // Message text
        ]);

        // Eager Loading: Message ke sath sender ki details bhi load karo
        $message->load('sender');

        // JSON Response return karo (Frontend AJAX ke liye)
        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    
    //Purani Chat History Fetch Karna (AJAX ke liye)
     
    public function getMessages($userId)
    {
        // Eloquent Query: Dono users ke beech ke saare messages nikalo
        $messages = Message::where(function($query) use ($userId) {
            // Condition 1: Main ne bheja ho aur usne receive kiya ho
            $query->where('sender_id', auth()->id())
                  ->where('receiver_id', $userId);
        })->orWhere(function($query) use ($userId) {
            // Condition 2: Usne bheja ho aur maine receive kiya ho
            $query->where('sender_id', $userId)
                  ->where('receiver_id', auth()->id());
        })
        ->with('sender') // Eager Loading: Har message ke sath sender ka data bhi fetch karo (N+1 problem avoid karne ke liye)
        ->orderBy('created_at', 'asc') // Purane messages pehle, naye baad mein
        ->get(); // Collection return karo

        return response()->json($messages);
    }
}