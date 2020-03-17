<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Transcript;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Class HomeController.
 */
class HomeController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $transcripts = Transcript::all();
        return view('frontend.index')->with(['transcripts' => $transcripts, 'ip' => request()->ip()]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function upload(Request $request)
    {
        $filename = $request->file('transcript')->store('public/transcripts');
        $transcript = new Transcript();
        $transcript->title = $request->title;
        $transcript->path = $filename;
        $transcript->save();

        return redirect()->route('frontend.index');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function transcript($id)
    {
        $transcript = Transcript::where('id', $id)->first();
        $contents = Storage::get($transcript->path);
        return view('frontend.transcript')->with(['transcript' => $transcript, 'contents' => $contents]);
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        $transcript = Transcript::where('id', $id)->first();
        if($transcript){
            $transcript->delete();
        }

        return redirect()->route('frontend.index');
    }
}
