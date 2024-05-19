<?php

// app/Http/Controllers/DeskController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Desk;

class DeskController extends Controller
{
    public function showDesks()
    {
        $desks = Desk::all();
        return view('super-admin.desk', compact('desks'));
    }

    public function createDesk(Request $request)
    {
        $request->validate([
            'desk' => 'required|unique:desks',
        ]);

        Desk::create([
            'desk' => $request->desk,
        ]);

        return redirect()->back()->with('success', 'Дэск успешно создан!');
    }

    public function deleteDesk($deskId)
    {
        $desk = Desk::find($deskId);

        if ($desk) {
            $desk->delete();
            return redirect()->back()->with('success', 'Дэск успешно удален!');
        } else {
            return redirect()->back()->with('error', 'Дэск не найден!');
        }
    }
}

