<?php

namespace App\Repositories;

/**
 * Class BaseRepository.
 */
class BaseRepository
{
    /**
     * @return mixed
     */
    public function getAll()
    {
        return $this->query()->get();
    }

    /**
     * Get Paginated.
     *
     * @param $per_page
     * @param string $active
     * @param string $order_by
     * @param string $sort
     *
     * @return mixed
     */
    public function getPaginated($per_page, $active = '', $order_by = 'id', $sort = 'asc')
    {
        if ($active) {
            return $this->query()->where('status', $active)
                ->orderBy($order_by, $sort)
                ->paginate($per_page);
        } else {
            return $this->query()->orderBy($order_by, $sort)
                ->paginate($per_page);
        }
    }

    /**
     * @return mixed
     */
    public function getCount()
    {
        return $this->query()->count();
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->query()->find($id);
    }

    /**
     * @return mixed
     */
    public function query()
    {
        return call_user_func(static::MODEL.'::query');
    }

    protected function pushTokenFromRequest($request)
    {
        foreach (['firebase_token', 'fcm_token', 'push_token', 'device_token', 'firebaseToken', 'fcmToken', 'pushToken', 'deviceToken'] as $field) {
            if ($request->filled($field)) {
                return $request->input($field);
            }
        }

        foreach (['firebase-token', 'fcm-token', 'push-token', 'device-token', 'x-firebase-token', 'x-fcm-token', 'x-push-token', 'x-device-token'] as $header) {
            if ($request->headers->has($header)) {
                return $request->headers->get($header);
            }
        }

        return null;
    }

    protected function syncPushToken($model, $request): void
    {
        if (!$model) {
            return;
        }

        $pushToken = $this->pushTokenFromRequest($request);
        if (!$pushToken || $model->firebase_token === $pushToken) {
            return;
        }

        $model->firebase_token = $pushToken;
        $model->save();
    }
}
