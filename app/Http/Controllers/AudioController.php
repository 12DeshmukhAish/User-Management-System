<?php

namespace App\Http\Controllers;

use App\Models\AudioFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
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
            
            // Store file in the audio directory within public disk
            $filePath = 'audio/' . $filename;
            
            // Store the file
            if (!Storage::disk('public')->putFileAs('audio', $file, $filename)) {
                throw new \Exception('Failed to store file');
            }

            // Get audio duration using getID3
            $fullPath = Storage::disk('public')->path($filePath);
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
            Log::error('Error uploading audio file: ' . $e->getMessage());
            return back()->with('error', 'Error processing audio file: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $audioFile = AudioFile::findOrFail($id);
            
            // Get the file path
            $filePath = $audioFile->file_path;
            
            // Check if file exists and delete it
            if (!empty($filePath)) {
                $fullPath = Storage::disk('public')->path($filePath);
                
                if (File::exists($fullPath)) {
                    // Try to delete using PHP's unlink
                    if (!@unlink($fullPath)) {
                        throw new \Exception('Failed to delete physical file');
                    }
                    Log::info('Physical file deleted successfully', ['path' => $fullPath]);
                }
                
                // Also try to delete using Storage facade as backup
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }

            // Delete the database record
            $audioFile->delete();
            
            // If this is an AJAX request, return JSON response
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Audio file deleted successfully'
                ]);
            }

            return redirect()->route('audio.index')
                ->with('success', 'Audio file deleted successfully.');

        } catch (\Exception $e) {
            Log::error('Error in audio file deletion', [
                'error' => $e->getMessage(),
                'id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            
            // If this is an AJAX request, return JSON response
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting audio file: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Error deleting audio file: ' . $e->getMessage());
        }
    }
}