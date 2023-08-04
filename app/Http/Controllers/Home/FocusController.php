<?php

namespace App\Http\Controllers\Home;

use App\Enums\NewLikeTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\News;
use App\Services\FocusService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FocusController extends Controller
{
    private FocusService $focusService;

    public function __construct()
    {
        $this->focusService = new FocusService;
    }

    /**
     * get more news
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function moreNews(Request $request): JsonResponse
    {
        $news = $this->focusService
            ->getListFromQuery($request->all());

        $html = '';
        $group = Group::find($request->group_id); // kien thuc bat dong san
        foreach ($news as $new) {
            $html .= view('components.home.focus.property-item', [
                'property' => $new,
                'group' => $group
            ])->render();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'html' => mb_convert_encoding($html, 'UTF-8', 'UTF-8'),
                'meta' => [
                    'onLastPage' => $news->onLastPage()
                ]
            ]
        ]);
    }

    /**
     * toggle reaction
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleReaction(Request $request): JsonResponse
    {
        $user = Auth::guard('user')->user();
        $id = $request->id;
        $type = $request->type;

        $new = News::showed()
            ->find($id);

        if (!$user || !$new) {
            return response()->json([
                'success' => false,
            ]);
        }

        $toggled = $this->focusService->toggleReaction($new, $user, $type);

        return response()->json([
            'success' => true,
            'message' => ucfirst(($toggled ? '' : 'Bỏ ') . ($type == NewLikeTypeEnum::LIKE ? 'Thích' : 'Không thích'). ' thành công'),
            'data' => [
                'toggled' => $toggled,
                'likesCount' => $new->likes->count(),
                'dislikeCount' => $new->dislikes->count(),
            ]
        ], 200);
    }

    /**
     * get html5lightbox description
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDescription(Request $request): JsonResponse
    {
        $id = $request->id;
        $new = News::showed()
            ->find($id);

        if (!$new) {
            return response()->json([
                'success' => false,
            ]);
        }

        // $new->increment('num_view');
        $new->update([
            'num_view' => $new->num_view + 1
        ]);

        $html = renderLightboxDescription($new);

        return response()->json([
            'success' => true,
            'data' => [
                'html' => mb_convert_encoding($html, 'UTF-8', 'UTF-8'),
                'video_url' => $new->video_url,
            ]
        ], 200);
    }
}
