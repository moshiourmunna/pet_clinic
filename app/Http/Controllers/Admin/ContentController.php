<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\AdminResources;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ContentController extends Controller
{
    public function index(Request $request, string $resource)
    {
        $cfg = AdminResources::get($resource);
        $q = $cfg['model']::query()->orderBy($cfg['order'], $cfg['dir'] ?? 'asc');
        if ($s = $request->query('q')) {
            $first = array_key_first($cfg['fields']);
            $q->where($first, 'like', "%{$s}%");
        }

        return view('admin.content.index', ['resource' => $resource, 'cfg' => $cfg, 'items' => $q->paginate(15)->withQueryString()]);
    }

    public function create(string $resource)
    {
        $cfg = AdminResources::get($resource);

        return view('admin.content.form', ['resource' => $resource, 'cfg' => $cfg, 'item' => new $cfg['model']()]);
    }

    public function store(Request $request, string $resource)
    {
        $cfg = AdminResources::get($resource);
        $cfg['model']::create($this->data($request, $cfg));

        return redirect()->route('admin.content.index', $resource)->with('ok', 'Saved.');
    }

    public function edit(string $resource, int $id)
    {
        $cfg = AdminResources::get($resource);

        return view('admin.content.form', ['resource' => $resource, 'cfg' => $cfg, 'item' => $cfg['model']::findOrFail($id)]);
    }

    public function update(Request $request, string $resource, int $id)
    {
        $cfg = AdminResources::get($resource);
        $item = $cfg['model']::findOrFail($id);
        $item->update($this->data($request, $cfg, $item));

        return redirect()->route('admin.content.index', $resource)->with('ok', 'Saved.');
    }

    public function destroy(string $resource, int $id)
    {
        $cfg = AdminResources::get($resource);
        $cfg['model']::findOrFail($id)->delete();

        return back()->with('ok', 'Deleted.');
    }

    private function data(Request $request, array $cfg, $item = null): array
    {
        if ($request->has('slug') && blank($request->slug)) {
            $base = $request->input('name_en') ?: $request->input('title_en') ?: Str::random(6);
            $request->merge(['slug' => Str::slug($base) ?: Str::random(8)]);
        }

        $rules = [];
        foreach ($cfg['fields'] as $name => $f) {
            $req = ($f[2] ?? null) === 'required';
            $rules[$name] = match ($f[0]) {
                'number' => [$req ? 'required' : 'nullable', 'numeric', 'min:0'],
                'checkbox' => ['nullable'],
                'image' => [$req && ! $item ? 'required' : 'nullable', 'image', 'max:4096'],
                'select' => [$req ? 'required' : 'nullable', 'in:'.implode(',', array_keys($f[3]))],
                'datetime' => ['nullable', 'date'],
                'textarea', 'lines' => ['nullable', 'string', 'max:50000'],
                default => [$req ? 'required' : 'nullable', 'string', 'max:255'],
            };
            if ($name === 'slug') {
                $rules[$name][] = 'unique:'.(new $cfg['model']())->getTable().',slug'.($item ? ','.$item->id : '');
            }
        }
        // Bilingual: at least one language must be provided.
        foreach ($cfg['one_of'] as $base) {
            $rules["{$base}_en"][] = "required_without:{$base}_bn";
        }
        $request->validate($rules);

        $data = [];
        foreach ($cfg['fields'] as $name => $f) {
            $data[$name] = match ($f[0]) {
                'checkbox' => $request->boolean($name),
                'lines' => array_values(array_filter(array_map('trim', preg_split('/\r?\n/', (string) $request->input($name))))),
                'image' => $request->hasFile($name) ? $request->file($name)->store('uploads', 'public') : ($item?->getRawOriginal($name)),
                'number' => $request->input($name) ?? 0,
                default => $request->input($name),
            };
        }

        return $data;
    }
}
