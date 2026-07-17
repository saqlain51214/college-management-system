<?php

namespace Database\Seeders;

use App\Models\NotificationTemplate;
use Illuminate\Database\Seeder;

class NotificationTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [

            // ── Student notifications ────────────────────────────────────────

            [
                'key'          => 'result_published',
                'name'         => 'Result Published — Student',
                'description'  => 'Sent when admin publishes results for an exam. Delivered by email and in-app bell.',
                'channel'      => 'both',
                'subject'      => 'Your Results for {{exam_name}} Are Ready — JDCA',
                'body'         => "Dear {{student_name}},\n\nWe are pleased to inform you that the results for **{{exam_name}}** have been published and are now available on your student portal.\n\nPlease log in to view your detailed results, grade points, and remarks.",
                'action_label' => 'View Results',
                'action_url'   => '/portal/results',
                'in_app_icon'  => 'academic-cap',
                'variables'    => ['student_name', 'exam_name', 'exam_id'],
            ],

            [
                'key'          => 'fee_overdue',
                'name'         => 'Fee Overdue — Student',
                'description'  => 'Sent automatically by the fees:check-overdue command when a payment becomes overdue.',
                'channel'      => 'both',
                'subject'      => 'Fee Overdue Reminder — PKR {{amount}} — JDCA',
                'body'         => "Dear {{student_name}},\n\nThis is a reminder that your fee payment of **PKR {{amount}}** for **{{fee_type}}** is now overdue.\n\nPlease pay at the earliest to avoid late charges. If you have already paid, please contact the accounts office to update your records.",
                'action_label' => 'View Fee Details',
                'action_url'   => '/portal/fees',
                'in_app_icon'  => 'credit-card',
                'variables'    => ['student_name', 'amount', 'fee_type', 'due_date'],
            ],

            [
                'key'          => 'fee_payment_confirmed',
                'name'         => 'Fee Payment Confirmed — Student',
                'description'  => 'Sent when a fee payment is marked as paid by admin.',
                'channel'      => 'both',
                'subject'      => 'Fee Payment Confirmed — PKR {{amount}} — JDCA',
                'body'         => "Dear {{student_name}},\n\nYour fee payment of **PKR {{amount}}** for **{{fee_type}}** has been confirmed and recorded successfully.\n\nYour challan receipt is available on your student portal.",
                'action_label' => 'Download Challan',
                'action_url'   => '/portal/fees',
                'in_app_icon'  => 'check-circle',
                'variables'    => ['student_name', 'amount', 'fee_type', 'payment_date'],
            ],

            [
                'key'          => 'scholarship_awarded',
                'name'         => 'Scholarship Awarded — Student',
                'description'  => 'Sent when a scholarship is awarded to a student.',
                'channel'      => 'both',
                'subject'      => 'Congratulations — Scholarship Awarded — JDCA',
                'body'         => "Dear {{student_name}},\n\nWe are delighted to inform you that you have been awarded the **{{scholarship_name}}** scholarship.\n\n**Amount:** PKR {{amount}}\n\nThis award reflects your academic excellence and dedication. Please visit the accounts office for further details.",
                'action_label' => 'View Details',
                'action_url'   => '/portal/fees',
                'in_app_icon'  => 'star',
                'variables'    => ['student_name', 'scholarship_name', 'amount'],
            ],

            [
                'key'          => 'new_announcement',
                'name'         => 'New Announcement — Student',
                'description'  => 'In-app notification when admin publishes a new announcement for students.',
                'channel'      => 'database',
                'subject'      => 'New Notice: {{title}}',
                'body'         => '{{title}} — posted by JDCA administration.',
                'action_label' => 'Read Notice',
                'action_url'   => '/portal/notices',
                'in_app_icon'  => 'megaphone',
                'variables'    => ['title', 'priority'],
            ],

            [
                'key'          => 'new_assignment_posted',
                'name'         => 'New Assignment Posted — Student',
                'description'  => 'In-app notification when a teacher posts a new assignment.',
                'channel'      => 'database',
                'subject'      => 'New Assignment: {{assignment_title}}',
                'body'         => '{{teacher_name}} posted a new assignment in **{{course_name}}**. Due: {{due_date}}.',
                'action_label' => null,
                'action_url'   => null,
                'in_app_icon'  => 'clipboard-document-list',
                'variables'    => ['assignment_title', 'course_name', 'teacher_name', 'due_date'],
            ],

            [
                'key'          => 'attendance_warning',
                'name'         => 'Attendance Warning — Student',
                'description'  => 'Sent when a student\'s attendance falls below 75%.',
                'channel'      => 'both',
                'subject'      => 'Attendance Warning — {{course_name}} — JDCA',
                'body'         => "Dear {{student_name}},\n\nThis is to inform you that your attendance in **{{course_name}}** has fallen to **{{attendance_percent}}%**, which is below the required 75%.\n\nPlease ensure regular attendance to avoid being barred from examinations. If you have any medical or valid reason for absence, please submit supporting documents to the administration.",
                'action_label' => 'View Timetable',
                'action_url'   => '/portal/timetable',
                'in_app_icon'  => 'exclamation-triangle',
                'variables'    => ['student_name', 'course_name', 'attendance_percent'],
            ],

            // ── Teacher notifications ────────────────────────────────────────

            [
                'key'          => 'assignment_submitted',
                'name'         => 'Assignment Submitted — Teacher',
                'description'  => 'In-app notification when a student submits an assignment.',
                'channel'      => 'database',
                'subject'      => 'Assignment Submitted: {{assignment_title}}',
                'body'         => '**{{student_name}}** submitted the assignment "{{assignment_title}}" in {{course_name}}.',
                'action_label' => null,
                'action_url'   => null,
                'in_app_icon'  => 'document-check',
                'variables'    => ['student_name', 'assignment_title', 'course_name'],
            ],

            [
                'key'          => 'attendance_reminder',
                'name'         => 'Attendance Reminder — Teacher',
                'description'  => 'Sent to remind teacher to mark attendance for a session.',
                'channel'      => 'database',
                'subject'      => 'Attendance Not Marked — {{course_name}}',
                'body'         => 'Reminder: attendance for **{{course_name}}** on {{session_date}} has not been marked yet. Please mark attendance at your earliest.',
                'action_label' => 'Mark Attendance',
                'action_url'   => '/teacher/attendance',
                'in_app_icon'  => 'clock',
                'variables'    => ['course_name', 'session_date'],
            ],

            [
                'key'          => 'teacher_new_announcement',
                'name'         => 'New Announcement — Teacher',
                'description'  => 'In-app notification when admin posts an announcement for teachers.',
                'channel'      => 'database',
                'subject'      => 'New Notice: {{title}}',
                'body'         => '{{title}} — posted by JDCA administration.',
                'action_label' => 'Read Notice',
                'action_url'   => '/teacher/notices',
                'in_app_icon'  => 'megaphone',
                'variables'    => ['title'],
            ],

            [
                'key'          => 'exam_entry_reminder',
                'name'         => 'Exam Result Entry Reminder — Teacher',
                'description'  => 'Sent to remind teacher to enter results for an exam.',
                'channel'      => 'database',
                'subject'      => 'Results Entry Pending — {{exam_name}}',
                'body'         => 'Reminder: please enter results for **{{exam_name}}** in {{course_name}}. The deadline is {{deadline}}.',
                'action_label' => null,
                'action_url'   => null,
                'in_app_icon'  => 'pencil-square',
                'variables'    => ['exam_name', 'course_name', 'deadline'],
            ],

            [
                'key'          => 'timetable_updated',
                'name'         => 'Timetable Updated — Teacher',
                'description'  => 'In-app notification when the teacher\'s timetable is changed by admin.',
                'channel'      => 'database',
                'subject'      => 'Timetable Updated',
                'body'         => 'Your teaching schedule has been updated by the administration. Please review your new timetable.',
                'action_label' => 'View Timetable',
                'action_url'   => '/teacher/timetable',
                'in_app_icon'  => 'calendar',
                'variables'    => [],
            ],

        ];

        foreach ($templates as $template) {
            NotificationTemplate::updateOrCreate(
                ['key' => $template['key']],
                $template,
            );
        }

        $this->command->info('Notification templates seeded: ' . count($templates) . ' templates.');
    }
}
