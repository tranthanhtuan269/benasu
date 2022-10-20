<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\SiteConfig;

class SiteConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $configs = SiteConfig::paginate(5);
        return view('configs.index', ['configs' => $configs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('configs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required',
            'value' => 'required'
        ]);

        $config = new SiteConfig;
        $config->key = $request->key;
        $config->value = $request->value;
        $config->save();

        \Cache::forget('SiteConfigs');

        return redirect()->route('configs.index')->with('success','Config has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $key)
    {
        $config = SiteConfig::where('key', $key)->first();
        if($config){
            return view('configs.show',compact('config'));
        }else{
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(SiteConfig $config)
    {
        return view('configs.edit', compact('config'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SiteConfig $config)
    {
        $request->validate([
            'key' => 'required',
            'value' => 'required',
        ]);

        $input = $request->all();
        $config->update($input);

        return redirect()->route('configs.index')->with('success','Config has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SiteConfig $config)
    {
        $config->delete();
        return redirect()->route('configs.index')->with('success','Config has been deleted successfully');
    }
}
