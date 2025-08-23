<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\UserController;
use App\Models\User;
use App\Notifications\JsonNotification;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if(Auth::check()) {
        return redirect()->route('profile.view');
    }
    return redirect()->route('signup');
});

// Auth routes
Route::get('/signup', [AuthController::class, 'showSignupForm'])->name('signup');
Route::post('/signup', [AuthController::class, 'signup']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.view');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/users/{username}', [UserController::class, 'show'])->name('users.show');

    // Photos
    Route::get('/photos', [PhotoController::class, 'index'])->name('photos.index'); // list all user photos
    Route::get('/photo/{photo_id}', [PhotoController::class, 'view'])->name('photos.view'); // see a post
    Route::get('/photos/upload', [PhotoController::class, 'create'])->name('photos.create'); // show upload form
    Route::post('/photos', [PhotoController::class, 'store'])->name('photos.store'); // save upload
    Route::delete('/photos/{id}', [PhotoController::class, 'destroy'])->name('photos.destroy');

    // Feed (All users)
    Route::get('/feed', [FeedController::class, 'index'])->name('feed.index'); // show feed page
    Route::post('/photos/{photo}/like', [LikeController::class, 'store'])->name('photos.like'); // like a photo
    Route::delete('/photos/{photo}/like', [LikeController::class, 'destroy'])->name('photos.unlike'); // unlike photo
    Route::post('/photos/{photo}/comments', [CommentController::class, 'store'])->name('photos.comment'); // add comment

    // Social
    Route::get('social' , [SocialController::class  , 'index'])->name('social.index');

    // Notifications
    Route::get('notifications' , [NotificationController::class , 'index'])->name('notifications');
    Route::get('notifications/count' , [NotificationController::class , 'count'])->name('notifications.count');
    Route::post('/notifications/read', [NotificationController::class, 'markRead'])->name('notifications.read');

    // Follow / Unfollow
    Route::post('/follow/{id}', [FollowController::class, 'store'])->name('follow.store');
    Route::delete('/unfollow/{id}', [FollowController::class, 'destroy'])->name('follow.destroy');
    Route::get('/users/{id}/followers', [ProfileController::class, 'followers'])->name('profile.followers');
    Route::get('/users/{id}/following', [ProfileController::class, 'following'])->name('profile.following');
    // Accept follow request
    Route::post('/follow/{id}/accept', [FollowController::class, 'accept'])->name('follow.accept');
    // Reject follow request
    Route::delete('/follow/{id}/reject', [FollowController::class, 'reject'])->name('follow.reject');

    // Show all 1:1 conversations (list of users you have chats with)
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [MessageController::class, 'conversation'])->name('messages.conversation');
    Route::post('/messages/{user}', [MessageController::class, 'send'])->name('messages.send');
    Route::post('/messages/{message}/read', [MessageController::class, 'markRead'])->name('messages.read');

    // Live chatting
    Route::get('/messages/fetch/{user}', [MessageController::class, 'fetch'])->name('messages.fetch');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/theme', [SettingsController::class, 'updateTheme'])->name('settings.theme.update');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

//////////////////////
// Dev Routes in web.php
//////////////////////

// TODO 
use Illuminate\Support\Carbon;
Route::get('/todo', function () {
    $today = Carbon::now()->toDateString();

    $rows = [
        ['task' => 'Follow Request', 'status' => 'DONE', 'description' => 'to follow someone they should be able to raise a follow request'],
        ['task' => 'Messaging', 'status' => 'Todo', 'description' => 'add a feature for messaging in the app'],
        ['task' => 'Sharing of Post in Messaging', 'status' => 'Todo','description' => 'users should be able to share posts in messages'],
        ['task' => 'Follow request should be accepted if the acount is public', 'status' => 'Done','description' => 'follow request should be accepted if the account type is public'],
    ];

    $html = "
        <style>
            body { font-family: Arial, sans-serif; background: #f9fafb; padding: 2rem; }
            table { border-collapse: collapse; width: 70%; margin: auto; background: white; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden; }
            th, td { padding: 12px 16px; text-align: left; border-bottom: 1px solid #e5e7eb; }
            th { background: #f3f4f6; font-weight: bold; }
            tr:hover { background: #f9fafb; }
            .status { padding: 4px 8px; border-radius: 6px; font-size: 0.85rem; font-weight: bold; }
            .todo { background: #fee2e2; color: #991b1b; }
            .done { background: #e9fee2ff; colorrgba(27, 153, 29, 1)1b; }
        </style>

        <table>
            <thead>
                <tr>
                    <th>Task</th>
                    <th>Description</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
    ";

    foreach ($rows as $row) {
        $html .= "
            <tr>
                <td>{$row['task']}</td>
                <td>{$row['description']}</td>
                <td><span class='status todo'>{$row['status']}</span></td>
            </tr>
        ";
    }

    $html .= "
            </tbody>
        </table>
    ";

    return $html;
});

// Send a "like" notification
Route::get('/test-notification/like', function () {
    $user = \App\Models\User::first(); // ✅ always exists
    $user->notify(new \App\Notifications\JsonNotification(
        'like',
        'liked your photo',
        2,
        ['post_id' => 123]
    ));
    return 'Like notification sent!';
});


// Send a "comment" notification
Route::get('/test-notification/comment', function () {
    $user = User::first();
    $from = User::find(2);
    $user->notify(new JsonNotification(
        type: 'comment',
        text: ($from?->name ?? 'Someone') . ' commented on your photo',
        fromUserId: $from?->id,
        extra: ['post_id' => 123, 'comment_id' => 456]
    ));

    return 'Comment notification sent!';
});

// Send a "follow" notification
Route::get('/test-notification/follow', function () {
    $user = User::first();
    $from = User::find(2);
    $user->notify(new JsonNotification(
        type: 'follow',
        text: ($from?->name ?? 'Someone') . ' sent you a follow request',
        fromUserId: $from?->id
    ));

    return 'Follow notification sent!';
});
