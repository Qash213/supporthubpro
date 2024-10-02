<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use Spatie\Honeypot\ProtectAgainstSpam;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ArticleCommentController;
use App\Http\Controllers\ArticleReplyController;
use App\Http\Controllers\Contactform\ContactController;
use App\Http\Controllers\CategorypageController;

use App\Http\Controllers\GuestticketController;
use App\Http\Controllers\User\Ticket\CommentsController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\CannedmessagesController;
use App\Http\Controllers\Admin\EnvatoApiTokenController;
use App\Http\Controllers\Admin\EnvatoAppinfoController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\Admin\HolidayController;
use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\LiveChatController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Admin\AdminSettingController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(ProtectAgainstSpam::class)->group(function() {

	Route::middleware(['checkinstallation'])->group(function () {


		Route::middleware(['admincountryblock', 'datarecovery','throttle:refresh', 'ipblockunblock'])->group(function () {
            Route::group([ 'prefix'	 => 'admin'], function () {
                Route::get('envatoapitoken', [EnvatoApiTokenController::class,'index'])->name('admin.envatoapitoken');
                Route::post('envatoapitoken',[EnvatoApiTokenController::class,'storeupdate'])->name('admin.envatoapitoken.storeupdate');
                Route::get('envatolicensesearch', [EnvatoApiTokenController::class,'licensesearch'])->name('admin.envatolicensesearch');
                Route::post('envatolicensesearch',[EnvatoApiTokenController::class,'licensesearchget'])->name('admin.envatolicensesearchget');
                Route::post('ticketlicenseverify',[EnvatoApiTokenController::class,'ticketlicenseverify'])->name('admin.ticketlicenseverify');

                Route::get('/cannedmessages', [CannedmessagesController::class,'index'])->name('admin.cannedmessages');
                Route::get('/cannedmessages/create', [CannedmessagesController::class,'create'])->name('admin.cannedmessages.create');
                Route::post('/cannedmessages/create', [CannedmessagesController::class,'store'])->name('admin.cannedmessages.store');
                Route::get('/cannedmessages/edit/{id}', [CannedmessagesController::class,'edit'])->name('admin.cannedmessages.edit');
                Route::post('/cannedmessages/update/{id}',[CannedmessagesController::class,'update'])->name('admin.cannedmessages.update');
                Route::post('/cannedmessages/status/{id}',[CannedmessagesController::class,'status'])->name('admin.cannedmessages.statuschange');
                Route::post('/cannedmessages/delete/{id}', [CannedmessagesController::class,'destroy'])->name('admin.cannedmessages.delete');
                Route::post('/cannedmessages/delete', [CannedmessagesController::class,'destroyall'])->name('admin.cannedmessages.deleteall');
            });

        });
        Route::get('applicationinfodata',[EnvatoAppinfoController::class,'testinginfo'])->name('admin.testinginfo');
        Route::group(['prefix' => 'admin'], function(){
            Route::get('licenseinfo', [EnvatoAppinfoController::class,'index'])->name('admin.licenseinfo');
            Route::post('licenseinfoenter', [EnvatoAppinfoController::class,'store'])->name('admin.licenseinfoenter');
            Route::post('licenseinfo/{id}', [EnvatoAppinfoController::class,'envatogetdetails'])->name('admin.envatogetdetails');
        });
    });



	Route::middleware(['checkinstallation'])->group(function () {


		Route::middleware(['admincountryblock','datarecovery','throttle:refresh', 'ipblockunblock'])->group(function () {


			Route::group(['namespace' => 'Admin', 'prefix'	 => 'admin'], function () {

				Auth::routes([
					'register'	=>	false
				]);

				Route::get('/{token}/reset-password', 'Auth\ResetPasswordController@showResetForm')->name('reset.passwords');
				Route::get('/change-password', 'ChangepasswordController@index');
                Route::get('/validatelog', 'Auth\LoginController@validatelog');
				Route::post('/change-password', 'ChangepasswordController@changePassword');
                Route::get('adminemailtwofactresendotp/{email}','AdminprofileController@resendotp')->name('admin.resendotp');
				Route::get('adminemailtwofact/{email}','AdminprofileController@emailtwofactorlogin')->name('admin.emailtwofactorlogin');
				Route::post('admingoogle2faotpverify','AdminprofileController@admingoogle2faotpverify')->name('admingoogle2falogin.otpverify');
				Route::get('adminindexlogin/{email}','AdminprofileController@google2faadminlogin')->name('admin.google2falogin');
				Route::post('emptwofactqr','AdminprofileController@emptwofactqr')->name('emptwofactqr');
				Route::post('empgoogle2faotp','AdminprofileController@empgoogle2faotp')->name('empgoogle2faotp.verify');
				Route::post('empemail2faotp','AdminprofileController@empemail2fa')->name('user.emailtwofactor');
				Route::get('emp2faotpverify','AdminprofileController@emp2faotpverify')->name('user.emp2faotpverify');
				Route::post('enabletwofactor','AdminprofileController@enabletwofactor')->name('admin.enabletwofactor');

				Route::middleware('auth','admin.auth')->group( function () {
                    Route::get('/holidays',[HolidayController::class,'index'])->name('admin.holidays');
                    Route::post('/holidays/holidaysimport',[HolidayController::class,'holidayscsv']);
                    Route::get('/holidays/holidaysimport',[HolidayController::class,'holidaysimport'])->name('holidays.import');
                    Route::get('/holidays/massdelete',[HolidayController::class,'massdelete']);
                    Route::get('/holidays/{id}',[HolidayController::class,'edit']);
                    Route::get('/holidays/delete/{id}',[HolidayController::class,'delete']);
                    Route::post('/holidays/statuschange/{id}',[HolidayController::class,'statuschange'])->name('holidays.statuschange');
                    Route::post('/saveholidays',[HolidayController::class,'saveholidays'])->name('saveholidays');


                    Route::get('/livechat',[LiveChatController::class,'index'])->name('admin.livechat');
                    Route::get('/operators',[LiveChatController::class,'operators'])->name('admin.operators');
                    Route::get('/livechat-flow/{id}',[LiveChatController::class,'liveChatFlow']);
                    Route::get('/livechat-flow/test-it-out/{id}',[LiveChatController::class,'testItOut']);
                    Route::get('/chat-responses',[LiveChatController::class,'chatResponses'])->name('admin.chatResponses');
                    Route::get('/solvedchats',[LiveChatController::class,'solvedChats'])->name('admin.solvedChats');
                    Route::get('/myopened',[LiveChatController::class,'myOpenedChats'])->name('admin.myOpenedChats');
                    Route::get('/livechat-settings',[LiveChatController::class,'livechatSettings'])->name('admin.livechatSettings');
                    Route::post('/operators/broadcastoperator',[LiveChatController::class,'broadcastoperator'])->name('admin.broadcastoperator');
                    Route::get('/operators/singleoperator/{id}',[LiveChatController::class,'singleoperator'])->name('admin.singleoperator');
                    Route::get('/operators/conversationdelete/{id}',[LiveChatController::class,'conversationdelete'])->name('admin.conversationdelete');
                    Route::get('/operators/markasunread/{id}',[LiveChatController::class,'markasunread'])->name('admin.markasunread');
                    Route::get('/operators/markasread/{id}',[LiveChatController::class,'markasread'])->name('admin.markasread');
                    Route::post('/operators/groupbroadcastoperator',[LiveChatController::class,'groupbroadcastoperator'])->name('admin.groupbroadcastoperator');
                    Route::get('/operators/groupconversion/{id}',[LiveChatController::class,'groupconversion'])->name('admin.groupconversion');
                    Route::post('/operators/groupconversionstore/{id}',[LiveChatController::class,'groupconversionstore'])->name('admin.groupconversionstore');
                    Route::get('/operators/groupconversiondelete/{id}',[LiveChatController::class,'groupconversiondelete'])->name('admin.groupconversiondelete');
                    Route::post('/livechat-flow/save',[LiveChatController::class,'ChatFlowSave'])->name('admin.liveChatFlowSave');
                    Route::get('/livechat-flow/delete/{id}',[LiveChatController::class,'deleteChatFlow'])->name('admin.liveChatFlowDelete');
                    Route::post('/livechat-flow/active-save',[LiveChatController::class,'activeChatFlow'])->name('admin.liveChatActiveFlowSave');
                    Route::post('/livechat/engage-conversation',[LiveChatController::class,'engageConversation'])->name('admin.engageConversation');
                    Route::get('/livechat/conversationleave',[LiveChatController::class,'conversationLeave'])->name('admin.conversationLeave');
                    Route::post('/livechat/conversation-reassign',[LiveChatController::class,'conversationReassign'])->name('admin.conversationReassign');
                    Route::get('/livechat/conversation-delete',[LiveChatController::class,'livechatConversationDelete'])->name('admin.livechatConversationDelete');
                    Route::get('/livechat/mark-as-solved',[LiveChatController::class,'markAsSolved'])->name('admin.markAsSolved');
                    Route::post('/livechat/live-chat-notifications-setting',[LiveChatController::class,'liveChatNotificationsSetting'])->name('admin.liveChatNotificationsSetting');
                    Route::post('/livechat/live-chat-notifications-sound',[LiveChatController::class,'liveChatNotificationsSound'])->name('admin.liveChatNotificationsSound');
                    Route::post('/livechat/live-chat-flow-settings',[LiveChatController::class,'liveChatFlowSettings'])->name('admin.liveChatFlowSettings');
                    Route::post('/livechat/live-chat-file-settings',[LiveChatController::class,'liveChatFileSettings'])->name('admin.liveChatFileSettings');
                    Route::post('/livechat/live-chat-cust-fileupload',[LiveChatController::class,'liveChatCustFileUpload'])->name('admin.liveChatCustFileUpload');
                    Route::get('/livechat-notifications-sounds',[LiveChatController::class,'livechatNotificationsSonds'])->name('admin.livechatNotificationsSonds');
                    Route::get('/livechat-notifications-sounds-delete',[LiveChatController::class,'livechatNotificationsSondsDelete'])->name('admin.livechatNotificationsSondsDelete');
                    Route::get('/livechat-notifications-masssounds-delete',[LiveChatController::class,'livechatNotificationsMassSondsDelete'])->name('admin.livechatNotificationsMassSondsDelete');
                    Route::post('/livechat/live-chat-icon-size',[LiveChatController::class,'liveChatIconSize'])->name('admin.liveChatIconSize');
                    Route::post('/livechat/live-chat-position',[LiveChatController::class,'liveChatPosition'])->name('admin.liveChatPosition');
                    Route::post('/livechat/live-chat-offline-setting',[LiveChatController::class,'liveChatOfflineSetting'])->name('admin.liveChatOfflineSetting');
                    Route::get('/livechat/get-cannedmessages',[LiveChatController::class,'getCannedmessages'])->name('admin.getCannedmessages');
                    Route::post('/livechat/operators-notifications-setting',[LiveChatController::class,'operatorsNotificationsSetting'])->name('admin.operatorsNotificationsSetting');
                    Route::post('/livechat/livechat-credentials',[LiveChatController::class,'livechatCredentials'])->name('admin.livechatCredentials');
                    Route::post('/livechat/livechatssldstore',[LiveChatController::class,'livechatssldstore'])->name('admin.livechatssldstore');
                    Route::post('/livechat/livechat-autosave',[LiveChatController::class,'livechatAutoSave'])->name('admin.livechatAutoSave');
                    Route::post('/livechat/livechatAutoDelete',[LiveChatController::class,'livechatAutoDelete'])->name('admin.livechatAutoDelete');
                    Route::get('/livechat-tickets',[LiveChatController::class,'livechatTickets'])->name('admin.livechatTickets');
                    Route::get('/livechat-ticket-massdelete',[LiveChatController::class,'livechatTicketMassDelete'])->name('admin.livechatTicketMassDelete');
                    Route::get('/livechat-ticket-delete',[LiveChatController::class,'livechatTicketDelete'])->name('admin.livechatTicketDelete');
                    Route::post('/livechat-feedback-dropdown',[LiveChatController::class,'livechatFeedbackDropdown'])->name('admin.livechatFeedbackDropdown');
                    Route::post('/livechat-cust-welecome-title',[LiveChatController::class,'LivechatCustWelcomeMsg'])->name('admin.LivechatCustWelcomeMsg');
                    Route::get('/livechat-allratings',[LiveChatController::class,'livechatAllRatings'])->name('admin.livechatAllRatings');
                    Route::get('/livechat-employer-ratings/{id}',[LiveChatController::class,'livechatEmpliyerRatings'])->name('admin.livechatEmpliyerRatings');
                    Route::get('/livechat-delete-feedback/{id}',[LiveChatController::class,'livechatDeleteFeedback'])->name('admin.livechatDeleteFeedback');
                    Route::post('/security-settings',[LiveChatController::class,'securitySettings'])->name('admin.securitySettings');


					Route::get('/mark-as-read', 'AdminDashboardController@markNotification')->name('admin.markNotification');

					Route::get('/', 'AdminDashboardController@index')->name('admin.dashboard');
					Route::get('/activeticket', 'AdminDashboardController@activeticket')->name('admin.activeticket');
					Route::get('/closedticket', 'AdminDashboardController@closedticket')->name('admin.closedticket');
					Route::group(['prefix' => 'activeticket', 'as' => 'admin.activeticket.'], function(){
						Route::get('/inprogress', 'AdminTicketViewController@allactiveinprogresstickets')->name('allactiveinprogresstickets');
						Route::get('/reopen', 'AdminTicketViewController@allactivereopentickets')->name('allactivereopentickets');
						Route::get('/onhold', 'AdminTicketViewController@allactiveonholdtickets')->name('allactiveonholdtickets');
						Route::get('/assigned', 'AdminTicketViewController@allactiveassignedtickets')->name('allactiveassignedtickets');
					});
					Route::get('/onholdtickets', 'AdminDashboardController@onholdticket')->name('admin.onholdticket');
					Route::get('/overduetickets', 'AdminDashboardController@overdueticket')->name('admin.overdueticket');
					Route::get('/allassignedtickets', 'AdminDashboardController@adminallassignedtickets')->name('admin.allassignedtickets');
					Route::get('/recenttickets', 'AdminDashboardController@recenttickets')->name('admin.recenttickets');
					Route::get('notification/{id}', 'AdminDashboardController@Notificationview')->name('admin.notiication.view');
					Route::get('/categories', 'CategoriesController@index')->name('categorys.index');
					Route::post('/categories/create', 'CategoriesController@store');
					Route::get('/categories/{id}/edit', 'CategoriesController@show');
					Route::get('/categories/status{id}', 'CategoriesController@status');
					Route::get('/categoryassigned/{id}', 'CategoriesController@agentshow');
					Route::get('/category/list/{ticket_id}', 'CategoriesController@categorylist');
					Route::get('/categorylist', 'CategoriesController@categorylistshow');
					Route::post('/categoryenvatoassign', 'CategoriesController@categoryenvatoassign');
					Route::post('/category/change/', 'CategoriesController@categorychange');
					Route::post('/category/all/', 'CategoriesController@categorygetall')->name('category.admin.all');
					Route::get('/subcategories', 'CategoriesController@subcategoryindex')->name('subcategorys.index');
					Route::post('/subcategories', 'CategoriesController@subcategorystore')->name('subcategorys.store');
					Route::get('/subcategories/{id}/edit', 'CategoriesController@subcategoryshow')->name('subcategorys.show');
					Route::post('/subcategory/status/', 'CategoriesController@subcategorystatusupdate')->name('category.admin.subcategorystatusupdate');
					Route::post('/subcategory/delete/', 'CategoriesController@subcategorydelete')->name('category.admin.subcategorydelete');
					Route::get('/subcategory/deleteall', 'CategoriesController@subcategorydeleteall')->name('category.admin.subcategorydeleteall');
					Route::get('/groupassigned/{id}', 'CategoriesController@groupshow');
					Route::post('/groupcategory/group', 'CategoriesController@categorygroupassign');
					Route::post('/selfassign', 'CategoriesController@selfassign')->name('admin.selfassign');
					Route::post('/assignedcategory/createagent', 'CategoriesController@agentshowcreate');
					Route::get('/categories/{id}', 'CategoriesController@destroy')->name('delete');
					Route::get('/category/deleteall', 'CategoriesController@categorymassdestroy')->name('category.deleteall');

					Route::post('/adminemailchange','AdminprofileController@adminemailchange')->name('adminemailchange');
					Route::post('/userpasswordverify','AdminprofileController@userpasswordverify')->name('userpasswordverify');
                    Route::post('/adminnewemailstore','AdminprofileController@adminnewemailstore')->name('admin.newemailstore');
                    Route::post('/adminemailupdateotpverify/{oldemail}','AdminprofileController@adminemailupdateotpverify')->name('adminemail.updateotpverify');
                    Route::get('/adminemailupdate/{email}','AdminprofileController@adminemailupdate')->name('adminemailupdate');

					Route::get('/domaintransfer', 'AdminprofileController@domaintransfer');
					Route::get('/profile', 'AdminprofileController@index');
					Route::get('/profile/edit', 'AdminprofileController@profileedit');
					Route::post('/profile', 'AdminprofileController@profilesetup');
					Route::post('/image/remove/{id}', 'AdminprofileController@imageremove');
					Route::get('/article', 'ArticlesController@index');
					Route::post('/article', 'ArticlesController@article')->name('admin.article.index');
					Route::get('/article/create', 'ArticlesController@create');
					Route::post('/article/create', 'ArticlesController@store');
					Route::post('/article/imageupload', 'ArticlesController@storeMedia')->name('admin.imageupload');
					Route::post('/article/featureimageupload', 'ArticlesController@featureimagestoreMedia')->name('admin.featureimageupload');
					Route::get('/article/{id}/edit', 'ArticlesController@show')->name('admin.article');
					Route::post('/article/{id}/edit', 'ArticlesController@update');
					Route::get('/article/{id}', 'ArticlesController@destroy');
					Route::get('/massarticle/delete', 'ArticlesController@articlemassdestroy');
					Route::post('/article/status{id}', 'ArticlesController@status');
					Route::post('/article/privatestatus/{id}', 'ArticlesController@privatestatus');
					Route::post('/article/featureimage/{id}', 'ArticlesController@featureimage');
					Route::get('/employee', 'AgentCreateController@index')->name('employee');
					Route::get('/employee/create', 'AgentCreateController@create');
					Route::post('/agent', 'AgentCreateController@store');
					Route::get('/agentprofile/{id}', 'AgentCreateController@show');
					Route::post('/agentprofile/{id}', 'AgentCreateController@update');
					Route::get('/userimport', 'AgentCreateController@userimportindex')->name('user.userimport');
					Route::post('/agent/{id}', 'AgentCreateController@destroy');
					Route::post('/massuser/deleteall', 'AgentCreateController@usermassdestroy');
					Route::post('/agent/status/{id}', 'AgentCreateController@status');
                    Route::post('/employeesreplyingremove', 'AdminTicketController@employeesreplyingremove')->name('employeesreplyingremove');
                    Route::post('/employeesreplyingstore', 'AdminTicketController@employeesreplyingstore')->name('employeesreplyingstore');
                    Route::get('/getemployeesreplying/{id}', 'AdminTicketController@getemployeesreplying')->name('getemployeesreplying');
					Route::get('/ticket/{ticket_id}', 'AdminTicketController@destroy');
					Route::post('/priority/change/', 'AdminTicketController@changepriority');
					Route::post('/ticket/delete/tickets', 'AdminTicketController@ticketmassdestroy')->name('admin.ticket.sremove');
					Route::get('/ticket-view/{ticket_id}', 'AdminTicketController@show')->name('admin.ticketshow');
					Route::get('/ticket-view/violationdetails/{id}', 'AdminTicketController@violationdetails')->name('violationdetails.customer');
					Route::post('/ticket-comment/{ticket_id}', 'AdminTicketController@commentshow')->name('admin.ticketcomment');
					Route::post('/ticket/{ticket_id}', 'CommentsController@postComment');
					Route::get('/latestcommentimgdelete/{id}', 'CommentsController@latestcommentimgdelete');
					Route::post('/ticketdraft', 'CommentsController@ticketdraft');//draft
                    Route::get('/ticketdraftimage/{id}', 'CommentsController@ticketdraftimage');//draft
                    Route::get('/draftdelete/{id}', 'CommentsController@draftdelete');//draft
					Route::post('/ticket/imageupload/{ticket_id}', 'CommentsController@storeMedia');
					Route::post('/closed/{ticket_id}', 'AdminTicketController@close');
					Route::get('/delete-ticket/{id}', 'AdminTicketController@destroy');
					Route::get('/purchasedetailsverify', 'AdminTicketController@purchasedetailsverify')->name('purchasedetailsverify');
					Route::get('/wrongcustomer', 'AdminTicketController@wrongcustomer')->name('wrongcustomer');
					Route::post('/adminticketclosing/{id}', 'AdminTicketController@adminticketclosing')->name('admin.ticketclosing');
					Route::post('/addimportantticket/{id}', 'AdminTicketController@addimportantticket')->name('admin.addimportantticket');
					Route::post('/ticket/editcomment/{id}', 'CommentsController@updateedit');
                    Route::get('/ticket/deletecomment/{id}', 'CommentsController@deletecomment');
					Route::delete('/image/delete/{id}', 'CommentsController@imagedestroy');
					Route::post('/ticket/reopen/{id}', 'CommentsController@reopenticket');
					Route::get('/roleaccess', 'PermissionstatusController@index')->name('ajaxproducts.index');
					Route::get('/roleaccess/{id}/edit', 'PermissionstatusController@edit');
					Route::post('/roleaccess/{id}/edit', 'PermissionstatusController@update');
					Route::get('/faq', 'FAQController@index')->name('faq.index');
					Route::post('/faq', 'FAQController@faq');
					Route::get('/faq/create','FAQController@faqcreate')->name('faq.create');
                    Route::post('/faq/create', 'FAQController@store')->name('faq.store');
                    Route::get('/faq/{id}', 'FAQController@show')->name('faq.edit');
					Route::delete('/faq/delete/{id}', 'FAQController@destroy');
					Route::post('/faq/deleteall', 'FAQController@allfaqdelete')->name('faq.deleteall');
					Route::post('/faq/status{id}', 'FAQController@status');
					Route::post('/faq/privatestatus/{id}', 'FAQController@privatestatus');
					Route::get('/testimonial', 'TestimonialController@index');
					Route::post('/testimonial/create', 'TestimonialController@store');
					Route::post('/testimonial', 'TestimonialController@testi');
					Route::get('/testimonial/{id}', 'TestimonialController@show');
					Route::get('/testimonial/delete/{id}', 'TestimonialController@destroy');
					Route::post('/testimonial/deleteall', 'TestimonialController@alltestimonialdelete')->name('testimonial.deleteall');
					Route::post('/testimonial/imagedelete/{id}', 'TestimonialController@imagedelete')->name('testimonial.imagedelete');
					Route::get('/call-to-action', 'CalltoactionController@index');
					Route::post('/call-to-action', 'CalltoactionController@store');
					Route::post('/call-to-action/imagedestroy/{id}', 'CalltoactionController@imagedestroy');
					Route::get('/feature-box', 'FeatureBoxController@index');
					Route::post('/feature-box/feature', 'FeatureBoxController@feature');
					Route::post('/feature-box/create', 'FeatureBoxController@store');
					Route::get('/feature-box/{id}', 'FeatureBoxController@show');
					Route::get('/feature-box/delete/{id}', 'FeatureBoxController@destroy');
					Route::post('/feature-box/image/delete/{id}', 'FeatureBoxController@imagedelete');
					Route::post('/featurebox/deleteall', 'FeatureBoxController@allfeaturedelete')->name('featurebox.deleteall');
					Route::get('/general', 'ApptitleController@index');
					Route::get('/bannersetting', 'ApptitleController@bannerpage');
					Route::post('/general', 'ApptitleController@store');
					Route::post('/bannerstore', 'ApptitleController@bannerstore');
					Route::post('/footer', 'ApptitleController@footerstore');
					Route::post('/logodelete', 'ApptitleController@logodelete')->name('admin.logodelete');
					Route::post('/bussinesslogodelete', 'AdminSettingController@bussinesslogodelete')->name('admin.bussinesslogodelete');
					Route::get('/seo', 'SeopageController@index');
					Route::post('/seo/create', 'SeopageController@store');
					Route::post('/assigned/create', 'AdminAssignedticketsController@create');
					Route::get('/assigned/{id}', 'AdminAssignedticketsController@show');
					Route::get('/assigned/update/{id}', 'AdminAssignedticketsController@update');
					Route::get('/announcement', 'AdminAnnouncementController@index');
                    Route::get('/announcement/create', 'AdminAnnouncementController@create')->name('announcement.create');
					Route::post('/announcement/create', 'AdminAnnouncementController@store');
					Route::get('/announcement/{id}', 'AdminAnnouncementController@show');
					Route::get('/announcement/delete/{id}', 'AdminAnnouncementController@destroy');
					Route::post('/massannouncedelete', 'AdminAnnouncementController@allannouncementdelete')->name('announcementall.delete');
					Route::post('/announcement/status{id}', 'AdminAnnouncementController@status');
                    Route::post('/announcementsetting', 'AdminSettingController@announcementsetting')->name('settings.announcement');
                    Route::get('/announcement/edit/{id}', 'AdminAnnouncementController@edit')->name('announcement.edit');
                    Route::post('/announcement/update', 'AdminAnnouncementController@update')->name('announcement.update');
					Route::get('/emailsetting', 'AdminSettingController@email')->name('email.setting.alert');
					Route::post('/emailsetting', 'AdminSettingController@emailstore')->name('settings.email.store');
					Route::get('/emailtemplates', 'AdminSettingController@emailtemplates');
					Route::get('/emailtemplates/{id}', 'AdminSettingController@emailtemplatesEdit')->name('settings.email.edit');
					Route::post('/emailtemplates/{id}', 'AdminSettingController@emailtemplatesUpdate')->name('settings.email.update');
					Route::get('/captcha', 'AdminSettingController@captcha')->name('settings.captcha');
					Route::post('/captcha', 'AdminSettingController@captchastore')->name('settings.captcha.store');
					Route::post('/captchatype', 'AdminSettingController@captchatypestore');
					Route::post('/captchacontact', 'AdminSettingController@captchacontact')->name('settings.captchacontact.store');
					Route::post('/captcharegister', 'AdminSettingController@captcharegister')->name('settings.captcharegister.store');
					Route::post('/captchalogin', 'AdminSettingController@captchalogin')->name('settings.captchalogin.store');
					Route::post('/captchaadminlogin', 'AdminSettingController@captchaadminlogin')->name('settings.captchaadminlogin.store');
					Route::post('/captchaguest', 'AdminSettingController@captchaguest')->name('settings.captchaguest.store');
					Route::get('/sociallogin', 'AdminSettingController@sociallogin')->name('settings.sociallogin');
					Route::post('/sociallogin', 'AdminSettingController@socialloginupdate')->name('settings.sociallogin.update');
					Route::get('/ticketsetting', 'AdminSettingController@ticketsetting')->name('settings.ticket');
					Route::post('/ticketsetting', 'AdminSettingController@ticketsettingstore')->name('settings.ticket.store');
					Route::get('/languagesetting', 'AdminSettingController@languagesetting');
					Route::post('/languagesetting', 'AdminSettingController@languagesettingstore')->name('settings.lang.store');
					Route::post('/datetimeformat', 'AdminSettingController@datetimeformatstore')->name('settings.timedateformat.store');
					Route::post('/startweek', 'AdminSettingController@startweekstore')->name('settings.startweek.store');
					Route::post('/timezoneupdate', 'AdminSettingController@timezoneupdate')->name('settings.timezone.store');
                    Route::post('/contactemail', 'AdminSettingController@contactemail')->name('settings.contactemail.store');
					Route::get('/general/dark', 'ApptitleController@check');
					Route::get('/customer', 'AdminprofileController@customers')->name('admin.customer');
                    Route::get('/customer/resendverification/{email}', 'AdminprofileController@resendverification');
					Route::post('/knowledge', 'AdminSettingController@knowledge')->name('settings.knowledge.store');
					Route::post('/profileuser', 'AdminSettingController@profileuser')->name('settings.profileuser.store');
					Route::post('/profileagent', 'AdminSettingController@profileagent')->name('settings.profileagent.store');
					Route::post('/imapstore', 'AdminSettingController@imapstore')->name('settings.imapstore');
					Route::post('/imaps/statuschange/{id}', 'AdminSettingController@statuschange')->name('imap.statuschange');
					Route::get('/imaps/{id}', 'AdminSettingController@edit')->name('imap.edit');
					Route::get('/imaps/delete/{id}', 'AdminSettingController@delete')->name('imap.delete');
					Route::post('/imaps/alldelete', 'AdminSettingController@massdelete')->name('imaps.alldelete');
					Route::post('/smtp/check', 'AdminSettingController@smtpcheck')->name('smtp.check');
                    Route::get('/botresponsettingcreate',[AdminSettingController::class,'botresponsettingcreate'])->name('admin.botresponsetting');
                    Route::post('/botsettingstore',[AdminSettingController::class,'botsettingstore'])->name('admin.botsettingstore');
                    Route::post('/botimagedelete',[AdminSettingController::class,'botimagedelete'])->name('admin.botimagedelete');

                    Route::get('/twiliosetting',[AdminSettingController::class,'twiliosetting'])->name('admin.twiliosetting');
                    Route::post('/twiliosettingstore',[AdminSettingController::class,'twiliosettingstore'])->name('admin.twiliosettingstore');

					Route::get('/smstemplateedit/{id}', 'AdminSettingController@smstemplateedit')->name('admin.smstemplate.edit');
					Route::post('/smstemplateUpdate/{id}', 'AdminSettingController@smstemplateUpdate')->name('admin.smstemplate.update');

					Route::get('/customer/create', 'AdminprofileController@customerscreate');
					Route::post('/customer/create', 'AdminprofileController@customersstore');
					Route::get('/customer/{id}', 'AdminprofileController@customersshow');
					Route::get('/usersettings', 'AdminprofileController@usersetting');
					Route::post('/customer/{id}', 'AdminprofileController@customersupdate');
					Route::post('/voilating', 'AdminprofileController@voilating')->name('voilating.customer');
					Route::delete('/voilationnotedelete/{id}', 'AdminprofileController@voilationnotedelete')->name('customer.voilationnotedelete');
					Route::get('/voilationedit/{id}', 'AdminprofileController@voilationedit')->name('customer.voilationedit');
					Route::get('/unvoilating/{id}', 'AdminprofileController@unvoilating')->name('unvoilating.customer');
					Route::get('/customer/delete/{id}', 'AdminprofileController@customersdelete');
					Route::get('/masscustomer/delete', 'AdminprofileController@customermassdestroy');
					Route::get('/general/register', 'AdminSettingController@registerpopup');
					Route::post('/general/enableemailtoticket', 'AdminSettingController@enableemailtoticket')->name('admin.enableemailtoticket');
					Route::get('/googleanalytics', 'AdminSettingController@googleanalytics');
					Route::post('/googleanalytics', 'AdminSettingController@googleanalyticsStore')->name('settings.googleanalytics');
					Route::post('/filesetting', 'AdminSettingController@filesettingstore')->name('settings.file.store');
					Route::post('/sendtestmail', 'AdminSettingController@sendTestMail')->name('settings.email.sendtestmail');
					Route::post('/colorsetting', 'AdminSettingController@frontendStore')->name('settings.color.colorsetting');
					Route::post('/urlset', 'AdminSettingController@seturl')->name('settings.url.urlset');
					Route::get('/envatosetting', 'AdminSettingController@envatosetting')->name('settings.envatosetting');
                    Route::post('/expiredsupport', 'AdminSettingController@expiredsupport')->name('settings.expiredsupport');
					Route::get('/customcssjssetting', 'CustomcssjsController@index');
					Route::post('/customcssjssetting', 'CustomcssjsController@customcssjs')->name('settings.custom.cssjs');
					Route::get('/customchatsetting', 'CustomcssjsController@customchat');
					Route::post('/customchatsetting', 'CustomcssjsController@customchats')->name('settings.custom.chat');
					Route::get('/error404', 'CustomerrorpagesController@index');
					Route::post('/error404', 'CustomerrorpagesController@store');
					Route::get('/maintenancepage', 'CustomerrorpagesController@maintenancepage');
					Route::post('/maintenancepage', 'CustomerrorpagesController@maintenancepagestore');
					Route::get('/createticket', 'AdminTicketController@createticket');
					Route::post('/createticket', 'AdminTicketController@gueststore');
					Route::post('/imageupload', 'AdminTicketController@guestmedia')->name('imageuploadadmin');
					Route::get('/alltickets', 'AdminTicketController@alltickets')->name('admin.alltickets');
					Route::get('role','RoleCreateController@index');
					Route::get('role/create','RoleCreateController@create');
					Route::post('role/create','RoleCreateController@store');
					Route::get('role/edit/{id}','RoleCreateController@edit');
					Route::post('role/edit/{id}','RoleCreateController@update');
					Route::get('/pages', 'GeneralPageController@index')->name('pages.index');
                    Route::get('/createpage', 'GeneralPageController@createpage')->name('pages.createpage');
                    Route::post('/pages/create', 'GeneralPageController@store')->name('pages.storepage');
                    Route::get('/pages/{id}', 'GeneralPageController@show')->name('pages.show');
                    Route::post('/pagesdelete/{id}', 'GeneralPageController@destroy');
                    Route::post('/pagesdeleteall', 'GeneralPageController@destroyall')->name('deleteall');

					Route::group(['prefix' => 'groups'], function () {

						Route::get('/','GroupCreateController@index');
						Route::get('create','GroupCreateController@create')->name('groups.create');
						Route::post('store','GroupCreateController@store');
						Route::get('view/{id}','GroupCreateController@show');
						Route::post('update/{id}','GroupCreateController@update');
						Route::post('delete/{id}','GroupCreateController@destroy');
						Route::post('deleteall','GroupCreateController@destroyall')->name('groups.deleteall');

					});
					Route::post('/note/create', 'AdminTicketController@note');
					Route::get('/products/{ticket_id}', 'AdminTicketController@noteshow');
					Route::delete('/ticketnote/delete/{id}', 'AdminTicketController@notedestroy');
					Route::post('userimport', 'AgentCreateController@usercsv')->name('customer.ucsvimport');
					Route::post('projectimport', 'ProjectsController@projetcsv')->name('project.pcsvimport');
					Route::get('projectimport', 'ProjectsController@projetimport')->name('projects.pcsvimports');
					Route::get('maintenancemode', 'MaintanancemodeController@index');
					Route::post('maintenancemode', 'MaintanancemodeController@store')->name('maintanance');
					Route::get('/projects', 'ProjectsController@index')->name('projects');
					Route::get('/notifications', 'ProjectsController@notificationpage')->name('notificationpage');
					Route::post('/projects/create', 'ProjectsController@store')->name('projects.create');
					Route::get('/projects/{id}', 'ProjectsController@show')->name('projects.view');
					Route::get('/projects/delete/{id}', 'ProjectsController@destroy');
					Route::get('massproject/delete', 'ProjectsController@projectmassdestroy');
					Route::get('/projectsassigned', 'ProjectsController@projectlist');
					Route::post('/projectsassigned', 'ProjectsController@projectassignee');
					Route::post('subcat','AdminTicketController@sublist')->name('admin.subcat');
					Route::post('refresh/{id}', 'AdminDashboardController@autorefresh');
					Route::get('reports', 'AdminReportController@index');
					Route::get('customer/adminlogin/{id}', 'AdminprofileController@adminLogin');
					Route::get('ticketreports', 'AdminReportController@ticketreports')->name('admin.ticketreports');

					Route::group(['prefix' => 'customnotification'], function(){

						Route::get('/', 'MailboxController@index')->name('mail.index');
						Route::get('/customercompose', 'MailboxController@customercompose')->name('mail.customer');
						Route::post('/customercompose', 'MailboxController@customercomposesend')->name('mail.customersend');
						Route::get('/employeecompose', 'MailboxController@employeecompose')->name('mail.employee');
						Route::post('/employeecompose', 'MailboxController@employeecomposesend')->name('mail.employeesend');
						Route::get('/sentmail', 'MailboxController@mailsent')->name('mail.sendmail');
						Route::get('/{id}', 'MailboxController@show')->name('mail.show');
						Route::delete('delete/{id}', 'MailboxController@destroy')->name('mail.delete');
						Route::post('/massdelete', 'MailboxController@allnotifydelete')->name('notifyall.delete');

					});

					Route::get('securitysetting', 'SecuritySettingController@index');
					Route::post('securitysetting', 'SecuritySettingController@store')->name('settings.security.country');
					Route::post('adminsecuritysetting/', 'SecuritySettingController@adminstore')->name('settings.security.admin.country');
					Route::post('securitysetting/ip', 'SecuritySettingController@dosstore')->name('settings.security.ip');
					Route::get('ipblocklist', 'IpblockController@index')->name('ipblocklist');
					Route::get('ipblocklist/{id}', 'IpblockController@show')->name('ipblocklist.id');
					Route::post('ipblocklist/create', 'IpblockController@store')->name('ipblocklist.store');
					Route::delete('ipblocklist/delete/{id}', 'IpblockController@destroy')->name('ipblocklist.destroy');
					Route::post('ipblocklist/reset/{id}', 'IpblockController@resetipblock')->name('ipblocklist.reset');
					Route::post('/ipblocklist/deleteall', 'IpblockController@allipblocklistdelete')->name('ipblocklist.deleteall');
					Route::get('language/{locale}', 'SecuritySettingController@setLanguage')->name('admin.front.set_language');

					Route::get('bussinesshour', 'BussinesshourController@index')->name('admin.bussinesshour.index');
					Route::post('bussinesshour', 'BussinesshourController@store')->name('admin.bussinesshour.store');
					Route::post('/bussinesshour/store', 'AdminSettingController@bussinesshourtitle')->name('admin.bussinesshour.bussinesshourtitle');
					Route::post('bussinesshour/fullhours', 'BussinesshourController@fullhours')->name('admin.fullhours.store');

					Route::post('notify/delete','AdminDashboardController@notifydelete')->name('admin.notifydelete');
					Route::post('/general/logindisable', 'AdminSettingController@logindisable');
					Route::post('employeepasswordreset', 'AgentCreateController@employeepasswordreset')->name('admin.employeepasswordreset');

					Route::get('/faq-category', 'FaqCategoryController@index')->name('faqsub.index');
					Route::post('/faqcategory/store', 'FaqCategoryController@storeupdate')->name('faqsub.storeupdate');
					Route::post('/faqcategory/delete/{id}', 'FaqCategoryController@destroy')->name('faqsub.delete');
					Route::post('/faqcategory/deleteall', 'FaqCategoryController@allfaqcategorydelete')->name('faqcategory.deleteall');
					Route::post('/faqcategory/status/{id}', 'FaqCategoryController@status')->name('faqcategory.status');

					Route::get('reports/employeedetails/{id}', 'AdminReportController@employeedetails')->name('admin.reports.employeedetails');
					Route::post('reports/ratingticket/delete/{id}', 'AdminReportController@ratingticketdelete')->name('admin.reports.ratingticketdelete');

					Route::group(['prefix' => 'selfassigned'], function(){

						Route::get('/', 'AdminTicketViewController@selfassignticketview')->name('admin.selfassignticketview');
					});

					Route::group(['prefix' => 'myassignedtickets'], function()
					{
						Route::get('/', 'AdminDashboardController@myassignedTickets')->name('admin.myassignedticket');

					});


					Route::get('/myclosedtickets', 'AdminTicketViewController@myclosedtickets')->name('admin.myclosedtickets');
					Route::get('/tickettrashed', 'AdminTicketViewController@tickettrashed')->name('admin.tickettrashed');
					Route::post('/tickettrashedrestore/{id}', 'AdminTicketViewController@tickettrashedrestore')->name('admin.tickettrashedrestore');
					Route::post('/tickettrasheddestroy/{id}', 'AdminTicketViewController@tickettrasheddestroy')->name('admin.tickettrasheddestroy');
					Route::get('/tickettrashedview/{id}', 'AdminTicketViewController@tickettrashedview')->name('admin.tickettrashedview');
					Route::post('/trashedticket/restore', 'AdminTicketViewController@alltrashedticketrestore')->name('admin.alltrashedticketrestore');
					Route::post('/trashedticket/delete', 'AdminTicketViewController@alltrashedticketdelete')->name('admin.alltrashedticketdelete');

					Route::post('/emaildomain', 'SecuritySettingController@emaildomainlist')->name('admin.emaildomainlist');


					Route::group(['prefix' => 'customfield', 'as'=> 'admin.customfield.'], function () {
						Route::get('/', 'CustomfieldController@index')->name('index');
						Route::post('/', 'CustomfieldController@storeupdate')->name('storeupdate');
						Route::get('/edit/{id}', 'CustomfieldController@edit')->name('edit');
						Route::post('/delete/{id}', 'CustomfieldController@destroy')->name('delete');
						Route::post('/deleteall', 'CustomfieldController@destroyall')->name('deleteall');
                        Route::post('/status/{id}', 'CustomfieldController@status')->name('status');

					});

					Route::get('/ticketarticle/{ticket}/{comment?}', 'ArticlesController@ticketarticle')->name('admin.article.ticket');


					Route::group(['prefix' => 'languages', 'as'=> 'admin.languages.'], function () {
						Route::get('/', 'LanguagesController@index')->name('index');
						Route::get('/create', 'LanguagesController@create')->name('create');
						Route::post('/store', 'LanguagesController@store')->name('store');
						Route::get('/translates/{code}', 'LanguagesController@translate')->name('translate');
						Route::post('{id}/default', 'LanguagesController@setDefault')->name('default');
						// not used
						Route::get('newkeyadding/{id}', 'LanguagesController@newkeyadding')->name('newkeyadding');
						Route::post('newkeyaddingstore/{id}', 'LanguagesController@newkeyaddingstore')->name('newkeyaddingstore');
						// not used
						Route::get('/edit/{id}', 'LanguagesController@edit')->name('edit');
						Route::post('/edit/{id}', 'LanguagesController@update')->name('edit.update');
						Route::post('/destroy/{id}', 'LanguagesController@destroy')->name('destroy');

						Route::post('{id}/update', 'LanguagesController@translateUpdate')->name('translates.update');
						Route::get('translate/{code}/{group}', 'LanguagesController@translate')->name('translate.group');
					});
					Route::post('clearcache','AdminDashboardController@clearcache')->name('admin.clearcache');

					Route::group(['prefix' => 'department', 'as' => 'department.'], function(){

						Route::get('/', 'DepartmentController@index')->name('index');
						Route::post('create', 'DepartmentController@create')->name('create');
						Route::get('edit/{id}', 'DepartmentController@edit')->name('edit');
						Route::delete('delete/{id}', 'DepartmentController@delete')->name('delete');
						Route::post('deleteall', 'DepartmentController@deleteall')->name('deleteall');
						Route::post('status/{id}', 'DepartmentController@status')->name('status');
					});
					Route::get('tickethistory/{id}', 'AdminTicketViewController@tickethistory')->name('admin.tickethistory');
					Route::get('customerprevioustickets/{cust_id}', 'AdminTicketViewController@customerprevioustickets')->name('admin.customer.tickethistory');

					Route::get('/customerimport', 'AdminprofileController@customerimportindex')->name('admin.customer.import');
					Route::post('/customerimport', 'AdminprofileController@customercsv')->name('admin.customer.csv');
					Route::post('/sessionLogout', 'AdminprofileController@sessionLogout')->name('admin.sessionLogout');


				});

			});

		});
		Route::group(['namespace' => 'Admin', 'prefix'	 => 'admin'], function () {



			Route::get('/assigned/{id}', 'AdminAssignedticketsController@show')->name('assigning.ticket');
			Route::get('/ticket-view/ticketassigneds/{id}', 'AdminAssignedticketsController@show');
			Route::get('dashboardtabledata','AdminDashboardController@dashboardtabledata')->name('admin.dashboardtabledata');
			Route::post('notifystatus','AdminDashboardController@notifystatus')->name('admin.notifystatus');
			Route::post('notifysearch','AdminDashboardController@notifysearch')->name('admin.notifysearch');

			Route::get('faqcategory/list','FaqCategoryController@faqcategorylist')->name('faqsub.faqcategorylist');
			Route::get('faqcategory/edit/{id}','FaqCategoryController@edit')->name('faqsub.edit');
			Route::post('markallnotify','AdminDashboardController@markallnotify')->name('admin.notify.markallread');
			Route::post('emailonoff','AdminprofileController@emailonoff')->name('admin.emailonoff');
			Route::post('customerprofiledelete', 'AdminSettingController@customerprofiledelete')->name('admin.customerprofiledelete');
			Route::post('customerautologout', 'AdminSettingController@customerautologout')->name('admin.customerautologout');
            Route::post('twofactauthsetting', 'AdminSettingController@twofactauthsetting')->name('admin.twofactauthsetting');
		});


		Route::middleware(['countrylistbub', 'datarecovery', 'throttle:refresh', 'ipblockunblock'])->group(function () {


			Route::group(['namespace' => 'User', 'prefix' => 'customer'], function(){



				Route::group(['namespace' => 'Auth'], function(){
                    Route::post('/google2faauthnew','LoginController@google2fapage')->name('google2faqr.login');
					Route::post('/google2faauthlogin','LoginController@google2faotpverifylogin')->name('google2falogin.otpverify');
					Route::get('google2faauth/{email}','LoginController@google2fa')->name('google2fa.login');
					Route::get('/resendotp','LoginController@resendotp')->name('resend');
					Route::get('/verifytwofactor/{email}','LoginController@verifytwofactor')->name('verify.twofactor');
					Route::get('/verifytwofactor','LoginController@otpverify')->name('twofactor');
					Route::get('/login', 'LoginController@showLoginForm')->middleware('guest:customer')->name('auth.login');
					Route::post('/login', 'LoginController@login')->middleware('guest:customer')->name('client.do_login');
					Route::post('/ajaxlogin', 'LoginController@ajaxlogin')->middleware('guest:customer')->name('client.do_ajaxlogin');

					Route::post('/logout', 'LoginController@logout')->middleware('auth:customer')->name('client.logout');
					Route::post('/clientsessionLogout', 'LoginController@sessionLogout')->name('client.sessionLogout');

                    Route::get('/emailverification', 'LoginController@emailverification')->middleware('guest:customer')->name('user.emailverification');
                    Route::get('/emailverificationstore/{email}', 'LoginController@emailverificationstore')->middleware('guest:customer')->name('user.emailverificationstore');

					// Social Auth
					Route::get('/login/{social}', 'LoginController@socialLogin')->name('social.login');
					Route::get('/login/{social}/callback','LoginController@handleProviderCallback')->name('social.login-callback');

					Route::get('/register', 'RegisterController@showRegistrationForm')->middleware('guest:customer')->name('register');
					Route::post('/register', 'RegisterController@register')->name('auth.register')->middleware('guest:customer');
					Route::post('/register1', 'RegisterController@registers')->name('register1')->middleware('guest:customer');
					Route::get('/forgotpassword', 'Passwords\ForgotpasswordController@forgot')->middleware('guest:customer');
					Route::post('/forgotpassword', 'Passwords\ForgotpasswordController@Email')->middleware('guest:customer');
					Route::post('/forgotpasswordajax', 'Passwords\ForgotpasswordController@Emailajax')->name('ajax.forgot')->middleware('guest:customer');
					Route::post('/change-password', 'ChangepasswordController@changepassword')->name('change.password');
					Route::get('/{token}/reset-password', 'Passwords\ResetpasswordController@resetpassword')->middleware('guest:customer')->name('reset.password');
					Route::post('/reset-password',  'Passwords\ResetpasswordController@updatePassword')->middleware('guest:customer');
					Route::get('/user/verify/{token}','RegisterController@verifyUser')->middleware('guest:customer')->name('verify.email');
				});

				Route::middleware('auth:customer','customer.auth')->group(function () {

				    Route::get('/google2faotp','Profile\UserprofileController@google2faotpenter')->name('google2faotpenter');
					Route::post('/google2faotpverify','Profile\UserprofileController@google2faotpverify')->name('google2fa.otpverify');
					Route::post('/emailtwofactorcustomerset','Profile\UserprofileController@emailtwofactor')->name('emailtwofactor.setting');
					Route::post('/enabletwo-factor','Profile\UserprofileController@customerenabletwofactor')->name('customer.enabletwofactor');

					Route::get('notification/{id}', 'DashboardController@Notificationview')->name('customer.notiication.view');
					Route::get('/mark-as-read', 'DashboardController@markNotification')->name('customer.markNotification');
					Route::get('/', 'DashboardController@userTickets')->name('client.dashboard');

                    Route::post('/customeremailchange','Profile\UserprofileController@customeremailchange')->name('customeremailchange');
                    Route::post('/customernewemailstore','Profile\UserprofileController@customernewemailstore')->name('customer.newemailstore');
                    Route::post('/emailchangeotpverify/{oldemail}','Profile\UserprofileController@emailchangeotpverify')->name('emailchange.otpverify');
                    Route::get('/customeremailupdate/{email}','Profile\UserprofileController@customeremailupdate')->name('customeremailupdate');

					Route::get('/profile','Profile\UserprofileController@profile')->name('client.profile');
					Route::post('/profile','Profile\UserprofileController@profilesetup')->name('client.profilesetup');
					Route::post('/profile/sendotp','Profile\UserprofileController@sendOtp')->name('client.profilesendOtp');
					Route::post('/profile/verifyOTP','Profile\UserprofileController@verifyOTP')->name('client.profileverifyOTP');
					Route::post('/deleteaccount/{id}','Profile\UserprofileController@profiledelete')->name('client.profiledelete');
					Route::delete('/image/remove/{id}', 'Profile\UserprofileController@imageremove');
					Route::post('/custsettings', 'Profile\UserprofileController@custsetting');
					Route::post('/custtwiliosetting', 'Profile\UserprofileController@custtwiliosetting');
					Route::get('/ticket','Ticket\TicketController@create')->name('client.ticket');
					Route::post('/ticket','Ticket\TicketController@store')->name('client.ticketcreate');
					Route::post('/ticket/bot/editticketsubject',[App\Http\Controllers\User\Ticket\CommentsController::class, 'editticketsubject'])->name('client.ticketsubject');
					Route::post('/imageupload','Ticket\TicketController@storeMedia')->name('imageupload');
					Route::get('/ticket/view/{ticket_id}','Ticket\TicketController@show')->name('loadmore.load_data');
					Route::post('/ticket/{ticket_id}','Ticket\CommentsController@postComment')->name('client.comment');
					Route::post('/ticket/imageupload/{ticket_id}','Ticket\CommentsController@storeMedia')->name('client.ticket.image');
					Route::post('/ticket/editcomment/{id}','Ticket\CommentsController@updateedit')->name('client.comment.edit');
					Route::get('/activeticket','Ticket\TicketController@activeticket')->name('activeticket');
					Route::get('/closedticket','Ticket\TicketController@closedticket')->name('closedticket');
					Route::get('/onholdticket','Ticket\TicketController@onholdticket')->name('onholdticket');
					Route::post('/closed/{ticket_id}','Ticket\TicketController@close')->name('client.ticketclose');
					Route::delete('/image/delete/{id}','Ticket\CommentsController@imagedestroy')->name('client.imagedestroy');
					Route::post('subcat','Ticket\TicketController@sublist')->name('subcat');
					Route::get('/rating/{ticket_id}', 'Ticket\TicketController@rating')->name('rating')->middleware('disablepreventback');
					Route::get('/rating/star5/{id}', 'Ticket\TicketController@star5');
					Route::get('/rating/star4/{id}', 'Ticket\TicketController@star4');
					Route::get('/rating/star3/{id}', 'Ticket\TicketController@star3');
					Route::get('/rating/star2/{id}', 'Ticket\TicketController@star2');
					Route::get('/rating/star1/{id}', 'Ticket\TicketController@star1');
					Route::get('/generalsetting', 'GeneralSettingController@index')->name('client.general');
					Route::post('/general/notification', 'GeneralSettingController@NotifyOn')->name('client.generalsetting');
					Route::get('/notification', 'DashboardController@notify')->name('client.notification');

					Route::get('/markAsRead', function(){

						$notify = Auth::guard('customer')->user();
						$notify->unreadNotifications->markAsRead();

					})->name('cust.mark');

					Route::post('notify/delete','DashboardController@notifydelete')->name('customer.notifydelete');
				});

				Route::get('ticket/pdfmake/{id}','Ticket\TicketController@pdfmake')->name('user.pdfmake');

			});

			Route::get('/', [HomeController::class, 'index'])->name('home');
			Route::get('language/{locale}', [HomeController::class, 'setLanguage'])->name('front.set_language');
			Route::get('/captchareload', [HomeController::class, 'captchareload'])->name('captcha.reload');
			Route::get('/article/{id}', [HomeController::class, 'knowledge'])->name('article');
			Route::get('/likes/{id}',[HomeController::class, 'like']);
			Route::get('/dislikes/{id}',[HomeController::class, 'dislike']);
			Route::post('likedislike',[HomeController::class, 'likedislike'])->name('likedislike');
			Route::get('/faq', [HomeController::class, 'faqpage']);
			Route::get('/faq/{id}', [HomeController::class, 'faqcategorypage'])->name('faq.faqcategory');
			Route::get('/page/{pageslug}', [HomeController::class, 'frontshow']);

			Route::get('/knowledge', [ArticleCommentController::class, 'index'])->name('knowledge');
			Route::post('/comment{id}', [ArticleCommentController::class, 'store']);
			Route::post('/replies{id}', [ArticleReplyController::class, 'store']);

			Route::get('/contact-us', [ContactController::class, 'contact']);
			Route::post('/contact-us', [ContactController::class, 'saveContact']);

			Route::get('/category/{id}', [CategorypageController::class, 'index']);


			Route::prefix('guest')->group(function () {

				Route::get('openticket', [GuestticketController::class, 'index'])->name('guest.ticket');
				Route::post('openticket', [GuestticketController::class, 'gueststore']);
				Route::post('openticketnootp', [GuestticketController::class, 'gueststore1'])->name('guest.openticketnootp');
				Route::post('/ticket/bot/editticketsubject', [GuestticketController::class, 'editticketsubject'])->name('guest.editticketsubject');
				Route::post('storemedia', [GuestticketController::class, 'guestmedia'])->name('guest.imageupload');
				Route::get('ticketdetails/{id}', [GuestticketController::class, 'guestdetails'])->name('guest.gusetticket');
				Route::get('ticket/{ticket_id}', [GuestticketController::class, 'guestview'])->name('gusetticket');
				Route::post('closed/{ticket_id}',[GuestticketController::class, 'close'])->name('guesttickets.ticketclose');
				Route::post('ticket/{ticket_id}',[GuestticketController::class, 'postComment'])->name('guest.comment');
				Route::post('emailsvalidate', [GuestticketController::class, 'emailsvalidateguest'])->name('guest.emailsvalidate');
				Route::post('verifyotp', [GuestticketController::class, 'verifyotp'])->name('guest.verifyotp');
				Route::get('ticket-view/{id}', [GuestticketController::class, 'ticketview'])->name('guest.ticketview');
				Route::post('ticket-view', [GuestticketController::class, 'senddataverify'])->name('guest.senddataverify');
				Route::post('verifyguestotp', [GuestticketController::class, 'verifyguestotp'])->name('guest.verifyguestotp');
				Route::get('ticket-view/details/{ticket_id}', [GuestticketController::class, 'ticketdetailshow'])->name('guest.ticketdetailshow');


			});

			Route::post('envatoverify',[GuestticketController::class, 'envatoverify'])->name('guest.envatoverify');
			Route::delete('/image/delete/{id}', [GuestticketController::class, 'imagedestroy']);
			Route::get('/rating/{ticket_id}', [GuestticketController::class, 'rating'])->name('guest.rating')->middleware('disablepreventback');
			Route::get('/rating/star5/{id}', [GuestticketController::class, 'star5']);
			Route::get('/rating/star4/{id}', [GuestticketController::class, 'star4']);
			Route::get('/rating/star3/{id}', [GuestticketController::class, 'star3']);
			Route::get('/rating/star2/{id}', [GuestticketController::class, 'star2']);
			Route::get('/rating/star1/{id}', [GuestticketController::class, 'star1']);

			Route::post('/ticket/rating', [GuestticketController::class, 'ticketrating']);
			Route::post('/guestticket/editcomment/{id}', [CommentsController::class, 'updateedit']);
		});

		Route::post('customer/notifystatus',[DashboardController::class, 'notifystatus'])->name('customer.notifystatus');
		Route::post('customer/notifysearch',[DashboardController::class, 'notifysearch'])->name('customer.notifysearch');

		Route::post('/guest/emailsvalidate', [GuestticketController::class, 'emailsvalidateguest'])->name('guest.emailsvalidate');
		Route::post('/guest/verifyotp', [GuestticketController::class, 'verifyotp'])->name('guest.verifyotp');
		Route::post('subcategorylist',[GuestticketController::class, 'subcategorylist'])->name('guest.subcategorylist');
		Route::post('/search',[HomeController::class, 'searchlist']);
		Route::post('/suggestarticle',[HomeController::class, 'suggestarticle']);
		Route::get('ipblock', [App\Http\Controllers\CaptchaipblockController::class, 'index'])->name('ipblock');
		Route::post('ipblock/update', [App\Http\Controllers\CaptchaipblockController::class, 'update'])->name('ipblock.update');
		Route::get('/captchasreload', [App\Http\Controllers\CaptchaipblockController::class, 'captchasreload'])->name('captchas.reload');
		Route::get('/apifailed', [App\Http\Controllers\ApiController::class, 'index'])->name('apifail.index');

		Route::get('notificationsreading', [GuestticketController::class, 'notificationsreading'])->name('notificationsreading');
		Route::get('badgecount', [GuestticketController::class, 'badgecount'])->name('badgecount');
		Route::get('markasreadcount', [GuestticketController::class, 'markasreadcount'])->name('markasreadcount');
		Route::get('notificationalerts', [GuestticketController::class, 'notificationalerts'])->name('update.notificationalerts');
		Route::post('notificationalerts', [GuestticketController::class, 'notificationalertsread'])->name('update.notificationalertsread');
		Route::get('customer/cnotificationalerts', [GuestticketController::class, 'cnotificationalerts'])->name('customer.update.notificationalerts');
		Route::post('customer/cnotificationalerts', [GuestticketController::class, 'cnotificationalertsread'])->name('customer.update.notificationalertsread');
		Route::get('customer/cnotificationsreading', [GuestticketController::class, 'cnotificationsreading'])->name('customer.notificationsreading');
		Route::get('customer/cbadgecount', [GuestticketController::class, 'cbadgecount'])->name('customer.badgecount');
		Route::get('customer/cmarkasreadcount', [GuestticketController::class, 'cmarkasreadcount'])->name('customer.markasreadcount');
		Route::post('customer/markallnotify', [GuestticketController::class, 'markallnotify'])->name('customer.notify.markallread');



        Route::get('previous/notfound',[HomeController::class ,'sessionput'])->name('previous.notfound');
		Route::get('image/{id}/{imageurl}', 'ImageController@index')->name('imageurl');
		Route::get('imagedownload/{id}/{imageurl}', 'ImageController@imagedownload')->name('imagedownload');
		Route::get('emailtoticket/{id}/{imageurl}', 'ImageController@emailtoticketshow')->name('emailtoticketimageurl');
		Route::get('emailtoticketdownload/{id}/{imageurl}', 'ImageController@emailtoticketdownload')->name('emailtoticketdownload');
		Route::get('emtcimageurl/{id}/{imageurl}', 'ImageController@emtcimageurlshow')->name('emtcimageurl');
        Route::get('emtcimagedownload/{id}/{imageurl}', 'ImageController@emtcimagedownload')->name('emtcimagedownload');
		Route::get('guestimage/{id}/{imageurl}', 'ImageController@guestimage')->name('guest.imageurl');
		Route::get('/getProfile/{storage_disk}/{imagePath}',[ImageController::class,'getProfileUrl'])->name('getprofile.url');
		Route::get('/getimage/{storage_disk}/{imagePath}',[ImageController::class,'getImage'])->name('getImage.url');
        Route::post('/livechat/customerdata',[LiveChatController::class,'customerdata'])->name('admin.customerdata');
        Route::get('/livechat/singlecustdata/{id}',[LiveChatController::class,'singlecustdata'])->name('admin.singlecustdata');
        Route::post('/livechat/broadcast-message',[LiveChatController::class,'broadcastMessage'])->name('broadcasMessage');
        Route::get('/livechat/flow/{id}',[LiveChatController::class,'ChatFlowData'])->name('admin.ChatFlowData');
        Route::post('/livechat/live-chat-image-upload',[LiveChatController::class,'liveChatImageUpload'])->name('admin.liveChatImageUpload');
        Route::post('/livechat/live-chat-image-remove',[LiveChatController::class,'removeChatImage'])->name('admin.removeChatImage');
        Route::post('/livechat/livechat-download-file',[LiveChatController::class,'downloadFile'])->name('admin.downloadFile');
        Route::get('/livechat/run-surver', function(){ Artisan::call('websockets:serve', [
            '--port' => 8443,
        ]); })->name('websockets:serve');
	});

	Route::view('timeupdate', 'admin.superadmindashboard.timeupdate')->name('timeupdate');
    Route::get('authcheckdetails',[HomeController::class ,'authcheckdetails'])->name('admin.authcheckdetails');

});
