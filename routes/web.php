<?php

use App\Livewire\{Dashboard, Notifications};
use App\Livewire\Auth\{Login, Register};
use App\Livewire\Workspace\{WorkspaceList, WorkspaceForm, WorkspaceDetail, InviteMember};
use App\Livewire\ApiRunner\ApiRunner;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Guest routes (Authentication)
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');

    // Dashboard
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/dashboard', Dashboard::class)->name('dashboard.index');

    // Workspaces
    Route::prefix('workspaces')->name('workspaces.')->group(function () {
        Route::get('/', WorkspaceList::class)->name('index');
        Route::get('/create', WorkspaceForm::class)->name('create');
        Route::get('/{workspace}', WorkspaceDetail::class)->name('detail');
        Route::get('/{workspace}/edit', WorkspaceForm::class)->name('edit');
        Route::get('/{workspace}/invite', InviteMember::class)->name('invite');
    });

    // Alias for workspaces
    Route::get('/workspaces', WorkspaceList::class)->name('workspaces');

    // API Runner
    Route::get('/api-runner', ApiRunner::class)->name('api-runner');
    Route::get('/api-runner/{workspace}', ApiRunner::class)->name('api-runner.workspace');

    Route::get('/api-runner/{workspace}/{request}', ApiRunner::class)->name('api-runner.workspace.request');

    Route::get('/api-runner/{workspace}/{request}/{collection}', ApiRunner::class)->name('api-runner.workspace.request.collection');
});

// Public invitation routes (with token)
Route::get('/invitations/{token}/accept', function ($token) {
    // This will be handled by WorkspaceInvitation logic
    $invitation = \App\Models\WorkspaceInvitation::where('token', $token)->firstOrFail();

    if (!auth()->check()) {
        return redirect()->route('login')->with('invitation_token', $token);
    }

    // Accept invitation logic
    if ($invitation->email !== auth()->user()->email) {
        abort(403, 'This invitation is not for you.');
    }

    if ($invitation->accept()) {
        $invitation->workspace->members()->create([
            'user_id' => auth()->id(),
            'role' => 'member',
            'invited_by' => $invitation->invited_by,
            'accepted_at' => now(),
        ]);

        return redirect()->route('workspaces.detail', $invitation->workspace)
            ->with('message', 'Invitation accepted successfully!');
    }

    return redirect()->route('dashboard')->with('error', 'This invitation has expired.');
})->name('invitations.accept');
