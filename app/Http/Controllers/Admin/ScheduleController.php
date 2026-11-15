<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseClass;
use App\Models\Room;
use App\Models\Schedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $dayOrder = 'CASE day';
        foreach (Schedule::DAYS as $i => $day) {
            $dayOrder .= " WHEN '{$day}' THEN ".($i + 1);
        }
        $dayOrder .= ' ELSE 99 END';

        $schedules = Schedule::with(['courseClass.course', 'courseClass.lecturer.user', 'room'])
            ->when($request->filled('day'), fn ($q) => $q->where('day', $request->input('day')))
            ->when($request->filled('room_id'), fn ($q) => $q->where('room_id', $request->integer('room_id')))
            ->orderByRaw($dayOrder)
            ->orderBy('start_time')
            ->paginate(10)
            ->withQueryString();

        return view('admin.schedules.index', [
            'schedules' => $schedules,
            'rooms' => Room::orderBy('code')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.schedules.form', ['item' => null] + $this->options());
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $this->ensureNoConflict($data);

        Schedule::create($data);

        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit(Schedule $schedule)
    {
        return view('admin.schedules.form', ['item' => $schedule] + $this->options());
    }

    public function update(Request $request, Schedule $schedule): RedirectResponse
    {
        $data = $this->validated($request);
        $this->ensureNoConflict($data, $schedule);

        $schedule->update($data);

        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Schedule $schedule): RedirectResponse
    {
        $schedule->delete();

        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil dihapus.');
    }

    private function options(): array
    {
        return [
            'classes' => CourseClass::with(['course', 'lecturer.user'])->latest()->get(),
            'rooms' => Room::orderBy('code')->get(),
        ];
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'class_id' => ['required', 'exists:classes,id'],
            'room_id' => ['required', 'exists:rooms,id'],
            'day' => ['required', Rule::in(Schedule::DAYS)],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ]);
    }

    /**
     * Tolak jadwal yang bentrok: ruangan yang sama atau dosen yang sama
     * pada hari dan jam yang beririsan.
     */
    private function ensureNoConflict(array $data, ?Schedule $ignore = null): void
    {
        $overlap = function ($query) use ($data, $ignore) {
            $query->where('day', $data['day'])
                ->where('start_time', '<', $data['end_time'])
                ->where('end_time', '>', $data['start_time']);

            if ($ignore) {
                $query->where('id', '!=', $ignore->id);
            }
        };

        $roomBusy = Schedule::where('room_id', $data['room_id'])->where($overlap)->exists();

        if ($roomBusy) {
            throw ValidationException::withMessages(['room_id' => 'Ruangan sudah terpakai pada hari & jam tersebut.']);
        }

        $lecturerId = CourseClass::findOrFail($data['class_id'])->lecturer_id;

        $lecturerBusy = Schedule::whereHas('courseClass', fn ($q) => $q->where('lecturer_id', $lecturerId))
            ->where($overlap)
            ->exists();

        if ($lecturerBusy) {
            throw ValidationException::withMessages(['class_id' => 'Dosen pengampu sudah mengajar di jadwal lain pada hari & jam tersebut.']);
        }
    }
}
