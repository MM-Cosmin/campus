<?php

namespace Modules\Zoom\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Zoom\Repositories\Eloquents\ReportRepository;
use Modules\Zoom\Repositories\Eloquents\VirtualClassRepository;
use Modules\Zoom\Repositories\Eloquents\VirtualMeetingRepository;
use Modules\Zoom\Repositories\Eloquents\ZoomRepository;
use Modules\Zoom\Repositories\Interfaces\ReportRepositoryInterface;
use Modules\Zoom\Repositories\Interfaces\VirtualClassRepositoryInterface;
use Modules\Zoom\Repositories\Interfaces\VirtualMeetingRepositoryInterface;
use Modules\Zoom\Repositories\Interfaces\ZoomRepositoryInterface;

class ZoomRepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ZoomRepositoryInterface::class, ZoomRepository::class);
        $this->app->bind(VirtualClassRepositoryInterface::class, VirtualClassRepository::class);
        $this->app->bind(VirtualMeetingRepositoryInterface::class, VirtualMeetingRepository::class);
        $this->app->bind(ReportRepositoryInterface::class, ReportRepository::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
