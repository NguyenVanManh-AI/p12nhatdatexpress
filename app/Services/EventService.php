<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Helpers\SystemConfig;
use App\Models\Event\Event;
use App\Models\User;

class EventService
{
    /**
     * get all events
     * @param array $queries = []
     *
     * @return $events
     */
    public function index(array $queries = [])
    {
        $itemsPerPage = (int) data_get($queries, 'items') ?: 10;
        $page = (int) data_get($queries, 'page') ?: 1;

        $keyword = data_get($queries, 'keyword');
        $status = data_get($queries, 'status');

        $params = Helper::array_remove_null($queries);

        $events = Event::with('bussiness.user_detail')
            ->filter($params)
            ->when(data_get($queries, 'user_id'), function ($query, $userId) {
                return $query->where('event.user_id', $userId);
            })
            ->when($keyword, function ($query, $keyword) {
                $query->where('event.event_title', 'LIKE', '%' . $keyword . '%');
            })
            ->when($status != null, function ($query) use ($status) {
                return $query->where('event.is_confirmed', $status);
            })
            ->when(data_get($queries, 'trashed'), function ($query, $trashed) {
                switch ($trashed) {
                    case 'only':
                        return $query->onlyIsDeleted();
                    break;
                }

                return $query;
            })
            ->latest('id')
            ->skip(($page - 1) * $itemsPerPage)
            ->paginate($itemsPerPage);

        return $events;
    }

    /**
     * highlight event
     * @param Event $event
     * @param User $user
     *
     * @return array $data
     */
    public function highlight(Event $event, User $user)
    {
        // should if user is admin not check coin for use in admin
        $data = [
            'message' => 'Mua dịch vụ không thành công!',
            'success' => false,
        ];

        $serviceFee = get_fee_service(7);

        if (!$serviceFee) return $data;

        if ($user->coint_amount < $serviceFee->service_fee) {
            $data['message'] = 'Không đủ coin để sử dụng tính năng nổi bật!';
            return $data;
        }

        $user->transactions()->create([
            'transaction_type' => 'S', // mua dich vu,
            'sevice_fee_id' => $serviceFee->id,
            'coin_amount' => $serviceFee->service_fee,
            'total_coin_amount' => $serviceFee->service_fee,
            'created_at' => time(),
            'created_by' => $user->id
        ]);

        $user->decrement('coint_amount', $serviceFee->service_fee);

        $event->update([
            'highlight_end' => time() + $serviceFee->existence_time,
            'is_highlight' => true,
        ]);

        $data['message'] = 'Mua dịch vụ thành công!';
        $data['success'] = true;

        return $data;
    }

    /**
     * update event
     * @param Event $event
     * @param array $data
     *
     * @return Event $event
     */
    public function update(Event $event, User $user, array $data)
    {
        // changed from not highlight to highlight
        if (!$event->isHighLight() && data_get($data, 'is_highlight')) {
            $this->highlight($event, $user);
        }
        if (data_get($data, 'is_highlight')) {
            unset($data['is_highlight']);
        }

        $imageHeader = data_get($data, 'image_header_url');
        $imageInvitation = data_get($data, 'image_invitation_url');
        unset($data['image_header_url']);
        unset($data['image_invitation_url']);

        $event->fill($data);
        $event->start_date = strtotime(str_replace('/', '-', data_get($data, 'start_date')) . ' ' . data_get($data, 'start_time'));
        $event->updated_at = strtotime('now');

        // save image
        if ($imageHeader && $imageHeader != $event->image_header_url) {
            // remove old image
            removeFile($event->image_header_url);

            $event->image_header_url = base64ToFile(
                $imageHeader,
                SystemConfig::userDirectory(),
                false,
            );
        }
        if ($imageInvitation && $imageInvitation != $event->image_invitation_url) {
            // remove old image
            removeFile($event->image_invitation_url);

            $event->image_invitation_url = base64ToFile(
                $imageInvitation,
                SystemConfig::userDirectory(),
                false,
            );
        }
        $event->save();

        return $event;
    }

    /**
     * create location
     * @param Event $event
     * @param array $data
     *
     * @return EventLocation $location
     */
    public function createLocation(Event $event, array $data)
    {
        $location = $event->location()
            ->create([
                'address' => data_get($data, 'address'),
                'province_id' => data_get($data, 'province_id'),
                'district_id' => data_get($data, 'district_id'),
                'ward_id' => data_get($data, 'ward_id'),
                'map_longtitude' => data_get($data, 'map_longtitude'),
                'map_latitude' => data_get($data, 'map_latitude'),
            ]);

        return $location;
    }

    /**
     * create report
     * @param Event $event
     * @param array $data
     *
     * @return EventReport $report
     */
    public function createReport(Event $event, array $data)
    {
        return $event->reports()
            ->create([
                'user_id' => data_get($data, 'user_id'),
                'report_type' => data_get($data, 'report_type'),
                'report_content' => data_get($data, 'report_content'),
                'report_result' => 1,
                'confirm_status' => 0,
                'report_time' => time(),
            ]);
    }

    /**
     * update event location
     * @param Event $event
     * @param array $data
     *
     * @return EventLocation $location
     */
    public function updateLocation(Event $event, array $data)
    {
        $location = $event->location()
            ->update([
                'address' => data_get($data, 'address'),
                'province_id' => data_get($data, 'province_id'),
                'district_id' => data_get($data, 'district_id'),
                'ward_id' => data_get($data, 'ward_id'),
                'map_longtitude' => data_get($data, 'map_longtitude'),
                'map_latitude' => data_get($data, 'map_latitude'),
            ]);

        return $location;
    }

    /**
     * create rating
     * @param Event $event
     * @param array $data
     *
     * @return EventRating $rating
     */
    public function createRating(Event $event, $data)
    {
        $userId = data_get($data, 'user_id');
        $uniqueCondition = [
            'event_id' => $event->id,
        ];

        $userId
            ? $uniqueCondition['user_id'] = $userId
            : $uniqueCondition['ip'] = data_get($data, 'ip');

        $rating = $event->ratings()
            ->updateOrCreate($uniqueCondition, [
                'star' => data_get($data, 'star'),
                'rating_time' => time(),
            ]);

        return $rating;
    }
}
