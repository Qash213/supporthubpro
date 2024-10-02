<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Artisan;

class Updateversion3_4 extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // remove this setting : CUSTOMER_RESTICT_TO_DELETE_TICKET

        DB::table('settings')->insert([
            [
                'key' => 'DASHBOARD_TABLE_DATA_AUTO_REFRESH',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'TABLE_DATA_AUTO_REFRESH_TIME',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'USER_MAX_FILE_UPLOAD',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'USER_FILE_UPLOAD_MAX_SIZE',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'USER_FILE_UPLOAD_TYPES',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // livechat settings
            [
                'key' => 'All_Online_Users',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'notificationsSounds',
                'value' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'newMessageWebNot',
                'value' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'newMessageSound',
                'value' => 'norifysound.mp3',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'newChatRequestWebNot',
                'value' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'newChatRequestSound',
                'value' => 'norifysound.mp3',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'liveChatFlowload',
                'value' => "for-a-single-unique-user",
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'liveChatFileUpload',
                'value' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'livechatMaxFileUpload',
                'value' => '2',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'livechatFileUploadMax',
                'value' => "3",
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'livechatFileUploadTypes',
                'value' => ".jpg,.jpeg,.png",
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'liveChatAgentFileUpload',
                'value' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'AgentlivechatMaxFileUpload',
                'value' => '2',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'AgentlivechatFileUploadMax',
                'value' => "3",
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'AgentlivechatFileUploadTypes',
                'value' => ".jpg,.jpeg,.png",
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'livechatIconSize',
                'value' => "small",
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'livechatPosition',
                'value' => "right",
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'offlineDisplayLiveChat',
                'value' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'OfflineStatusMessage',
                'value' => "Live chat offline. Leave message, we'll reply soon.",
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'OnlineStatusMessage',
                'value' => 'Chat’s live. How can I help?',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'OfflineMessage',
                'value' => "our support team will get back to you promptly during our next business hours. In the meantime, you may find helpful resources",
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'operatorsNotificationsSounds',
                'value' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'operatorsAgentToAgentWebNot',
                'value' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'operatorsGroupChatWebNot',
                'value' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'operatorsAgentToAgentSound',
                'value' => 'norifysound.mp3',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'operatorsGroupChatSound',
                'value' => 'norifysound.mp3',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'liveChatHidden',
                'value' => 'true',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'liveChatPort',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'enableAutoSlove',
                'value' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'autoSloveEmailTimer',
                'value' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'autoSloveCloseTimer',
                'value' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'liveChatCustomerOnlineUsers',
                'value' => "",
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'notificationType',
                'value' => "Single",
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'livechatFeedbackDropdown',
                'value' => "Resolved in one chat.,Resolved in multiple chats.,Not resolved (first contact).,Not resolved (multiple contacts).",
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'inspectDisable',
                'value' => "off",
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'selectDisabled',
                'value' => "off",
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'botresponseenable',
                'value' => "off",
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'bot_name',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'botsresponse_time',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'time_detection',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'response_description',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'response_description_exclude_business_hours',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'bot_image',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'IMAP_EMAIL_AUTO_DELETE',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'IMAP_EMAIL_PROCESS_LIMIT_SWITCH',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'IMAP_EMAIL_TEMPLATE_LIMIT',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'twilioenable',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'twilio_auth_id',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'twilio_auth_token',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'twilio_auth_phone_number',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'cust_mobile_update',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'isToken',
                'value' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => '24hoursbusinessswitch',
                'value' => 'off',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'AUTO_DELETE_LIVECHAT_ENABLE',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'AUTO_DELETE_LIVECHAT_IN_MONTHS',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'serversslcertificate',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'serversslkey',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'serverssldomainname',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'LivechatCustFeedbackQuestion',
                'value' => 'Satisfied with resolution today?',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'LivechatCustWelcomeMsg',
                'value' => 'Livechat Users',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        DB::table('email_templates')->insert([

            [
                'code' => 'when_send_customnotify_email_to_delete_guest_account',
                'title' => 'Send an email alert to the guest when they are not using the application.',
                'subject' => 'Your account is unused and will soon be deleted.',
                'body' => '<p>Attention {{customer_username}}</p><p>Your {{customer_email}} personal account has been unused for {{customer_months}}.</p><p>It would be helpful if you registered to the application.<br></p><p>Click here to <a href="{{ticket_customer_url}}" target="_blank">register</a></p><p>Note:  If you fail to register, your associated data will be deleted in {{customer_time}}.</p><p><br></p><p class="root-block-node" data-paragraphid="19" data-from-init="true" data-changed="false">Sincerely,</p><p class="root-block-node" data-paragraphid="20" data-from-init="true" data-changed="false">Support Team</p>',
                'variables_used' => 'customer_username,customer_email,customer_time,customer_months,ticket_customer_url',
                'created_at' => now(),
                'updated_at' => now()
            ],

            // livechat email template
            [
                'code' => 'send_mail_to_livechat_cust_when_noresponse',
                'title' => 'Mail send to livechat customer when no response for any message',
                'subject' => 'No response for the livechat message ',
                'body' => '<p class="root-block-node" data-paragraphid="16" data-from-init="true" data-changed="false">Dear {{livechat_cust_name}},<br></p><p class="root-block-node" data-paragraphid="16" data-from-init="true" data-changed="false">We hope this email finds you well.</p><p class="root-block-node" data-paragraphid="16" data-from-init="true" data-changed="false">We wanted to follow up on our recent LiveChat conversation. Our dedicated agent was happy to assist you and provided a detailed response to your inquiry.<br></p><p class="root-block-node" data-paragraphid="16" data-from-init="true" data-changed="false">Here’s a recap of the last message from our agent:</p><p class="root-block-node" data-paragraphid="16" data-from-init="true" data-changed="false">{{livechat_last_message_date}}<br>{{livechat_last_message}}</p><p class="root-block-node" data-paragraphid="16" data-from-init="true" data-changed="false">If you have any further questions, need clarification, or require additional assistance, please don’t hesitate to reach out. Our team is here to help you every step of the way.<br></p><p class="root-block-node" data-paragraphid="16" data-from-init="true" data-changed="false">Your satisfaction is our top priority, and we want to ensure that your experience with us is excellent. We are committed to providing you with the best possible service.</p><p class="root-block-node" data-paragraphid="16" data-from-init="true" data-changed="false">We appreciate your business and look forward to hearing from you soon.<br><br>Best regards,<br></p>',
                'variables_used' => 'livechat_cust_name,livechat_last_message_date,livechat_last_message',
                'created_at' => now(),
                'updated_at' => now()
            ],

            // two factor authentication disabled by admin
            [
                'code' => 'send_mail_to_users_when_two_factor_authentication_disabled',
                'title' => 'Send an email alert to users when two factor authenticataion is disabled by admin.',
                'subject' => 'Two-Factor Authentication Disabled.',
                'body' => '<p>Dear {{username}},</p>
                <p>We’re reaching out to inform you that your two-factor authentication (2FA) has been disabled by our administrative team.</p>
                <p>Please be assured that your account security remains our top priority, and this action was taken for reasons that require your attention. It could be due to a variety of factors, including security protocol updates, account maintenance, or specific circumstances related to your account.</p>
                <p>To ensure the continued security of your account, we strongly recommend re-enabling two-factor authentication as soon as possible. 2FA provides an additional layer of security that helps safeguard your account from unauthorized access.</p>
                <ol>
                <li>Here are the steps to re-enable two-factor authentication:</li>
                <li>Log in to your account using your username and password.</li>
                <li>Navigate to the two-factor authentication settings within your account profile.</li>
                </ol>
                <p>Follow the prompts to set up two-factor authentication using your preferred method (e.g., Google authenticator app, email).</p>
                <p>If you encounter any difficulties or have questions regarding this matter, please don’t hesitate to reach out to our support team. We’re here to assist you every step of the way.</p>
                <p>Thank you for your attention to this matter and for your cooperation in maintaining the security of your account.</p><p class="root-block-node" data-paragraphid="19" data-from-init="true" data-changed="false">Sincerely,</p><p class="root-block-node" data-paragraphid="20" data-from-init="true" data-changed="false">Support Team</p>',
                'variables_used' => 'username',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        $emailtemplate = EmailTemplate::where('code','customer_sendmail_contactus')->first();
        $emailtemplate->variables_used = 'Contact_name,Contact_email,Contact_subject,Contact_phone,Contact_message';
        $emailtemplate->save();
        $emailtemplate = EmailTemplate::where('code','admin_sendmail_contactus')->first();
        $emailtemplate->variables_used = 'Contact_name,Contact_email,Contact_subject,Contact_phone,Contact_message';
        $emailtemplate->save();
        $emailtemplate = EmailTemplate::where('code','customer_sendmail_verification')->first();
        $emailtemplate->variables_used = 'username, email, email_verify_url';
        $emailtemplate->save();
        $emailtemplate = EmailTemplate::where('code','customer_send_ticket_created')->first();
        $emailtemplate->variables_used = 'ticket_username,ticket_id, ticket_title,ticket_status,ticket_description,ticket_customer_url,ticket_admin_url';
        $emailtemplate->save();
        $emailtemplate = EmailTemplate::where('code','admin_send_email_ticket_created')->first();
        $emailtemplate->variables_used = 'ticket_username,ticket_id, ticket_title,ticket_status,ticket_description,ticket_customer_url,ticket_admin_url';
        $emailtemplate->save();
        $emailtemplate = EmailTemplate::where('code','customer_send_ticket_reply')->first();
        $emailtemplate->variables_used = 'ticket_username,ticket_title,ticket_id,ticket_status,comment,ticket_admin_url,ticket_customer_url';
        $emailtemplate->save();
        $emailtemplate = EmailTemplate::where('code','customer_rating')->first();
        $emailtemplate->variables_used = 'closed_agent_name,closed_agent_role,ticket_username,ticket_title,ticket_id,comment,ticket_status,ratinglink,ticket_customer_url,ticket_admin_url';
        $emailtemplate->save();
        $emailtemplate = EmailTemplate::where('code','customer_send_ticket_reopen')->first();
        $emailtemplate->variables_used = 'ticket_username,ticket_id,ticket_title,ticket_description,ticket_status,ticket_customer_url,ticket_admin_url';
        $emailtemplate->save();
        $emailtemplate = EmailTemplate::where('code','forget_password')->first();
        $emailtemplate->variables_used = 'reset_password_url';
        $emailtemplate->save();
        $emailtemplate = EmailTemplate::where('code','customer_send_registration_details')->first();
        $emailtemplate->variables_used = 'userpassword,username,useremail,url';
        $emailtemplate->save();
        $emailtemplate = EmailTemplate::where('code','employee_send_registration_details')->first();
        $emailtemplate->variables_used = 'userpassword,username,useremail,url';
        $emailtemplate->save();
        $emailtemplate = EmailTemplate::where('code','customer_send_guestticket_created')->first();
        $emailtemplate->variables_used = 'ticket_username,ticket_id,ticket_title,ticket_status,ticket_description,ticket_customer_url,ticket_admin_url';
        $emailtemplate->save();
        $emailtemplate = EmailTemplate::where('code','customer_send_ticket_overdue')->first();
        $emailtemplate->variables_used = 'ticket_username,ticket_title,ticket_overduetime,ticket_id,ticket_description,ticket_status,ticket_customer_url,ticket_admin_url';
        $emailtemplate->save();
        $emailtemplate = EmailTemplate::where('code','customer_send_ticket_response')->first();
        $emailtemplate->variables_used = 'ticket_username,ticket_closingtime,ticket_title,ticket_id,ticket_description,ticket_status,replystatus,ticket_customer_url,ticket_admin_url';
        $emailtemplate->save();
        $emailtemplate = EmailTemplate::where('code','customer_send_ticket_autoclose')->first();
        $emailtemplate->variables_used = 'ticket_username,ticket_id,ticket_title,ticket_description,ticket_status,ticket_customer_url,ticket_admin_url';
        $emailtemplate->save();
        $emailtemplate = EmailTemplate::where('code','Send_email_to_customer_when_Ticket_is_Overdue')->first();
        $emailtemplate->variables_used = 'ticket_username,ticket_title,ticket_overduetime,ticket_id,ticket_description,ticket_status,ticket_customer_url,ticket_admin_url';
        $emailtemplate->save();
        $emailtemplate = EmailTemplate::where('code','Send_email_to_customer_when_change_email')->first();
        $emailtemplate->variables_used = 'username,useremail,ticket_customer_url';
        $emailtemplate->body = '<div>Hello {{username}},</div><div><br></div><div>We have received a request to change the email address associated with your account from {{useremail}} to a new email address.</div><div><br></div><div>To complete the process, please click on the link below:</div><div><br></div><div><a href="{{ticket_customer_url}}" target="_blank">{{ticket_customer_url}}</a></div><div><br></div><div>If you did not initiate this request or if you have any concerns, please contact our support team for assistance.</div><div><br></div><div>Thank you,</div><div>Support Team</div>';
        $emailtemplate->save();
        $emailtemplate = EmailTemplate::where('code','Send_email_to_admin_users_when_change_email')->first();
        $emailtemplate->variables_used = 'username,useremail,ticket_admin_url';
        $emailtemplate->body = '<div>Hello {{username}},</div><div><br></div><div>We have received a request to change the email address associated with your account from {{useremail}} to a new email address.</div><div><br></div><div>To complete the process, please click on the link below:</div><div><br></div><div><a href="{{ticket_admin_url}}" target="_blank">{{ticket_admin_url}}</a></div><div><br></div><div>If you did not initiate this request or if you have any concerns, please contact our support team for assistance.</div><div><br></div><div>Thank you,</div><div>Support Team</div>';
        $emailtemplate->save();
        $emailtemplate = EmailTemplate::where('code','Send_email_to_customer_when_change_email_otp_verification')->first();
        $emailtemplate->variables_used = 'otp,useremail,username';
        $emailtemplate->save();
        $emailtemplate = EmailTemplate::where('code','Send_email_to_admin_when_ticket_draft_created')->first();
        $emailtemplate->variables_used = 'username,ticket_id,ticket_description,created_or_respond,ticket_admin_url';
        $emailtemplate->save();
        $emailtemplate = EmailTemplate::where('code','customer_send_ticket_created_that_holiday_or_announcement')->first();
        $emailtemplate->variables_used = 'ticket_username,ticket_title,ticket_id,ticket_status,comment,ticket_admin_url,ticket_customer_url';
        $emailtemplate->save();
        $emailtemplate = EmailTemplate::where('code','two_factor_authentication_otp_send')->first();
        $emailtemplate->variables_used = 'otp,email,name';
        $emailtemplate->save();
        $emailtemplate = EmailTemplate::where('code','send_a_reply_to_the_customer_when_a_customer_responds_to_a_closed_email_ticket')->first();
        $emailtemplate->variables_used = 'ticket_id,ticket_username,ticket_title,ticket_description,ticket_customer_url,ticket_admin_url,url';
        $emailtemplate->save();
        $emailtemplate = EmailTemplate::where('code','admin_send_email_ticket_reply')->first();
        $emailtemplate->variables_used = 'ticket_username,ticket_title,ticket_id,ticket_status,comment,ticket_admin_url,ticket_customer_url';
        $emailtemplate->save();
        $emailtemplate = EmailTemplate::where('code','when_ticket_assign_to_other_employee')->first();
        $emailtemplate->variables_used = 'ticket_username,ticket_id,ticket_title,ticket_description,ticket_admin_url';
        $emailtemplate->save();
        $emailtemplate = EmailTemplate::where('code','when_send_customnotify_email_to_selected_member')->first();
        $emailtemplate->variables_used = 'notification_subject,notification_message,notification_tag';
        $emailtemplate->save();
        $emailtemplate = EmailTemplate::where('code','when_send_customnotify_email_todelete_member')->first();
        $emailtemplate->variables_used = 'customer_username,customer_email,customer_time,customer_months,ticket_customer_url';
        $emailtemplate->save();
        $emailtemplate = EmailTemplate::where('code','admin_sendemail_whenticketclosed')->first();
        $emailtemplate->variables_used = 'ticket_username,ticket_id,ticket_title,ticket_description,ticket_status,comment,ticket_customer_url,ticket_admin_url';
        $emailtemplate->save();
        $emailtemplate = EmailTemplate::where('code','customer_sendemail_whenticketclosed')->first();
        $emailtemplate->variables_used = 'ticket_username,ticket_id,ticket_title,ticket_description,ticket_status,comment,ticket_customer_url,ticket_admin_url';
        $emailtemplate->save();
        $emailtemplate = EmailTemplate::where('code','CCmail_sendemail_whenticketclosed')->first();
        $emailtemplate->variables_used = 'ticket_username,ticket_id,ticket_title,ticket_status,comment,ticket_description,ticket_customer_url,ticket_admin_url';
        $emailtemplate->save();
        $emailtemplate = EmailTemplate::where('code','admin_sendemail_whenticketreopen')->first();
        $emailtemplate->variables_used = 'ticket_username,ticket_id,ticket_title,ticket_description,ticket_status,ticket_customer_url,ticket_admin_url';
        $emailtemplate->save();
        $emailtemplate = EmailTemplate::where('code','send_mail_to_customer_when_ticket_closed_by_admin')->first();
        $emailtemplate->variables_used = 'closed_agent_name,closed_agent_role,ticket_username,ticket_title,ticket_id,comment,ticket_status,ratinglink,ticket_customer_url,ticket_admin_url';
        $emailtemplate->save();
        $emailtemplate = EmailTemplate::where('code','send_mail_to_agent_when_ticket_closed_by_admin_or_agent')->first();
        $emailtemplate->variables_used = 'closed_agent_name,closed_agent_role,ticket_username,ticket_title,ticket_id,comment,ticket_status,ticket_customer_url,ticket_admin_url';
        $emailtemplate->save();
        $emailtemplate = EmailTemplate::where('code','send_mail_admin_panel_users_when_category_changed')->first();
        $emailtemplate->variables_used = 'ticket_id,ticket_title,ticket_description,ticket_status,ticket_oldcategory,ticket_changedcategory,ticket_customer_url,ticket_admin_url';
        $emailtemplate->save();
        $emailtemplate = EmailTemplate::where('code','send_mail_customer_when_category_changed')->first();
        $emailtemplate->variables_used = 'ticket_username,ticket_id,ticket_title,ticket_description,ticket_status,ticket_oldcategory,ticket_changedcategory,ticket_customer_url,ticket_admin_url';
        $emailtemplate->save();
        $emailtemplate = EmailTemplate::where('code','send_mail_to_admin_when_ticket_note_created')->first();
        $emailtemplate->variables_used = 'ticket_id,note_username,ticket_note,ticket_admin_url';
        $emailtemplate->save();
        $emailtemplate = EmailTemplate::where('code','customer_send_guestticket_created_with_attachment_failed')->first();
        $emailtemplate->variables_used = 'ticket_id,ticket_username,ticket_title,ticket_file_format,ticket_file_size,ticket_file_count,ticket_description,ticket_customer_url,ticket_admin_url';
        $emailtemplate->save();
        $emailtemplate = EmailTemplate::where('code','guestticket_email_verification')->first();
        $emailtemplate->variables_used = 'guestotp,guestemail,guestname';
        $emailtemplate->save();
        $emailtemplate = EmailTemplate::where('code','guestticket_email_verification_view')->first();
        $emailtemplate->variables_used = 'guestotp,guestemail,guestname';
        $emailtemplate->save();

        $deleteTemplate = EmailTemplate::where('code','Send_email_to_customer_when_voilation_is_ticket_or_customer')->first();
        if ($deleteTemplate) {
            $deleteTemplate->delete();
        }

        DB::table('message_templates')->insert([
            [
                'code' => 'created_ticket',
                'title' => 'Message to customer when a ticket is created',
                'variables_used' => 'ticket_username,ticket_id,ticket_title,ticket_status,ticket_description,ticket_customer_url',
                'body' => '<p>Dear {{ticket_username}},</p><p>We wish to acknowledge the receipt of your inquiry. A ticket with reference id {{ticket_id}} has been generated for your request, and our dedicated support team is now poised to address it promptly.</p><p>Rest assured, our specialists will thoroughly review your query and provide a comprehensive response within the next 24 to 48 hours.</p><p>For real-time updates on the status of your ticket or to offer additional insights, please access the provided link. {{ticket_customer_url}}</p><p>We appreciate your patience and trust in our services.</p><p>Best regards,</p><p>Support Team<br></p>',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'reply_to_customer',
                'title' => 'Message to customer when they get a reply to the ticket',
                'variables_used' => 'ticket_username,ticket_title,ticket_id,ticket_status,comment,ticket_customer_url',
                'body' => '<p>Dear {{ticket_username}},</p><p>We appreciate your patience. Following a thorough review of your inquiry, we’ve provided a detailed response for your ticket {{ticket_id}}.</p><p>Your satisfaction remains our utmost priority. Kindly respond to the ticket by accessing the link provided below.</p><p>Link: {{ticket_customer_url}}</p><p>If you require further assistance or clarification, feel free to reach out.</p><p>Best regards,</p><p>Support Team</p>',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'ticket_closed',
                'title' => 'Message to customer when the ticket is closed',
                'variables_used' => 'ticket_username,ticket_id,ticket_title,ticket_description,ticket_status,comment,ticket_customer_url',
                'body' => '<p>Dear {{ticket_username}},</p><p>We’re pleased to inform you that your inquiry has been successfully resolved. The ticket {{ticket_id}} associated with your request has been closed.</p><p>Our team has addressed your query, and we’re here to confirm its closure. If you have any further concerns or require additional assistance, feel free to reach out.</p><p>To check the status of your ticket, please visit: {{ticket_customer_url}}.</p><p>Thank you for your patience throughout the process.</p><p>Best regards,</p><p>Support Team</p>',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'ticket_auto_closed',
                'title' => 'Message to customer when the ticket is auto-closed',
                'variables_used' => 'ticket_username,ticket_title,ticket_id,ticket_status,comment,ticket_customer_url',
                'body' => '<p>Dear {{ticket_username}},</p><p>Your ticket has been closed successfully because there was no response from your end, so the ticket was closed automatically {{ticket_id}}.&nbsp;</p><p>If you want to reopen this ticket, please log in to your portal.</p><p>Ticket URL : {{ticket_customer_url}}</p><p>Sincerely,</p><p>Support Team</p>',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'customer_auto_response',
                'title' => 'Message to customer when the customer does not respond to the ticket',
                'variables_used' => 'ticket_username,ticket_closingtime,ticket_title,ticket_id,ticket_description,ticket_status,replystatus,ticket_customer_url',
                'body' => '<p>Dear {{ticket_username}},</p><p>Your ticket is in an idle state. Our team is waiting for your response.</p><p>If you do not respond to this ticket {{ticket_id}}, it will be automatically closed after {{ticket_closingtime}} days.</p><p>Title : {{ticket_title}}</p><p>Ticket URL : {{ticket_customer_url}}</p><p>Sincerely,</p><p>Support Team</p>',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('storage_disks')->insert([
            [
                'name' => 'S3',
                'storage_disk' => 'S3',
                'provider' => 'Uhelp\Addons\App\Http\Controllers\Storage\S3Controller',
                'credentials_data' => '{"access_key_id":null, "secret_access_key":null, "default_region":null, "bucket":null, "endpoint":null}',
                'status' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Storj',
                'storage_disk' => 'storj',
                'provider' => 'Uhelp\Addons\App\Http\Controllers\Storage\StorjController',
                'credentials_data' => '{"access_key_id":null, "secret_access_key":null, "default_region":null, "bucket":null, "endpoint":null}',
                'status' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        DB::table('addons')->insert([
            [
                'name' => 'Storj cloud storage',
                'type' => 'Storage',
                'image' => 'public/build/assets/images/storj.png',
                'version' => 'v 1.0',
                'handler' => 'Uhelp\Addons\App\Http\Controllers\Storage\StorjController',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'S3 cloud storage',
                'type' => 'Storage',
                'image' => 'public/build/assets/images/S3.png',
                'version' => 'v 1.0',
                'handler' => 'Uhelp\Addons\App\Http\Controllers\Storage\S3Controller',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

    }
}
