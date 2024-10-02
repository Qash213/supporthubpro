<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use Spatie\Permission\Models\Permission;

class UpdateVersion3_2 extends Seeder
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
                'key' => 'Employe_google_two_fact',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'Employe_email_two_fact',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'Customer_google_two_fact',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'Customer_email_two_fact',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        DB::table('email_templates')->insert([
            [
                'code' => 'customer_send_ticket_created_that_holiday_or_announcement',
                'title' => 'Send an email to the customer during ticket creation or responding to the ticket in the case of a holiday.',
                'subject' => 'We have successfully received your ticket/response even though we are on holiday.',
                'body' => '<p>Dear {{ticket_username}},</p><p style="color: #252525;">We hope this response finds you well. Thank you for reaching out to us and submitting your ticket/response. We confirm that we have successfully received your request, and our team appreciates your trust in our support services.</p><p style="color: #252525;">However, we regret to inform you that today is a company-wide holiday, and our support representatives are currently away from the office. As a result, we won’t be able to review and address your ticket until we return to work.</p><p style="color: #252525;">Rest assured, as soon as we are back in the office, one of our experienced support representatives will thoroughly review your ticket and provide you with a prompt response.</p><p style="color: #252525;">We kindly request your understanding and patience during this time.</p><p style="color: #252525;">We apologize for any inconvenience this delay may cause and assure you that we will do our best to make it up to you with an accurate and timely response.</p><p style="color: #252525;">If you have any additional information to add to your ticket or if there are any changes to your request, please don’t hesitate to update your ticket, and we will review the updated information once we are back in the office.</p><p style="color: #252525;">Thank you for your understanding and cooperation. We look forward to assisting you upon our return.</p><p>To view the status of the ticket or add comments, please visit</p><p><a href="{{ticket_customer_url}}" target="_blank">{{ticket_customer_url}}</a></p><p><br></p><p>Sincerely,</p><p>Support Team</p>',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'two_factor_authentication_otp_send',
                'title' => 'Two factor authentication verification otp',
                'subject' => 'Verify Two factor authentication otp.',
                'body' => '<p class="root-block-node" data-paragraphid="16" data-from-init="true" data-changed="false">Dear {{guestname}},</p><p class="root-block-node" data-paragraphid="17" data-from-init="true" data-changed="false">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; {{otp}} is your one time password (otp) to login into application.</p><p class="root-block-node" data-paragraphid="17" data-from-init="true" data-changed="false">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; {{guestemail}}</p><p class="root-block-node" data-paragraphid="17" data-from-init="true" data-changed="false">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Please do not share your otp’s with anyone.</p>
                <p class="root-block-node" data-paragraphid="19" data-from-init="true" data-changed="false">Sincerely,</p>
                <p class="root-block-node" data-paragraphid="20" data-from-init="true" data-changed="false">Support Team</p>',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'send_a_reply_to_the_customer_when_a_customer_responds_to_a_closed_email_ticket',
                'title' => 'Send a reply to the customer, when a customer responds to a closed email to ticket',
                'subject' => 'This is Information for closed email to ticket.',
                'body' => '<p>Dear {{ticket_username}},</p><p style="color: #252525;">We hope this response finds you well. We want to inform you that the ticket you recently tried to reply to has been closed in our system. We apologize for any inconvenience this may have caused.</p><p style=""><span style="color: rgb(37, 37, 37);">To check the status and details of your closed ticket, kindly click on the following URL: </span><font color="#252525">{{ticket_customer_url}}</font><span style="color: rgb(37, 37, 37);">.</span></p><p style="color: #252525;">If your initial query was resolved to your satisfaction, we are pleased to have been of assistance. However, if you have any additional questions, concerns, or new inquiries, we kindly request you to create a new ticket for each of them.</p><p style="color: #252525;">Creating a new ticket allows us to ensure proper tracking, prioritization, and a timely response from our support representatives. This approach enables us to deliver efficient and focused support tailored to each individual query.</p><p style="color: #252525;">Please follow the steps below to create a new ticket:</p><ol style="color: #252525;">
                <li>Visit our support portal at {{url}}.</li>
                <li>Click on the "Create Ticket" or "Submit a New Ticket" button.</li>
                <li>Fill in the required details, providing a clear description of your query or issue.</li>
                <li>Choose the appropriate category for your request.</li>
                <li>Submit the ticket, and you will receive an email confirmation with a unique ticket number.</li>
                </ol><p style="color: #252525;">Rest assured, we are committed to resolving your queries and providing the best possible support experience.</p><p style="color: #252525;">Thank you for your understanding and cooperation in this matter. We value your continued trust in our services and are eager to assist you further.</p><p class="root-block-node" data-paragraphid="19" data-from-init="true" data-changed="false">Sincerely,</p><p class="root-block-node" data-paragraphid="20" data-from-init="true" data-changed="false">Support Team</p>',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'send_mail_customer_when_a_customer_responds_to_a_restricted_mails',
                'title' => 'Send an email to customer they reply to a general mail sent to them by the application.',
                'subject' => 'You are not allowed to reply to this mail.',
                'body' => '<p>Dear User,</p><p>Thank you for reaching out to us. Please note that the email you have received is an automated system-generated response and is not intended to receive direct replies. Our system has processed your inquiry and is actively working to address any concerns you may have raised.</p><p>Sincerely,</p><p>Support Team</p>',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        $ticketsdelete = Permission::find('88');
        if($ticketsdelete){

            $ticketsdelete->delete();
        }

       

    }
}
