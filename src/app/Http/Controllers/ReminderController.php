<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreReminderRequest;
use App\Http\Requests\UpdateReminderRequest;
use App\Models\Reminder;
use Validator;
use App\Http\Resources\ReminderCollection;
use App\Http\Resources\ReminderResource;
use Auth;

class ReminderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Validator::make($request->all(), [
            'limit' => 'nullable|integer'
        ])->validate();

        $limit = $request->input('limit', 10);
        $query = Reminder::where('user_id',Auth::user()->id)->limit($limit)->orderBy('remind_at','asc');
        $reminderCollection = new ReminderCollection($query->get());
        $data = [
            'reminders' => $reminderCollection,
            'limit' => $limit
        ];
        return successResponse($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReminderRequest $request)
    {
        $reminder = Reminder::create([
            'user_id' => Auth::user()->id,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'remind_at' => $request->input('remind_at'),
            'event_at' => $request->input('event_at')
         ]);
         $data = new ReminderResource($reminder);
         return successResponse($data, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $reminder = Reminder::find($id);
        $data = new ReminderResource($reminder);
        return successResponse($data, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReminderRequest $request,  $id)
    {
       
        $reminderData = Reminder::find($id);
        Reminder::where('id',$id)->update([
            'user_id' => Auth::user()->id,
            'title' => $request->input('title',$reminderData->title),
            'description' => $request->input('description',$reminderData->description),
            'remind_at' => $request->input('remind_at',$reminderData->remind_at),
            'event_at' => $request->input('event_at',$reminderData->event_at)
         ]);
         $reminderNewData = Reminder::find($id);
         $data = new ReminderResource($reminderNewData);
         return successResponse($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Reminder::where('id',$id)->delete();
        return response()->json(['ok'=>true]);
    }
}
