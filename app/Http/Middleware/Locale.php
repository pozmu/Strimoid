<?php

namespace Strimoid\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Jenssegers\Agent\Facades\Agent;

class Locale
{
    public function __construct(private Guard $auth, private \Illuminate\Foundation\Application $application, private \Illuminate\Contracts\Config\Repository $configRepository)
    {
    }

    public function handle(Request $request, Closure $next)
    {
        if ($this->auth->check() && setting('language') !== 'auto') {
            $locale = setting('language');
        } else {
            $locale = $this->detectLocale();
        }

        $this->application->setLocale($locale);
        Carbon::setLocale($locale);

        return $next($request);
    }

    private function detectLocale()
    {
        $userLocales = Agent::languages();

        foreach (['pl', 'en'] as $locale) {
            if (in_array($locale, $userLocales)) {
                return $locale;
            }
        }

        return $this->configRepository->get('app.locale');
    }
}
