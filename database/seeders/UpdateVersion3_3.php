<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;
use DB;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UpdateVersion3_3 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            [
                'key' => 'cust_or_tick_violation',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'max_tic_to_violation',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'customer_inactive_auto_logout',
                'value' => 'off',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'customer_inactive_auto_logout_time',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'admin_users_inactive_auto_logout',
                'value' => 'off',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'admin_users_inactive_auto_logout_time',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'AUTO_OVERDUE_CUSTOMER',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'CUSTOMER_RESTRICT_EDIT_REPLY',
                'value' => 'no',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'custreplyeditwithintime',
                'value' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'cust_email_update',
                'value' => 'off',
                'created_at' => now(),
                'updated_at' => now()
            ],

        ]);

        $role = Role::where('name', 'Superadmin')->first();
        $permissions = Permission::get();
        foreach ( $permissions as $code ) {
			$role->givePermissionTo($code);
		};

        DB::table('storage_disks')->insert([
            [
                'name' => 'local',
                'storage_disk' => 'public',
                'provider' => 'App\Http\Controllers\Storage\LocalStorageController',
                'credentials_data' => '{"access_key_id":null, "secret_access_key":null, "default_region":null, "bucket":null, "endpoint":null}',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        DB::table('email_templates')->insert([
            [
                'code' => 'Send_email_to_admin_when_ticket_draft_created',
                'title' => 'When the admin panel users create a ticket draft.',
                'subject' => 'Ticket draft is created.',
                'body' => '<p>Dear admin panel users ,</p><p>I hope this email finds you well. I am writing to inform you that a ticket draft has been created by {{username}}. The details of the ticket draft are as follows:</p><p>Ticket ID: {{ticket_id}}</p><p>Agent Name: {{username}}</p><p>Ticket Summary: {{ticket_description}}</p><p>Ticket View : {{ticket_admin_url}}</p><p>Best regards,</p><p>Support Team</p>',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'Send_email_to_customer_when_voilation_is_ticket_or_customer',
                'title' => 'When the admin panel users added a ticket or a customer as a violated.',
                'subject' => 'Ticket Violation is created.',
                'body' => '<p>Dear {{ticket_username}},</p><p>I hope this email finds you well. I am writing to inform you that this ticket is involved in a violation, it is recognized by our team member {{username}}. The details of the voilated ticket are as follows:</p><p>Ticket ID: {{ticket_id}}</p><p>Agent Name: {{username}}</p><p>Ticket View : {{ticket_customer_url}}</p><p>Best regards,</p><p>Support Team</p>',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'Send_email_to_customer_when_Ticket_is_Overdue',
                'title' => 'Send email to customer, when Ticket is Overdue.',
                'subject' => 'Please wait for response, we are busy with some other issues.',
                'body' => '<p class="root-block-node" data-paragraphid="2" data-from-init="true" data-changed="false">Dear {{ticket_username}},</p><p class="root-block-node" data-paragraphid="10" data-from-init="true" data-changed="false">We hope this response finds you well. We would like to sincerely apologize for the delayed response to your inquiry. Our support team has been overwhelmed with a high volume of ticket flow, which has caused some delays in our response time. We understand that this may have inconvenienced you, and we sincerely apologize for any frustration this may have caused.</p><p class="root-block-node" data-paragraphid="10" data-from-init="true" data-changed="false">We want to assure you that we are actively working to address the ticket flow and resolve all pending inquiries as quickly as possible.</p><p class="root-block-node" data-paragraphid="10" data-from-init="true" data-changed="false">We understand your time is valuable, and we want to assure you that once we have resolved the ticket flow, we will promptly get back to you with a comprehensive response to your inquiry. Your satisfaction is our top priority.</p><p class="root-block-node" data-paragraphid="10" data-from-init="true" data-changed="false">We appreciate your patience and understanding during this time. If you have any further questions or concerns, please do not hesitate to contact us.</p><p class="root-block-node" data-paragraphid="10" data-from-init="true" data-changed="false">Thank you for your continued support.</p><p class="root-block-node" data-paragraphid="10" data-from-init="true" data-changed="false"><br></p><p> Title : {{ticket_title}}<br>Ticket URL : <a href="{{ticket_customer_url}}" target="_blank"><font color="#0000ff">VIEW Ticket&nbsp;</font></a></p><p><br></p><p class="root-block-node" data-paragraphid="19" data-from-init="true" data-changed="false">Best regards,</p><p class="root-block-node" data-paragraphid="19" data-from-init="true" data-changed="false">Support Team</p>',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'Send_email_to_customer_when_change_email',
                'title' => 'Send an email to the customer when their email address is updated.',
                'subject' => 'Email Verification: Reset Your Email Address',
                'body' => '<div>Hello {{username}},</div><div><br></div><div>We have received a request to change the email address associated with your account from {{useremail}} to a new email address.</div><div><br></div><div>To complete the process, please click on the link below:</div><div><br></div><div>{{ticket_customer_url}}</div><div><br></div><div>If you did not initiate this request or if you have any concerns, please contact our support team for assistance.</div><div><br></div><div>Thank you,</div><div>Support Team</div>',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'Send_email_to_admin_users_when_change_email',
                'title' => 'Send an email to the admin users when their email address is updated.',
                'subject' => 'Email Verification: Reset Your Email Address',
                'body' => '<div>Hello {{username}},</div><div><br></div><div>We have received a request to change the email address associated with your account from {{useremail}} to a new email address.</div><div><br></div><div>To complete the process, please click on the link below:</div><div><br></div><div>{{ticket_admin_url}}</div><div><br></div><div>If you did not initiate this request or if you have any concerns, please contact our support team for assistance.</div><div><br></div><div>Thank you,</div><div>Support Team</div>',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'Send_email_to_customer_when_change_email_otp_verification',
                'title' => 'When the customer changes their email address, an email should be sent with OTP verification.',
                'subject' => 'OTP Verification Required: Email Address Change Request',
                'body' => '<p>Dear {{username}},</p><p>We have received a request to change the email address associated with your account. To proceed with this change, please enter the following one-time password (OTP):</p><p>OTP: {{otp}}</p><p>Email address to be changed: {{useremail}}</p><p>Please do not share your OTP with anyone for security reasons.</p><p>If you did not initiate this request or if you have any concerns, please contact our support team for assistance.</p><p><br></p><p>Best regards,</p><p>Support Team</p>',
                'created_at' => now(),
                'updated_at' => now()
            ],

        ]);

        $emaildelete = EmailTemplate::where('code','send_a_reply_to_the_customer_when_a_customer_responds_to_a_suspended_email_ticket')->first();

        if($emaildelete){
            $emaildelete->delete();
        }


    }
}
