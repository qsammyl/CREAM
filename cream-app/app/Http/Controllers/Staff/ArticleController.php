<?php

namespace App\Http\Controllers\Staff;

use App\Enums\ArticleStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleFormRequest;
use App\Models\Article;
use App\Support\ActivityLogger;
use App\Support\HtmlSanitizer;
use App\Support\ImageProcessor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Article::class);

        $articles = Article::query()->with('author')
            ->when($request->filled('q'), fn ($q) => $q->where('title', 'like', '%'.$request->string('q').'%'))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->latest('updated_at')
            ->paginate(25)
            ->withQueryString();

        return view('staff.articles.index', [
            'articles' => $articles,
            'statuses' => ArticleStatus::options(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Article::class);

        return view('staff.articles.form', [
            'article' => new Article(['status' => ArticleStatus::Draft, 'allow_comments' => true]),
            'statuses' => ArticleStatus::options(),
        ]);
    }

    public function store(ArticleFormRequest $request, ImageProcessor $processor): RedirectResponse
    {
        $this->authorize('create', Article::class);

        $data = $this->payload($request);
        $data['author_id'] = $request->user()->id;
        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;

        if ($cover = $this->handleCover($request, $processor)) {
            $data = array_merge($data, $cover);
        }

        $article = Article::create($data);
        ActivityLogger::log('create', $article);

        return redirect()->route('staff.articles.edit', $article)->with('flash', 'Article created.');
    }

    public function edit(Article $article): View
    {
        $this->authorize('update', $article);

        return view('staff.articles.form', [
            'article' => $article,
            'statuses' => ArticleStatus::options(),
        ]);
    }

    public function update(ArticleFormRequest $request, Article $article, ImageProcessor $processor): RedirectResponse
    {
        $this->authorize('update', $article);

        $data = $this->payload($request);
        $data['updated_by'] = $request->user()->id;

        if ($cover = $this->handleCover($request, $processor)) {
            $processor->delete([$article->cover_path, $article->cover_path_sm]);
            $data = array_merge($data, $cover);
        }

        $article->update($data);
        ActivityLogger::log('update', $article);

        return redirect()->route('staff.articles.edit', $article)->with('flash', 'Article saved.');
    }

    public function destroy(Article $article): RedirectResponse
    {
        $this->authorize('delete', $article);

        $article->delete();
        ActivityLogger::log('delete', $article);

        return redirect()->route('staff.articles.index')->with('flash', 'Article deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    protected function payload(ArticleFormRequest $request): array
    {
        $data = $request->safe()->except(['cover', 'body', 'allow_comments']);
        $data['body'] = HtmlSanitizer::article($request->input('body'));
        $data['allow_comments'] = $request->boolean('allow_comments');

        if ($request->input('publish')) {
            $data['status'] = ArticleStatus::Published->value;
        }
        $becomingPublished = ($data['status'] ?? null) === ArticleStatus::Published->value;
        $alreadyPublished = $request->route('article')?->published_at;
        if ($becomingPublished && ! $alreadyPublished) {
            $data['published_at'] = now();
        }

        return $data;
    }

    /**
     * @return array<string, string>|null
     */
    protected function handleCover(ArticleFormRequest $request, ImageProcessor $processor): ?array
    {
        if (! $request->hasFile('cover')) {
            return null;
        }

        $res = $processor->process($request->file('cover')->getRealPath(), 'articles');

        return ['cover_path' => $res['path'], 'cover_path_sm' => $res['path_sm']];
    }
}
