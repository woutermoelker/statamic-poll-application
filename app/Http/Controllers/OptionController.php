<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Services\VoteService;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function  __construct( private readonly VoteService $service){}

    public function updateVotes(Request $request,Option $option, $action)
    {
        if ($action === 'increment') {
            $option->increment('votes');
        } elseif ($action === 'decrement') {
            $option->decrement('votes');
        }

        return response()->json(['success' => true, 'votes' => $option->votes]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'question_id' => 'required',
        ]);

        $option = new Option([
            'name' => $request->get('name'),
            'question_id' => $request->get('question_id'),
        ]);
        $option->save();
        return json_encode(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'question_id' => 'required',
        ]);

        $option = Option::find($id);
        $option->name = $request->get('name');
        $option->save();
        return json_encode(['success' => true]);
    }

    public function delete($id)
    {
        $option = Option::find($id);
        $option->delete();
        return json_encode(['success' => true]);
    }
}
