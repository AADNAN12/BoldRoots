<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CmsPagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_cms_pages,admin')->only(['index']);
        $this->middleware('permission:create_cms_pages,admin')->only(['create', 'store']);
        $this->middleware('permission:edit_cms_pages,admin')->only(['edit', 'update']);
        $this->middleware('permission:delete_cms_pages,admin')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = CmsPage::ordered()->get();

        return view('admin.cms-pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.cms-pages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'slug' => 'nullable|string|max:255|unique:cms_pages,slug',
                'content' => 'nullable|string',
                'is_active' => 'boolean',
                'order' => 'nullable|integer',
            ]);

            $data = $request->all();

            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['title']);
            }

            $data['is_active'] = $request->has('is_active');

            CmsPage::create($data);

            return redirect()->route('admin.cms-pages.index')->with('success', 'Page créée avec succès');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la création de la page: '.$e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CmsPage $cmsPage)
    {
        return view('admin.cms-pages.edit', compact('cmsPage'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CmsPage $cmsPage)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'slug' => 'nullable|string|max:255|unique:cms_pages,slug,'.$cmsPage->id,
                'content' => 'nullable|string',
                'is_active' => 'string',
                'order' => 'nullable|integer',
            ]);

            $data = $request->all();

            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['title']);
            }

            $data['is_active'] = $data['is_active'] == 'on'? true : false;
            // dd($data['is_active']);

            $cmsPage->update($data);

            return redirect()->route('admin.cms-pages.index')->with('success', 'Page mise à jour avec succès');
        } catch (\Exception $e) {
            dd($e->getMessage());

            return redirect()->back()->with('error', 'Erreur lors de la mise à jour de la page: '.$e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CmsPage $cmsPage)
    {
        $cmsPage->delete();

        return redirect()->route('admin.cms-pages.index')->with('success', 'Page supprimée avec succès');
    }
}
