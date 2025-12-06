<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MessageTemplate;

class MessageTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            // Welcome & Registration Messages
            [
                'name' => 'Welcome Message (After Registration)',
                'category' => 'Welcome & Registration',
                'subject' => 'Welcome to AI Literacy Professional Certification Program!',
                'message' => "Hello {{name}},\n\nWelcome to the AI Literacy Professional Certification Program! We're thrilled to have you join our community of professionals learning to master artificial intelligence.\n\nYour registration has been successfully completed. To access all features and begin your learning journey, please complete your payment of ₵150.00.\n\nOnce payment is confirmed, you'll gain:\n- Access to all 5 days of intensive training\n- Live online sessions with expert instructors\n- Lifetime access to session recordings\n- Course materials and resources\n- Your professional certification upon completion\n\nIf you have any questions, feel free to contact us.\n\nBest regards,\nStepProClass Team",
                'description' => 'Send to new students after registration',
                'variables' => ['name'],
            ],
            [
                'name' => 'Payment Reminder (First)',
                'category' => 'Welcome & Registration',
                'subject' => 'Complete Your Registration - Payment Pending',
                'message' => "Hello {{name}},\n\nWe noticed that your registration payment of ₵150.00 is still pending. Complete your payment now to unlock full access to the AI Literacy Professional Certification Program.\n\nYour registration includes:\n✓ 5-Day Intensive Training\n✓ Live Online Sessions\n✓ Lifetime Recordings Access\n✓ Professional Certification\n\nClick here to complete your payment: [Payment Link]\n\nIf you've already paid, please ignore this message. For payment issues, contact our support team.\n\nBest regards,\nStepProClass Team",
                'description' => 'First payment reminder for pending payments',
                'variables' => ['name'],
            ],
            [
                'name' => 'Payment Reminder (Urgent)',
                'category' => 'Welcome & Registration',
                'subject' => '⚠️ Final Reminder: Complete Your Payment',
                'message' => "Hello {{name}},\n\nThis is a final reminder that your registration payment of ₵150.00 is still pending. Complete your payment within 48 hours to secure your spot in the upcoming cohort.\n\nDon't miss out on:\n- Expert-led AI training sessions\n- Hands-on practical projects\n- Industry-recognized certification\n- Lifetime access to all materials\n\nComplete payment now: [Payment Link]\n\nIf you're experiencing payment difficulties, please contact us immediately so we can assist you.\n\nBest regards,\nStepProClass Team",
                'description' => 'Urgent final payment reminder',
                'variables' => ['name'],
            ],
            [
                'name' => 'Payment Confirmation',
                'category' => 'Welcome & Registration',
                'subject' => 'Payment Received - Welcome to Your Dashboard!',
                'message' => "Hello {{name}},\n\nGreat news! Your payment of ₵150.00 has been successfully received and confirmed.\n\nYou now have full access to:\n✓ Student Dashboard\n✓ All course materials\n✓ Live session links (will be shared before each session)\n✓ Recording access (available after sessions)\n\nYour Student ID: {{student_id}}\n\nNext Steps:\n1. Log in to your dashboard\n2. Review the course curriculum\n3. Check your email for session schedules\n4. Join the WhatsApp group (link will be shared)\n\nWe're excited to have you on this learning journey!\n\nBest regards,\nStepProClass Team",
                'description' => 'Confirm successful payment receipt',
                'variables' => ['name', 'student_id'],
            ],

            // Class & Session Messages
            [
                'name' => 'Class Starting Soon (3 Days Before)',
                'category' => 'Class & Sessions',
                'subject' => 'Your AI Literacy Program Starts in 3 Days!',
                'message' => "Hello {{name}},\n\nYour 5-Day AI Literacy Professional Certification Program begins in just 3 days!\n\nProgram Schedule:\n📅 Start Date: {{start_date}}\n⏰ Daily Sessions: {{session_time}}\n💻 Platform: Online (link will be shared)\n\nWhat to Prepare:\n- Stable internet connection\n- Laptop or computer\n- Notebook for taking notes\n- Enthusiasm to learn!\n\nYou'll receive the session link and meeting details 24 hours before Day 1. Make sure to check your email and join the WhatsApp group for real-time updates.\n\nSee you soon!\n\nBest regards,\nStepProClass Team",
                'description' => 'Notify students 3 days before program starts',
                'variables' => ['name', 'start_date', 'session_time'],
            ],
            [
                'name' => 'Class Starting Tomorrow',
                'category' => 'Class & Sessions',
                'subject' => '🚀 Your AI Literacy Program Starts Tomorrow!',
                'message' => "Hello {{name}},\n\nTomorrow is the big day! Your AI Literacy Professional Certification Program begins.\n\n📅 Date: {{start_date}}\n⏰ Time: {{session_time}}\n🔗 Session Link: [Will be shared via WhatsApp]\n\nImportant Reminders:\n- Join 10 minutes early to test your connection\n- Have your notebook ready\n- Ensure a quiet environment\n- Check your email for any last-minute updates\n\nWe're excited to start this journey with you. See you tomorrow!\n\nBest regards,\nStepProClass Team",
                'description' => 'Reminder sent the day before program starts',
                'variables' => ['name', 'start_date', 'session_time'],
            ],
            [
                'name' => 'Day 1 Session Reminder',
                'category' => 'Class & Sessions',
                'subject' => '⏰ Reminder: Day 1 Session Starts in 2 Hours',
                'message' => "Hello {{name}},\n\nYour Day 1 session starts in 2 hours!\n\n📅 Today, {{date}}\n⏰ Time: {{session_time}}\n🔗 Join Link: [Check WhatsApp group]\n\nToday's Focus: Fundamentals of Artificial Intelligence\n- Definition of AI and Key Terminologies\n- Practical Use of Artificial Intelligence\n- Several Ways of Accessing AI\n- Advantages and Disadvantages of AI\n\nSee you soon!\n\nBest regards,\nStepProClass Team",
                'description' => 'Same-day session reminder',
                'variables' => ['name', 'date', 'session_time'],
            ],
            [
                'name' => 'Session Recording Available',
                'category' => 'Class & Sessions',
                'subject' => '📹 Day {{day}} Recording Now Available',
                'message' => "Hello {{name}},\n\nThe recording for Day {{day}} session is now available in your dashboard!\n\n📹 Access Recording: [Dashboard Link]\n📚 Topic: {{session_topic}}\n\nYou can:\n- Watch at your own pace\n- Review key concepts\n- Pause and rewind as needed\n- Download materials\n\nRemember: You have lifetime access to all recordings, so you can revisit any session anytime.\n\nHappy learning!\n\nBest regards,\nStepProClass Team",
                'description' => 'Notify when session recording is ready',
                'variables' => ['name', 'day', 'session_topic'],
            ],
            [
                'name' => 'Mid-Program Encouragement',
                'category' => 'Class & Sessions',
                'subject' => 'You\'re Halfway There! Keep Going! 🎉',
                'message' => "Hello {{name}},\n\nCongratulations! You've completed the first half of the AI Literacy Professional Certification Program.\n\nYou've learned:\n✓ Fundamentals of AI\n✓ Effective use of LLM Chatbots\n✓ AI for Productivity\n\nComing up:\n- Day 4: AI for Content Creation\n- Day 5: AI for Coding\n\nYou're doing great! Keep up the momentum. If you have any questions, don't hesitate to reach out.\n\nBest regards,\nStepProClass Team",
                'description' => 'Encouragement message mid-program',
                'variables' => ['name'],
            ],

            // Completion & Certification
            [
                'name' => 'Program Completion',
                'category' => 'Completion & Certification',
                'subject' => '🎓 Congratulations! You\'ve Completed the Program!',
                'message' => "Hello {{name}},\n\nCongratulations! You've successfully completed the 5-Day AI Literacy Professional Certification Program!\n\nWhat You've Achieved:\n✓ Completed all 5 days of intensive training\n✓ Gained practical AI skills\n✓ Earned your professional certification\n✓ Lifetime access to all materials\n\nYour Certificate:\nYour professional certification certificate is now available for download in your dashboard. You can use it to:\n- Enhance your professional profile\n- Showcase your AI literacy skills\n- Advance your career\n\nNext Steps:\n- Download your certificate\n- Add it to your LinkedIn profile\n- Continue practicing with the resources provided\n- Join our alumni community\n\nThank you for being part of this journey!\n\nBest regards,\nStepProClass Team",
                'description' => 'Congratulate students on program completion',
                'variables' => ['name'],
            ],
            [
                'name' => 'Certificate Available',
                'category' => 'Completion & Certification',
                'subject' => '🏆 Your Professional Certificate is Ready!',
                'message' => "Hello {{name}},\n\nYour AI Literacy Professional Certification Certificate is now available!\n\n📜 Download Certificate: [Dashboard Link]\n\nThis certificate validates your expertise in:\n- AI Fundamentals and Terminologies\n- LLM Chatbots and AI Tools\n- AI for Productivity and Content Creation\n- AI for Coding and Development\n\nShare your achievement on LinkedIn and with your professional network!\n\nBest regards,\nStepProClass Team",
                'description' => 'Notify when certificate is ready for download',
                'variables' => ['name'],
            ],

            // General Announcements
            [
                'name' => 'Important Program Update',
                'category' => 'General Announcements',
                'subject' => 'Important Update: Program Schedule Change',
                'message' => "Hello {{name}},\n\nWe have an important update regarding the program schedule.\n\n{{update_details}}\n\nNew Schedule:\n{{new_schedule_details}}\n\nWe apologize for any inconvenience and appreciate your understanding. If you have any questions or concerns, please contact us immediately.\n\nThank you for your flexibility.\n\nBest regards,\nStepProClass Team",
                'description' => 'Notify about program schedule changes',
                'variables' => ['name', 'update_details', 'new_schedule_details'],
            ],
            [
                'name' => 'New Resource Available',
                'category' => 'General Announcements',
                'subject' => '📚 New Learning Resource Available',
                'message' => "Hello {{name}},\n\nWe've added a new resource to your dashboard!\n\n📚 Resource: {{resource_name}}\n🔗 Access: [Dashboard Link]\n\nThis resource will help you:\n{{resource_benefits}}\n\nCheck it out and let us know if you have any questions.\n\nBest regards,\nStepProClass Team",
                'description' => 'Announce new learning resources',
                'variables' => ['name', 'resource_name', 'resource_benefits'],
            ],
            [
                'name' => 'WhatsApp Group Reminder',
                'category' => 'General Announcements',
                'subject' => 'Join Our WhatsApp Group for Updates',
                'message' => "Hello {{name}},\n\nDon't forget to join our WhatsApp group for real-time updates, Q&A sessions, and community support!\n\n📱 Join Link: [WhatsApp Link]\n\nIn the group, you'll receive:\n- Session reminders\n- Quick updates\n- Peer support\n- Direct access to instructors\n\nSee you there!\n\nBest regards,\nStepProClass Team",
                'description' => 'Remind students to join WhatsApp group',
                'variables' => ['name'],
            ],

            // Payment & Financial
            [
                'name' => 'Payment Failed Notification',
                'category' => 'Payment & Financial',
                'subject' => 'Payment Issue - Action Required',
                'message' => "Hello {{name}},\n\nWe encountered an issue processing your payment. This could be due to:\n- Insufficient funds\n- Card expiration\n- Bank restrictions\n- Network issues\n\nPlease try again: [Payment Link]\n\nIf the problem persists, contact us immediately and we'll help you complete your payment through an alternative method.\n\nBest regards,\nStepProClass Team",
                'description' => 'Notify about payment processing issues',
                'variables' => ['name'],
            ],

            // Affiliate Messages
            [
                'name' => 'New Student Referred',
                'category' => 'Affiliate Messages',
                'subject' => '🎉 New Student Referred - Commission Pending',
                'message' => "Hello {{name}},\n\nGreat news! A new student has been registered through your referral link.\n\nStudent: {{student_name}}\nRegistration Date: {{date}}\n\nCommission Status: Pending Payment\nCommission Amount: ₵40.00\n\nYou'll receive your commission once the student completes their payment. Keep sharing your referral link to earn more!\n\nBest regards,\nStepProClass Team",
                'description' => 'Notify affiliate about new referral',
                'variables' => ['name', 'student_name', 'date'],
            ],
            [
                'name' => 'Commission Earned',
                'category' => 'Affiliate Messages',
                'subject' => '💰 Commission Earned - ₵40.00 Added to Your Wallet',
                'message' => "Hello {{name}},\n\nCongratulations! You've earned a commission!\n\nStudent: {{student_name}}\nCommission: ₵40.00\nDate: {{date}}\n\nYour current wallet balance: ₵{{balance}}\n\nYou can request a withdrawal anytime from your affiliate dashboard. Keep up the great work!\n\nBest regards,\nStepProClass Team",
                'description' => 'Notify affiliate about commission earned',
                'variables' => ['name', 'student_name', 'date', 'balance'],
            ],
            [
                'name' => 'Withdrawal Approved',
                'category' => 'Affiliate Messages',
                'subject' => '✅ Withdrawal Request Approved',
                'message' => "Hello {{name}},\n\nYour withdrawal request has been approved!\n\nAmount: ₵{{amount}}\nStatus: Processing\nExpected Transfer: 3-5 business days\n\nYou'll receive a confirmation once the transfer is completed. Thank you for being part of our affiliate program!\n\nBest regards,\nStepProClass Team",
                'description' => 'Notify affiliate about approved withdrawal',
                'variables' => ['name', 'amount'],
            ],

            // Reminders
            [
                'name' => 'Session Attendance Reminder',
                'category' => 'Reminders',
                'subject' => '⏰ Don\'t Miss Today\'s Session!',
                'message' => "Hello {{name}},\n\nFriendly reminder: Your session starts in {{hours}} hours!\n\n📅 Today, {{date}}\n⏰ Time: {{session_time}}\n📚 Topic: {{topic}}\n\nEven if you can't attend live, the recording will be available in your dashboard afterward. But live participation is highly recommended for the best learning experience!\n\nSee you there!\n\nBest regards,\nStepProClass Team",
                'description' => 'Remind students about upcoming session',
                'variables' => ['name', 'hours', 'date', 'session_time', 'topic'],
            ],
        ];

        foreach ($templates as $template) {
            MessageTemplate::create($template);
        }
    }
}
