<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoomController extends Controller
{
    public function index()
    {
        return view('admin.rooms.index', [
            'rooms' => Room::withCount('schedules')->orderBy('code')->paginate(10),
        ]);
    }

    public function create()
    {
        return view('admin.rooms.form', ['item' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        Room::create($this->validated($request));

        return redirect()->route('admin.rooms.index')->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function edit(Room $room)
    {
        return view('admin.rooms.form', ['item' => $room]);
    }

    public function update(Request $request, Room $room): RedirectResponse
    {
        $room->update($this->validated($request, $room));

        return redirect()->route('admin.rooms.index')->with('success', 'Ruangan berhasil diperbarui.');
    }

    public function destroy(Room $room): RedirectResponse
    {
        if ($room->schedules()->exists()) {
            return back()->with('error', 'Ruangan masih dipakai di jadwal, tidak bisa dihapus.');
        }

        $room->delete();

        return redirect()->route('admin.rooms.index')->with('success', 'Ruangan berhasil dihapus.');
    }

    private function validated(Request $request, ?Room $room = null): array
    {
        return $request->validate([
            'code' => ['required', 'string', 'max:20', Rule::unique('rooms', 'code')->ignore($room)],
            'name' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'integer', 'min:1', 'max:1000'],
        ]);
    }
}
