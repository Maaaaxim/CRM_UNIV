<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function showStatuses()
    {
        $statuses = Status::all();
        return view('super-admin.status', compact('statuses'));
    }

    public function createStatus(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:statuses',
            'color' => 'required|unique:statuses',
        ]);

        Status::create([
            'name' => $request->name,
            'color' => $request->color ?? null,
        ]);

        return redirect()->back()->with('success', 'Статус додано!');
    }

    public function deleteStatus($statusId)
    {
        $status = Status::find($statusId);

        if ($status) {
            // обновить все лиды с удаляемым статусом на статус по умолчанию
            Lead::where('status', $statusId)->update(['status' => 1]);

            $status->delete();
            return redirect()->back()->with('success', 'Статус видалений!');
        } else {
            return redirect()->back()->with('errors', 'Статус не найден!');
        }
    }

    public function setColor(Request $request)
    {
        $status = Status::find($request->id);

        $status->color = $request->color;
        $status->save();

        return response()->json([
            'message' => 'Цвет статуса успешно обновлен!',
            'status' => $status
        ]);
    }

}
