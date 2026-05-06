<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Http\Responses\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use Throwable;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $notifications = $request->user()
                ->notifications()
                ->orderByDesc('created_at')
                ->paginate((int) $request->query('per_page', 15));

            $items = collect($notifications->items())
                ->map(fn ($row) => (new NotificationResource($row))->toArray($request))
                ->values()
                ->all();

            return Response::Success([
                'items' => $items,
                'meta' => [
                    'current_page' => $notifications->currentPage(),
                    'last_page' => $notifications->lastPage(),
                    'per_page' => $notifications->perPage(),
                    'total' => $notifications->total(),
                ],
            ], 'تم جلب الإشعارات.', 200);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 500);
        }
    }

    public function unreadCount(Request $request): JsonResponse
    {
        try {
            $count = $request->user()->unreadNotifications()->count();

            return Response::Success(['unread_count' => $count], 'تم جلب العدد.', 200);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 500);
        }
    }

    public function markAsRead(Request $request, string $id): JsonResponse
    {
        try {
            if (! Str::isUuid($id)) {
                return Response::Error([], 'معرّف إشعار غير صالح.', 422);
            }

            $notification = $request->user()->notifications()->where('id', $id)->firstOrFail();
            $notification->markAsRead();

            return Response::Success(
                ['notification' => new NotificationResource($notification)],
                'تم تعليم الإشعار كمقروء.',
                200
            );
        } catch (ModelNotFoundException) {
            return Response::Error([], 'الإشعار غير موجود.', 404);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 500);
        }
    }

    public function markAllAsRead(Request $request): JsonResponse
    {
        try {
            $request->user()->unreadNotifications()->update(['read_at' => now()]);

            return Response::Success([], 'تم تعليم جميع الإشعارات كمقروءة.', 200);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 500);
        }
    }
}
