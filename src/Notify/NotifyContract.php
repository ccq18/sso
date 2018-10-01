<?php

namespace Ccq18\Notify;

use Illuminate\Notifications\Notification;

interface NotifyContract
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $instance Notification
     * @return void
     */
    public function notify( $instance);

    /**
     * Send the given notification immediately.
     *
     * @param  mixed  $instance Notification
     * @param  array|null  $channels
     * @return void
     */
    public function notifyNow( $instance, array $channels = null);

    /**
     * Get the notification routing information for the given driver.
     *
     * @param  string  $driver
     * @return mixed
     */
    public function routeNotificationFor($driver);
}