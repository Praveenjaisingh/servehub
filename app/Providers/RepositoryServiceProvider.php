<?php

namespace App\Providers;

use App\Repositories\Availability\AvailabilityContract;
use App\Repositories\Availability\AvailabilityEloquent;
use App\Repositories\Booking\BookingContract;
use App\Repositories\Booking\BookingEloquent;
use App\Repositories\Dashboard\DashboardContract;
use App\Repositories\Dashboard\DashboardEloquent;
use App\Repositories\Notification\NotificationContract;
use App\Repositories\Notification\NotificationEloquent;
use App\Repositories\ProviderProfile\ProviderProfileContract;
use App\Repositories\ProviderProfile\ProviderProfileEloquent;
use App\Repositories\Review\ReviewContract;
use App\Repositories\Review\ReviewEloquent;
use App\Repositories\Service\ServiceContract;
use App\Repositories\Service\ServiceEloquent;
use App\Repositories\ServiceCategory\ServiceCategoryContract;
use App\Repositories\ServiceCategory\ServiceCategoryEloquent;
use App\Repositories\User\UserContract;
use App\Repositories\User\UserEloquent;
use App\Services\AdminDashboard\AdminDashboardInterface;
use App\Services\AdminDashboard\AdminDashboardService;
use App\Services\Auth\AuthInterface;
use App\Services\Auth\AuthService;
use App\Services\Availability\AvailabilityInterface;
use App\Services\Availability\AvailabilityService;
use App\Services\Booking\BookingInterface;
use App\Services\Booking\BookingService;
use App\Services\Notification\NotificationInterface;
use App\Services\Notification\NotificationService;
use App\Services\ProviderProfile\ProviderProfileInterface;
use App\Services\ProviderProfile\ProviderProfileService;
use App\Services\Review\ReviewInterface;
use App\Services\Review\ReviewService;
use App\Services\Service\ServiceInterface;
use App\Services\Service\ServiceService;
use App\Services\ServiceCategory\ServiceCategoryInterface;
use App\Services\ServiceCategory\ServiceCategoryService;
use App\Services\User\UserInterface;
use App\Services\User\UserService;
use Illuminate\Support\ServiceProvider;

/**
 * Binds every Repository Contract to its concrete Eloquent implementation,
 * and every Service Interface to its concrete Service implementation.
 * Controllers only ever type-hint the Service Interface, and Services only
 * ever type-hint the Repository Contract - this is what makes the
 * controller -> interface -> service -> contract -> eloquent chain
 * swappable/testable.
 */
class RepositoryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        // Repositories: Contract -> Eloquent
        UserContract::class => UserEloquent::class,
        ProviderProfileContract::class => ProviderProfileEloquent::class,
        ServiceCategoryContract::class => ServiceCategoryEloquent::class,
        ServiceContract::class => ServiceEloquent::class,
        AvailabilityContract::class => AvailabilityEloquent::class,
        BookingContract::class => BookingEloquent::class,
        ReviewContract::class => ReviewEloquent::class,
        NotificationContract::class => NotificationEloquent::class,
        DashboardContract::class => DashboardEloquent::class,

        // Services: Interface -> Service
        AuthInterface::class => AuthService::class,
        UserInterface::class => UserService::class,
        ProviderProfileInterface::class => ProviderProfileService::class,
        ServiceCategoryInterface::class => ServiceCategoryService::class,
        ServiceInterface::class => ServiceService::class,
        AvailabilityInterface::class => AvailabilityService::class,
        BookingInterface::class => BookingService::class,
        ReviewInterface::class => ReviewService::class,
        NotificationInterface::class => NotificationService::class,
        AdminDashboardInterface::class => AdminDashboardService::class,
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        //
    }
}
