<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Throwable;

/**
 * Simple push-notification testing page (no DB).
 * Logs are kept in session only.
 */
class TestNotificationController extends Controller
{
    public const SESSION_LOG_KEY = 'test_notification_logs';
    public const MAX_LOGS = 30;

    /**
     * Predefined notification templates used by the mobile app click_action values.
     */
    public static function templates(): array
    {
        return [
            'general_test' => [
                'label' => 'General Test',
                'title' => 'HR Shastri Test Notification',
                'body' => 'This is a test push notification from HR Shastri.',
                'click_action' => 'general_test',
            ],
            'leave_status_changed' => [
                'label' => 'Leave Status Changed',
                'title' => 'Your Leave has been Approved',
                'body' => 'Test: Your leave request status has been updated.',
                'click_action' => 'leave_status_changed',
            ],
            'short_leave_status_changed' => [
                'label' => 'Short Leave Status',
                'title' => 'Your Short Leave has been Approved',
                'body' => 'Test: Your short leave status has been updated.',
                'click_action' => 'short_leave_status_changed',
            ],
            'claim_status_changed' => [
                'label' => 'Claim Status Changed',
                'title' => 'Your Claim has been Approved',
                'body' => 'Test: Your claim reimbursement status has been updated.',
                'click_action' => 'claim_status_changed',
            ],
            'ticket_status_changed' => [
                'label' => 'Ticket Status Changed',
                'title' => 'Your Ticket has been Updated',
                'body' => 'Test: Your support ticket status has been updated.',
                'click_action' => 'ticket_status_changed',
            ],
            'ticket_replied' => [
                'label' => 'Ticket Replied',
                'title' => 'New reply on your Ticket',
                'body' => 'Test: Someone replied to your ticket.',
                'click_action' => 'ticket_replied',
            ],
            'gate_pass_status_changed' => [
                'label' => 'Gate Pass Status',
                'title' => 'Your Gate Pass has been Approved',
                'body' => 'Test: Your gate pass status has been updated.',
                'click_action' => 'gate_pass_status_changed',
            ],
            'wfm_status_changed' => [
                'label' => 'WFM Status Changed',
                'title' => 'Your WFM request has been Approved',
                'body' => 'Test: Your work-from-home request status has been updated.',
                'click_action' => 'wfm_status_changed',
            ],
            'visit_status_changed' => [
                'label' => 'Visit Status Changed',
                'title' => 'Your Visit has been Approved',
                'body' => 'Test: Your visit request status has been updated.',
                'click_action' => 'visit_status_changed',
            ],
            'resign_status_changed' => [
                'label' => 'Resignation Status',
                'title' => 'Your Resignation has been Updated',
                'body' => 'Test: Your resignation status has been updated.',
                'click_action' => 'resign_status_changed',
            ],
            'dashboard_user_clock_out' => [
                'label' => 'Clock Out Reminder',
                'title' => 'Please Clock Out',
                'body' => 'Test: Reminder to clock out for today.',
                'click_action' => 'dashboard_user_clock_out',
            ],
        ];
    }

    public function index(Request $request)
    {
        if (!Auth::check() || Auth::user()->type === 'employee') {
            abort(403, __('Permission denied.'));
        }

        $companyId = Auth::user()->creatorId();
        $q = trim((string) $request->get('q', ''));
        $onlyWithToken = $request->get('with_token') === '1';

        $usersQuery = User::query()
            ->select('id', 'name', 'email', 'type', 'device_type', 'device_token', 'created_by', 'is_active')
            ->orderBy('name');

        if (Auth::user()->type !== 'super admin') {
            $usersQuery->where(function ($query) use ($companyId) {
                $query->where('created_by', $companyId)->orWhere('id', $companyId);
            });
        }

        if ($q !== '') {
            $usersQuery->where(function ($query) use ($q) {
                $query->where('name', 'like', '%' . $q . '%')
                    ->orWhere('email', 'like', '%' . $q . '%')
                    ->orWhere('id', $q);
            });
        }

        if ($onlyWithToken) {
            $usersQuery->whereNotNull('device_token')->where('device_token', '!=', '');
        }

        // Show all scoped users (no artificial 300 cut for company lists)
        $users = $usersQuery->get();
        $templates = self::templates();
        $logs = Session::get(self::SESSION_LOG_KEY, []);
        $usersWithTokenCount = $users->filter(function ($u) {
            return !empty($u->device_token);
        })->count();

        return view('test_notification.index', compact('users', 'templates', 'logs', 'q', 'onlyWithToken', 'usersWithTokenCount'));
    }

    public function send(Request $request)
    {
        if (!Auth::check() || Auth::user()->type === 'employee') {
            abort(403, __('Permission denied.'));
        }

        $request->validate([
            'user_id' => 'required|integer',
            'notification_type' => 'required|string',
            'title' => 'nullable|string|max:150',
            'body' => 'nullable|string|max:500',
        ]);

        $templates = self::templates();
        $type = $request->notification_type;
        if (!isset($templates[$type])) {
            return redirect()->route('test.notification.index')->with('error', __('Invalid notification type.'));
        }

        $template = $templates[$type];
        $title = trim((string) ($request->title ?: $template['title']));
        $body = trim((string) ($request->body ?: $template['body']));
        $clickAction = $template['click_action'];

        $companyId = Auth::user()->creatorId();
        $userQuery = User::where('id', (int) $request->user_id);

        if (Auth::user()->type !== 'super admin') {
            $userQuery->where(function ($q) use ($companyId) {
                $q->where('created_by', $companyId)->orWhere('id', $companyId);
            });
        }

        $targetUser = $userQuery->first();
        if (!$targetUser) {
            $this->pushLog([
                'status' => 'failed',
                'user' => 'User #' . $request->user_id,
                'type' => $type,
                'title' => $title,
                'body' => $body,
                'message' => 'User not found / not in your company.',
            ]);
            return redirect()->route('test.notification.index')->with('error', __('User not found / not in your company.'));
        }

        if (empty($targetUser->device_token)) {
            $this->pushLog([
                'status' => 'failed',
                'user' => $targetUser->name . ' (#' . $targetUser->id . ')',
                'email' => $targetUser->email,
                'type' => $type,
                'title' => $title,
                'body' => $body,
                'message' => 'Device token missing. User must login to the mobile app first.',
            ]);
            return redirect()->route('test.notification.index')->with('error', __('Device token missing for :name. Ask them to open the mobile app once.', ['name' => $targetUser->name]));
        }

        $usersData = User::where('id', $targetUser->id)
            ->whereNotNull('device_token')
            ->where('device_token', '!=', '')
            ->select('id', 'device_type', 'device_token')
            ->get();

        $extraData = [
            'id' => (string) time(),
            'test' => '1',
            'notification_type' => $type,
        ];

        $status = 'failed';
        $message = 'Unknown error';
        $responseBody = null;

        try {
            if (!function_exists('sendpPushNotification')) {
                throw new \RuntimeException('sendpPushNotification helper not found.');
            }

            $result = sendpPushNotification($title, $body, $usersData, $clickAction, $extraData, true);

            if (is_array($result)) {
                $responseBody = is_string($result['data'] ?? null)
                    ? $result['data']
                    : json_encode($result['data'] ?? []);
                $httpCode = (int) ($result['statusCode'] ?? 0);
                $decoded = json_decode((string) $responseBody, true);

                if ($httpCode >= 200 && $httpCode < 300 && !(is_array($decoded) && isset($decoded['error']))) {
                    $status = 'sent';
                    $message = $result['message'] ?? 'Push notification sent via FCM.';
                } else {
                    $status = 'failed';
                    $message = is_array($decoded) && isset($decoded['error']['message'])
                        ? $decoded['error']['message']
                        : ($result['message'] ?? ('FCM HTTP ' . $httpCode));
                }
            } elseif ($result === '' || $result === null || $result === 0) {
                $status = 'failed';
                $message = 'FCM send failed (empty response / access token issue).';
            } else {
                $responseBody = is_string($result) ? $result : json_encode($result);
                $status = 'sent';
                $message = 'Push notification sent via FCM.';
            }
        } catch (Throwable $e) {
            $status = 'failed';
            $message = $e->getMessage();
        }

        $this->pushLog([
            'status' => $status,
            'user' => $targetUser->name . ' (#' . $targetUser->id . ')',
            'email' => $targetUser->email,
            'device_type' => $targetUser->device_type,
            'token_preview' => substr((string) $targetUser->device_token, 0, 18) . '...',
            'type' => $type,
            'click_action' => $clickAction,
            'title' => $title,
            'body' => $body,
            'message' => $message,
            'response' => $responseBody ? substr($responseBody, 0, 400) : null,
            'sent_by' => Auth::user()->name,
        ]);

        if ($status === 'sent') {
            return redirect()->route('test.notification.index')->with('success', __('Test notification sent to :name', ['name' => $targetUser->name]));
        }

        return redirect()->route('test.notification.index')->with('error', __('Send failed: :msg', ['msg' => $message]));
    }

    public function clearLog()
    {
        if (!Auth::check() || Auth::user()->type === 'employee') {
            abort(403, __('Permission denied.'));
        }

        Session::forget(self::SESSION_LOG_KEY);

        return redirect()->route('test.notification.index')->with('success', __('Notification test log cleared.'));
    }

    protected function pushLog(array $entry): void
    {
        $logs = Session::get(self::SESSION_LOG_KEY, []);
        array_unshift($logs, array_merge($entry, [
            'at' => now()->format('d M Y H:i:s'),
        ]));
        $logs = array_slice($logs, 0, self::MAX_LOGS);
        Session::put(self::SESSION_LOG_KEY, $logs);
    }
}
