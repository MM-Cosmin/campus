<?php

namespace Modules\Gmeet\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Gmeet\Repositories\Eloquents\GmeetEventRepository;
use Modules\Gmeet\Repositories\Eloquents\GmeetReportRepository;
use Modules\Gmeet\Repositories\Eloquents\GmeetSettingsRepository;
use Modules\Gmeet\Repositories\Eloquents\GmeetVirtualClassRepository;
use Modules\Gmeet\Repositories\Eloquents\GmeetVirtualMeetingRepository;
use Modules\Gmeet\Repositories\Eloquents\UploadDocumentRepository;
use Modules\Gmeet\Repositories\Interfaces\GmeetEventRepositoryInterface;
use Modules\Gmeet\Repositories\Interfaces\GmeetReportRepositoryInterface;
use Modules\Gmeet\Repositories\Interfaces\GmeetSettingsRepositoryInterface;
use Modules\Gmeet\Repositories\Interfaces\GmeetVirtualClassRepositoryInterface;
use Modules\Gmeet\Repositories\Interfaces\GmeetVirtualMeetingRepositoryInterface;
use Modules\Gmeet\Repositories\Interfaces\UploadDocumentRepositoryInterface;

class GmeetRepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(GmeetSettingsRepositoryInterface::class, GmeetSettingsRepository::class);
        $this->app->bind(GmeetVirtualClassRepositoryInterface::class, GmeetVirtualClassRepository::class);
        $this->app->bind(GmeetVirtualMeetingRepositoryInterface::class, GmeetVirtualMeetingRepository::class);
        $this->app->bind(GmeetReportRepositoryInterface::class, GmeetReportRepository::class);
        $this->app->bind(UploadDocumentRepositoryInterface::class, UploadDocumentRepository::class);
        $this->app->bind(GmeetEventRepositoryInterface::class, GmeetEventRepository::class);
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
