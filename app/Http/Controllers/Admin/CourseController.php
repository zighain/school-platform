<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::paginate(5);
        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('admin.courses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:30',
            'description' => 'nullable|string|max:100',
            'hours'       => 'required|integer|min:1|max:10',
            'price'       => 'required|numeric|min:100',
            'start_date'  => 'required|date_format:d-m-Y',
            'end_date'    => 'required|date_format:d-m-Y|after_or_equal:start_date',
            'img'         => 'required|image|mimes:jpeg,jpg,png,webp|max:2000',
        ]);

        $data = $request->except('img');
        $data['start_date'] = Carbon::createFromFormat('d-m-Y', $request->start_date)->format('Y-m-d');
        $data['end_date']   = Carbon::createFromFormat('d-m-Y', $request->end_date)->format('Y-m-d');

        if ($request->hasFile('img')) {
            $data['img'] = $this->processImage($request->file('img'));
        }

        Course::create($data);
        return redirect()->route('admin.courses.index')->with('success', 'Курс успешно создан');
    }

    public function edit($id)
    {
        $course = Course::findOrFail($id);
        return view('admin.courses.edit', compact('course'));
    }

    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:30',
            'description' => 'nullable|string|max:100',
            'hours'       => 'required|integer|min:1|max:10',
            'price'       => 'required|numeric|min:100',
            'start_date'  => 'required|date_format:d-m-Y',
            'end_date'    => 'required|date_format:d-m-Y|after_or_equal:start_date',
            'img'         => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2000',
        ]);

        $data = $request->except('img');
        $data['start_date'] = Carbon::createFromFormat('d-m-Y', $request->start_date)->format('Y-m-d');
        $data['end_date']   = Carbon::createFromFormat('d-m-Y', $request->end_date)->format('Y-m-d');

        if ($request->hasFile('img')) {
            if ($course->img) {
                Storage::disk('public')->delete($course->img);
            }
            $data['img'] = $this->processImage($request->file('img'));
        }

        $course->update($data);
        return redirect()->route('admin.courses.index')->with('success', 'Курс успешно обновлен');
    }

    private function processImage($file)
    {
        $path = $file->getRealPath();
        $imageInfo = getimagesize($path);
        $mime = $imageInfo['mime'];

        // Создаем ресурс в зависимости от типа
        $img = match ($mime) {
            'image/png'  => imagecreatefrompng($path),
            'image/webp' => imagecreatefromwebp($path),
            default      => imagecreatefromjpeg($path),
        };

        $width = imagesx($img);
        $height = imagesy($img);
        
        $thumb = imagecreatetruecolor(300, 300);
        
        // Заливка белым фоном для предотвращения черных пятен при прозрачности
        $white = imagecolorallocate($thumb, 255, 255, 255);
        imagefill($thumb, 0, 0, $white);
        
        imagecopyresampled($thumb, $img, 0, 0, 0, 0, 300, 300, $width, $height);
        
        $filename = 'mpic_' . Str::random(10) . '.jpg';
        
        if (!Storage::disk('public')->exists('courses')) {
            Storage::disk('public')->makeDirectory('courses');
        }
        
        imagejpeg($thumb, storage_path('app/public/courses/' . $filename), 90);
        
        imagedestroy($img);
        imagedestroy($thumb);
        
        return 'courses/' . $filename;
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        
        if ($course->orders()->exists()) {
            return redirect()->back()->withErrors(['error' => 'Нельзя удалить курс, на который записаны студенты.']);
        }
        
        if ($course->img) {
            Storage::disk('public')->delete($course->img);
        }
        
        $course->delete();
        return redirect()->route('admin.courses.index')->with('success', 'Курс удален');
    }
}