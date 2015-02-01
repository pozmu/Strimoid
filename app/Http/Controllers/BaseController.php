<?php namespace Strimoid\Http\Controllers;

use Closure;
use Auth, Str;
use Illuminate\Routing\Controller;
use Strimoid\Models\Notification;
use Strimoid\Models\User;

class BaseController extends Controller {

    protected function sendNotifications($targets,
        Closure $callback, User $sourceUser = null)
    {
        $sourceUser = $sourceUser ?: Auth::user();

        if (is_array($targets))
        {
            $uniqueUsers = $targets;
        }
        else
        {
            preg_match_all('/@([a-z0-9_-]+)/i', $targets, $mentionedUsers, PREG_SET_ORDER);

            $uniqueUsers = [];

            foreach ($mentionedUsers as $mentionedUser)
            {
                if ( ! isset($mentionedUser[1])
                    || in_array(Str::lower($mentionedUser[1]), $uniqueUsers))
                {
                    break;
                }

                $uniqueUsers[] = Str::lower($mentionedUser[1]);
            }
        }

        if ( ! $uniqueUsers) return;

        $notification = new Notification();
        $notification->sourceUser()->associate($sourceUser);

        foreach ($uniqueUsers as $uniqueUser)
        {
            $user = User::shadow($uniqueUser)->first();

            if ($user && $user->getKey() != Auth::id()
                && !$user->isBlockingUser($sourceUser))
            {
                $notification->addTarget($user);
            }
        }

        $callback($notification);
        $notification->save();
    }

}
