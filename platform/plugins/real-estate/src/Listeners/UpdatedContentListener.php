<?php

namespace Srapid\RealEstate\Listeners;

use Srapid\Base\Events\UpdatedContentEvent;
use Srapid\RealEstate\Models\Account;
use Srapid\RealEstate\Repositories\Interfaces\AccountActivityLogInterface;
use Exception;

class UpdatedContentListener
{
    /**
     * Handle the event.
     *
     * @param UpdatedContentEvent $event
     * @return void
     */
    public function handle(UpdatedContentEvent $event)
    {
        try {
            if ($event->data->id &&
                $event->data->author_type === Account::class &&
                auth('account')->check() &&
                $event->data->author_id == auth('account')->id()
            ) {
                app(AccountActivityLogInterface::class)->createOrUpdate([
                    'action'         => 'your_property_updated_by_admin',
                    'reference_name' => $event->data->name,
                    'reference_url'  => route('public.account.properties.edit', $event->data->id),
                ]);
            }
        } catch (Exception $exception) {
            info($exception->getMessage());
        }
    }
}
