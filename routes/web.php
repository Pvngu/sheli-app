<?php

use Examyou\RestAPI\Facades\ApiRoute;
use Illuminate\Broadcasting\BroadcastController;

// Admin Routes
ApiRoute::group(['namespace' => 'App\Http\Controllers\Api'], function () {
    ApiRoute::get('all-langs', ['as' => 'api.extra.all-langs', 'uses' => 'AuthController@allEnabledLangs']);
    ApiRoute::get('lang-trans', ['as' => 'api.extra.lang-trans', 'uses' => 'AuthController@langTrans']);
    ApiRoute::post('change-theme-mode', ['as' => 'api.extra.change-theme-mode', 'uses' => 'AuthController@changeThemeMode']);
    ApiRoute::get('all-users', ['as' => 'api.extra.all-users', 'uses' => 'AuthController@allUsers']);
    ApiRoute::get('get-alphabetical-users', ['as' => 'api.extra.get-alphabetical-users', 'uses' => 'AuthController@getAlphabeticalUsers']);

    // Check visibility of module according to subscription plan
    ApiRoute::post('check-subscription-module-visibility', ['as' => 'api.extra.check-subscription-module-visibility', 'uses' => 'AuthController@checkSubscriptionModuleVisibility']);
    ApiRoute::post('visible-subscription-modules', ['as' => 'api.extra.visible-subscription-modules', 'uses' => 'AuthController@visibleSubscriptionModules']);

    ApiRoute::group(['middleware' => ['api.auth.check']], function () {
        ApiRoute::post('dashboard', ['as' => 'api.extra.dashboard', 'uses' => 'AuthController@dashboard']);
        ApiRoute::post('upload-file', ['as' => 'api.extra.upload-file', 'uses' => 'AuthController@uploadFile']);
        ApiRoute::post('profile', ['as' => 'api.extra.profile', 'uses' => 'AuthController@profile']);
        ApiRoute::post('user', ['as' => 'api.extra.user', 'uses' => 'AuthController@user']);
        ApiRoute::get('timezones', ['as' => 'api.extra.user', 'uses' => 'AuthController@getAllTimezones']);
        // ApiRoute::post('/broadcasting/auth', [BroadcastController::class, 'authenticate']);
    });

    // Routes Accessable to thouse user who have permissions realted to route
    ApiRoute::group(['middleware' => ['api.permission.check', 'api.auth.check', 'license-expire']], function () {
        $options = [
            'as' => 'api'
        ];

        // Imports
        ApiRoute::post('users/import', ['as' => 'api.users.import', 'uses' => 'UsersController@import']);


        // Create Menu Update
        ApiRoute::post('companies/update-create-menu', ['as' => 'api.companies.update-create-menu', 'uses' => 'CompanyController@updateCreateMenu']);

        ApiRoute::get('users/notifications', ['as' => 'api.users.notifications', 'uses' => 'UsersController@getUserNotifications']);
        ApiRoute::get('users/export', ['as' => 'api.users.export', 'uses' => 'UsersController@export']);
        ApiRoute::resource('users', 'UsersController', $options);
        
        ApiRoute::resource('companies', 'CompanyController', ['as' => 'api', 'only' => ['update']]);
        ApiRoute::resource('permissions', 'PermissionController', ['as' => 'api', 'only' => ['index']]);
        ApiRoute::resource('roles', 'RolesController', $options);

        ApiRoute::get('get-individual-conversations', ['as' => 'api.individual-conversations.get-individual-conversations', 'uses' => 'IndividualConversationController@getIndividualConversations']);
        
        ApiRoute::get('get-team-chats', ['as' => 'api.team-chats.get-team-chats', 'uses' => 'TeamChatController@getChats']);
        ApiRoute::get('get-team-messages/{chatId}', ['as' => 'api.team-messages.get-team-messages', 'uses' => 'TeamMessageController@getMessages']);
        ApiRoute::post('send-team-message', ['as' => 'api.team-messages.send-team-message', 'uses' => 'TeamMessageController@sendMessage']);
        ApiRoute::put('team-messages/update-read-status', ['as' => 'api.team-messages.update-read-status', 'uses' => 'TeamMessageController@updateReadStatus']);

        ApiRoute::get('get-user-have-chat/{userId}', ['as' => 'api.team-chats.user-have-chat', 'uses' => 'TeamChatController@userHaveChat']);

        ApiRoute::resource('app-notification-users', 'AppNotificationUserController', $options);

        ApiRoute::get('app-notifications/unread', ['as' => 'api.app-notifications.unread', 'uses' => 'AppNotificationUserController@getUnreadNotifications']);

        ApiRoute::post('app-notifications/{id}/mark-as-read', ['as' => 'api.app-notifications.mark-as-read', 'uses' => 'AppNotificationUserController@markAsRead']);
        ApiRoute::post('app-notifications/mark-all-as-read', ['as' => 'api.app-notifications.mark-all-as-read', 'uses' => 'AppNotificationUserController@markAllAsRead']);

        ApiRoute::get('accidents/export', ['as' => 'api.accidents.export', 'uses' => 'AccidentController@export']);
        ApiRoute::resource('accidents', 'AccidentController', $options);

        ApiRoute::resource('areas', 'AreaController', $options);
        
        // Add courses routes
        ApiRoute::get('courses/export', ['as' => 'api.courses.export', 'uses' => 'CourseController@export']);
        ApiRoute::resource('courses', 'CourseController', $options);

        ApiRoute::get('audits/generated-pdf/{auditXId}', ['as' => 'api.audits.generated-pdf', 'uses' => 'AuditController@downloadAudit']);
        ApiRoute::resource('audits', 'AuditController', $options);

        ApiRoute::resource('documents', 'DocumentController', $options);

        ApiRoute::delete('deleteFile/{fileName}', ['as' => 'api.document.deleteFile', 'uses' => 'DocumentController@deleteFile']);
        
        ApiRoute::resource('enrollments', 'EnrollmentController', $options);
    });
});
