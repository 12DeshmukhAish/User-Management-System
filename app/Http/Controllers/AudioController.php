<?php
namespace App\Http\Controllers;


use App\Models\AudioFile;
use Illuminate\Http\Request;
use getID3;

class AudioController extends Controller
{
    private $getID3;

    public function __construct()
    {
        $this->getID3 = new getID3;
    }

    public function index()
    {
        $audioFiles = AudioFile::all();
        return view('audio.index', compact('audioFiles'));
    }

    public function create()
    {
        return view('audio.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'audio_file' => 'required|mimes:mp3,wav,ogg|max:10240'  // Max 10MB
        ]);

        try {
            $file = $request->file('audio_file');
            $originalName = $file->getClientOriginalName();
            $filename = time() . '_' . $originalName;
            $filePath = $file->storeAs('public/audio', $filename);
            
            // Get audio duration using getID3
            $fullPath = storage_path('app/' . $filePath);
            $audioInfo = $this->getID3->analyze($fullPath);
            
            $duration = isset($audioInfo['playtime_seconds']) 
                ? round($audioInfo['playtime_seconds'])
                : null;

            AudioFile::create([
                'filename' => $filename,
                'original_name' => $originalName,
                'duration' => $duration,
                'file_path' => $filePath,
                'file_type' => $file->getClientMimeType()
            ]);

            return redirect()->route('audio.index')
                ->with('success', 'Audio file uploaded successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error processing audio file: ' . $e->getMessage());
        }
    }

    public function getDuration($filePath)
    {
        try {
            $audioInfo = $this->getID3->analyze($filePath);
            
            if (isset($audioInfo['playtime_seconds'])) {
                return [
                    'status' => 'success',
                    'duration' => round($audioInfo['playtime_seconds']),
                    'duration_formatted' => $this->formatDuration($audioInfo['playtime_seconds'])
                ];
            }
            
            return [
                'status' => 'error',
                'message' => 'Could not determine audio duration'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    private function formatDuration($seconds)
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $remainingSeconds = $seconds % 60;

        if ($hours > 0) {
            return sprintf("%02d:%02d:%02d", $hours, $minutes, $remainingSeconds);
        }
        
        return sprintf("%02d:%02d", $minutes, $remainingSeconds);
    }

    public function destroy(AudioFile $audioFile)
    {
        try {
            // Delete the physical file
            if (Storage::exists($audioFile->file_path)) {
                Storage::delete($audioFile->file_path);
            }
            
            // Delete the database record
            $audioFile->delete();
            
            return redirect()->route('audio.index')
                ->with('success', 'Audio file deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting audio file: ' . $e->getMessage());
        }
    }
}