<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

class VideoController extends Controller
{
    public function getAllVideos()
    {
        try {
            $videos = Video::all();
            return response()->json($videos);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve videos'], 500);
        }
    }

    public function getVideoById($id)
    {
        try {
            $video = Video::where('video_id', $id)->get();
            return response()->json($video);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve video'], 500);
        }
    }

    public function getMyVideos()
    {
        $user_ID = Auth::user()->id;
        try {
            $video = Video::where('user_id', $user_ID)->get();
            return response()->json($video);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve video'], 500);
        }
    }

    public function createVideo(Request $request)
    {   
        $user_ID = Auth::user()->id;
        error_log($user_ID);
        try {
            $video = new Video;
            $video->title = $request->input('title');
            $video->description = $request->input('description');
            $video->user_id = $user_ID;
            $video->save();

            return response()->json($video);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create video', 'message' => $e->getMessage()], 500);
        }
    }
    

    public function updateVideo(Request $request, $id)
    {
        $title = $request->input('title');
        $description = $request->input('description');

        try {
            $result = Video::where('video_id', $id)->update([
                'title' => $title,
                'description' => $description,
            ]);

            if ($result) {
                return response()->json(['success' => true, 'message' => 'Video updated']);
            } else {
                return response()->json(['error' => 'Failed to update video'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update video', $e], 500);
        }
    }

    public function deleteVideo($id)
    {
        try {
            $result = Video::where('video_id', $id)->delete();

            if ($result) {
                return response()->json(['success' => true, 'message' => 'Video deleted']);
            } else {
                return response()->json(['error' => 'Failed to delete video'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete video'], 500);
        }
    }
}
