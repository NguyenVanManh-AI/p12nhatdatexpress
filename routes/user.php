<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\Auth\LoginController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\CustomerController;
use App\Http\Controllers\User\SupportController;
use App\Http\Controllers\User\ReferenceController;
use App\Http\Controllers\User\PromotionController;
use App\Http\Controllers\User\DepositController;
// use App\Http\Controllers\User\DownloadFile;
use App\Http\Controllers\User\LevelController;
use App\Http\Controllers\User\PackageController;
use App\Http\Controllers\User\MailController;
use App\Http\Controllers\User\ExpressController;
use App\Http\Controllers\User\ClassifiedController;
use App\Http\Controllers\User\Auth\SocialAuthController;
use App\Http\Controllers\User\Auth\RegisterController;
use App\Http\Controllers\User\AjaxController;
use App\Http\Controllers\User\ChatBoxController;
use App\Http\Controllers\User\ConversationsController;
use App\Http\Controllers\User\EventController;
use App\Http\Controllers\User\GuideController;
use App\Http\Controllers\User\Mail\CampaignController;
use App\Http\Controllers\User\MailBoxController;
use App\Http\Controllers\User\NotifyController;
use App\Http\Controllers\User\SocialController;

    Route::group(['middleware' => ['user.check.login.register']], function () {
        Route::post('post-login', [LoginController::class, 'post_login'])->name('user.post-login');
        Route::post('post-register', [RegisterController::class, 'post_register'])->name('user.post-register');
        Route::get('social-login/social/{provider}', [SocialAuthController::class, 'redirect'])->name('login-social');
        Route::get('social-login/callback/{provider}', [SocialAuthController::class, 'social_login'])->name('login-social.callback');
        Route::get('active-account/{verifyCode}', [RegisterController::class, 'get_active'])->name('user.active-account');
        Route::post('post-reset-password', [LoginController::class, 'post_reset_password'])->name('user.post-reset-password');
    });

    Route::group(['prefix' => 'thanh-vien', 'middleware' => ['user.check.auth', 'choose.user.type'], 'as' => 'user.'], function () {
        #index
        Route::get('',[UserController::class, 'index'])->name('index');
        #Thông tin cá nhân
        Route::get('thong-tin-tai-khoan',[UserController::class, 'personal_info'])->name('personal-info')->withoutMiddleware('choose.user.type');
        Route::post('mang-xa-hoi', [UserController::class, 'post_update_social_link'])->name('post-update-social-link')->withoutMiddleware('choose.user.type');
        Route::post('du-an-dang-trien-khai', [UserController::class, 'post_update_deploying_project'])->name('post-update-deploying-project')->withoutMiddleware('choose.user.type');
        Route::post('yeu-cau-du-an', [UserController::class, 'post_project_request'])->name('post-project-request')->withoutMiddleware('choose.user.type');
        Route::post('doi-mat-khau', [UserController::class, 'post_password_change'])->name('post-password-change')->withoutMiddleware('choose.user.type');
        Route::post('delete-account', [UserController::class, 'deleteAccount'])->name('deleteAccount')->withoutMiddleware('choose.user.type');
        Route::post('restore-account', [UserController::class, 'restoreAccount'])->name('restoreAccount')->withoutMiddleware('choose.user.type');
        Route::post('cap-nhat-thong-tin-ca-nhan', [UserController::class, 'post_update_personal_info'])->name('post-update-personal-info')->withoutMiddleware('choose.user.type');
        Route::post('cap-nhat-background', [UserController::class, 'update_background'])->name('update-background')->withoutMiddleware('choose.user.type');
        Route::post('cap-nhat-avatar', [UserController::class, 'update_avatar'])->name('update-avatar')->withoutMiddleware('choose.user.type');
        Route::get('dang-xuat', [UserController::class, 'logout'])->name('logout')->withoutMiddleware('choose.user.type');
        Route::post('choose-account-type', [UserController::class, 'choose_account_type'])->name('choose-account-type')->withoutMiddleware('choose.user.type');
        Route::post('nang-cap-tai-khoan', [UserController::class, 'upgrade_account'])->name('upgrade-account')->withoutMiddleware('choose.user.type');

        #Danh sách khách hàng
        Route::get('danh-sach-khach-hang',[CustomerController::class, 'index'])->name('customer');
        Route::post('them-khach-hang',[CustomerController::class, 'create_customer'])->name('create-customer');
        Route::get('xoa-khach-hang/{customer_id}',[CustomerController::class, 'delete_customer'])->name('delete-customer');
        Route::post('cap-nhat-khach-hang',[CustomerController::class, 'update_customer'])->name('update-customer');

        Route::group(['prefix' => 'customers', 'as' => 'customers.'], function () {
            Route::get('/{id}/update-note', [CustomerController::class, 'updateNote'])->name('update-note');
        });

        // event
        Route::group(['as' => 'events.'], function () {
            Route::post('/events', [EventController::class, 'store'])->name('store');
            Route::get('/danh-sach-su-kien', [EventController::class, 'index'])->name('index');
            Route::get('/danh-sach-su-kien-da-xoa', [EventController::class, 'trash'])->name('trash');
            Route::get('/them-su-kien', [EventController::class, 'create'])->name('create');
            Route::get('/cap-nhat-su-kien/{event}', [EventController::class, 'edit'])->name('edit');
            Route::post('/cap-nhat-su-kien/{event}', [EventController::class, 'update'])->name('update');
            Route::post('/events/delete-multiple', [EventController::class, 'deleteMultiple'])->name('delete-multiple');
            Route::post('/events/restore-multiple', [EventController::class, 'restoreMultiple'])->name('restore-multiple');
            Route::post('/events/{event}/highlight', [EventController::class, 'highlight'])->name('highlight');
        });

        // notifies
        Route::group(['prefix' => 'notifies', 'as' => 'notifies.'], function () {
            Route::post('/{notify}/read-notify', [NotifyController::class, 'read']);
        });

        #lien ket tai khoan
        Route::get('lien-ket-tai-khoan',[ReferenceController::class, 'index'])->name('reference');
        Route::get('xoa-ket-tai-khoan/{ref_id?}',[ReferenceController::class, 'remove_reference'])->name('remove-reference');
        Route::post('them-lien-ket-tai-khoan/{ref_id?}',[ReferenceController::class, 'add_reference'])->name('add-reference');
        Route::get('chap-nhan-ket-tai-khoan',[ReferenceController::class, 'accrept_reference'])->name('accept-reference');
        Route::post('chia-se-tin-vip',[ReferenceController::class, 'share_vip_amount'])->name('share-vip-amount');
        Route::post('chia-se-coin',[ReferenceController::class, 'share_coin_amount'])->name('share-coin-amount');

        #Khuyến mại
        Route::get('tin-khuyen-mai',[PromotionController::class, 'promotion_news'])->name('promotion-news');
        Route::get('danh-sach-khuyen-mai',[PromotionController::class, 'index'])->name('promotion');
        Route::get('nhan-khuyen-mai/{promotion_code}',[PromotionController::class, 'receipt_promotion'])->name('promotion-receipt');

        #Nạp tiền
        Route::get('danh-sach-nap-tien/{voucher_code?}',[DepositController::class, 'index'])->name('deposit');
        Route::post('nap-tien', [DepositController::class, 'post_deposit'])->name('post-deposit');
        Route::get('danh-sach-giao-dich',[DepositController::class, 'transaction'])->name('transaction');
        Route::get('danh-sach-hoa-don',[DepositController::class, 'invoice'])->name('invoice');
        Route::post('yeu-cau-hoa-don',[DepositController::class, 'post_request_invoice'])->name('request-invoice');
        // Route::get('tai-hoa-don/{invoice_id}',[DownloadFile::class, 'download'])->name('download');
        Route::get('tai-file/{file_url}',[DepositController::class, 'download'])->name('invoice-download');
        Route::get('ajax-valid-voucher', [AjaxController::class, 'getVoucherInfo']);

        #Quản lý cấp bậc
        Route::get('cap-bac', [LevelController::class, 'index'])->name('level');

        #Gói tin
        Route::get('package', [PackageController::class, 'index'])->name('package');
        Route::post('package', [PackageController::class, 'deposit_package'])->name('post-package');

    Route::group(['middleware' => 'user.mailCampaign'], function () {
        #Chiến dịch mail
        Route::get('cau-hinh-mail', [MailController::class, 'config_mail'])->name('config-mail');
        Route::post('cau-hinh-mail', [MailController::class, 'post_config_mail'])->name('post-config-mail');
        Route::get('cap-nhat-cau-hinh-mail/{id}', [MailController::class, 'edit_config_mail'])->name('edit-config-mail');
        Route::post('cap-nhat-cau-hinh-mail/{id}', [MailController::class, 'post_edit_config_mail'])->name('podt-edit-config-mail');
        Route::get('xoa-cau-hinh-mail/{id}', [MailController::class, 'delete_config_mail'])->name('delete-config-mail');

        Route::group(['prefix' => 'mail-configs', 'as' => 'mailConfigs.'], function () {
            Route::post('/test', [MailController::class, 'mailConfigTest'])->name('test');
        });

        Route::get('tao-mau-mail', [MailController::class, 'template_mail'])->name('template-mail');
        Route::post('tao-mau-mail', [MailController::class, 'add_template_mail'])->name('post-template-mail');
        Route::get('sua-mau-mail/{id}', [MailController::class, 'edit_template_mail'])->name('edit-template-mail');
        Route::post('sua-mau-mail/{id}', [MailController::class, 'post_edit_template_mail'])->name('post-edit-template-mail');
        Route::get('xoa-mau-mail/{id}', [MailController::class, 'delete_template_mail'])->name('delete-template-mail');

        // mail campaign
        Route::group(['as' => 'campaigns.'], function () {
            Route::get('danh-sach-chien-dich-mail', [CampaignController::class, 'index'])->name('index');
            Route::get('tao-chien-dich-mail', [CampaignController::class, 'create'])->name('create');
            Route::post('tao-chien-dich-mail', [CampaignController::class, 'store'])->name('store');
            Route::get('/chi-tiet-gui-mail/{id}', [CampaignController::class, 'viewDetails'])->name('view-details');
            Route::get('/sua-chien-dich-mail/{id}', [CampaignController::class, 'edit'])->name('edit');
            Route::post('/sua-chien-dich-mail/{id}', [CampaignController::class, 'update'])->name('update');
            Route::post('/campaigns/{id}/delete', [CampaignController::class, 'destroy'])->name('destroy');
        });

        // Route::get('danh-sach-chien-dich-mail', [MailController::class, 'list_campaign_mail'])->name('list-campaign-mail');
        // Route::get('sua-chien-dich-mail/{id}', [MailController::class, 'edit_campaign_mail'])->name('edit-campaign-mail');
        // Route::post('sua-chien-dich-mail', [MailController::class, 'post_edit_campaign_mail'])->name('post-edit-campaign-mail');
        // Route::get('xoa-chien-dich-mail/{id}', [MailController::class, 'delete_campaign_mail'])->name('delete-campaign-mail');
    });

        #Giới thiệu người dùng
        Route::get('gioi-thieu-nguoi-dung',[UserController::class, 'user_ref_list'])->name('user-ref-list');

        #Mailbox
        Route::get('hom-thu/{mail_status?}',[SupportController::class, 'mailbox'])->name('mailbox');
        Route::get('chi-tiet-thu/{mailbox_id}',[SupportController::class, 'mailbox_detail'])->name('mailbox-detail');
        Route::group(['prefix' => 'mails', 'as' => 'mails.'], function () {
            Route::post('/{mail}/pin', [MailBoxController::class, 'pin']);
        });

        // support
        Route::group(['prefix' => 'ho-tro-ky-thuat', 'as' => 'supports.'], function () {
            Route::get('/', [SupportController::class, 'index'])->name('index');
        });

        #Hỗ trợ kỹ thuật
        // Route::get('ho-tro-ky-thuat',[SupportController::class, 'support'])->name('support');
        Route::post('ho-tro-ky-thuat',[SupportController::class, 'post_support'])->name('post-support');
        // Route::get('ho-tro-ky-thuat/',[SupportController::class, 'support'])->name('support');
        Route::post('/ho-tro-ky-thuat/message', [SupportController::class, 'store_message'])->name('store-message');
        Route::post('/ho-tro-ky-thuat/rating/{chat_code}', [SupportController::class, 'rating_session'])->name('rating-session');
        Route::get('/ho-tro-ky-thuat/chat/{admin_id}/{chat_code}', [SupportController::class, 'detail_chat'])->name('detail-chat');
        Route::get('/ho-tro-ky-thuat/generate/{admin_id}', [SupportController::class, 'generate_chat_code'])->name('generate-chat');

        Route::group(['prefix' => 'conversations', 'as' => 'conversations.'], function() {
            Route::get('/', [ConversationsController::class, 'index']);
            Route::get('/get-unread-messages', [ConversationsController::class, 'getUnreadMessages']);
            Route::get('/close-conversation', [ConversationsController::class, 'closeConversation']);
            Route::post('/send-basic-chat', [ConversationsController::class, 'sendBasicChat'])->name('sendBasic-chat');
            Route::post('/new-conversation', [ConversationsController::class, 'newConversation']);
            Route::post('/send-conversation-message', [ConversationsController::class, 'sendConversationMessage']);
            Route::post('/open-conversation', [ConversationsController::class, 'openConversation']);
            Route::post('/messages/{message}/remove', [ConversationsController::class, 'removeMessage']);
            Route::get('/{token}', [ConversationsController::class, 'getConversation']);
            Route::get('/{token}/get-messages', [ConversationsController::class, 'getMessages']);
            Route::get('/{token}/get-last-message', [ConversationsController::class, 'getLastMessage']);
            Route::post('/{token}/send-message', [ConversationsController::class, 'sendMessage']);
            Route::post('/{token}/rating', [ConversationsController::class, 'rating']);
            // Route::post('/{token}/add-attach', [ConversationsController::class, 'addAttach']);
            Route::post('/{token}/read-conversation', [ConversationsController::class, 'readConversation']);
        });

        Route::group(['prefix' => 'chat-boxes', 'as' => 'chat-boxes.'], function() {
            Route::get('/get-unread-messages', [ChatBoxController::class, 'getUnreadMessages']);
        });

        #Quản cáo express
        Route::get('tao-quang-cao', [ExpressController::class, 'add_express'])->name('add-express');
        Route::post('quang-cao', [ExpressController::class, 'post_express'])->name('post-express');
        Route::get('quang-cao', [ExpressController::class, 'index'])->name('express');
        Route::get('ajax-time-express', [ExpressController::class, 'ajax_express_time']);

        #đăng tin
        Route::get('danh-sach-dang-tin', [ClassifiedController::class, 'index'])->name('list-classified');
        Route::post('/classified/delete-multiple', [ClassifiedController::class, 'deleteMultiple'])->name('classified.delete-multiple');
        Route::get('xoa-tin-dang/{classified_id}', [ClassifiedController::class, 'delete_classified'])->name('delete-classified');
        // Route::get('lam-moi-tin/{classified_id}', [ClassifiedController::class, 'renew_classified'])->name('renew-classified');
        // Route::get('nang-cap-tin/{classified_id}', [ClassifiedController::class, 'upgrade_classified'])->name('upgrade-classified');
        Route::post('/classified/{classified}/renew', [ClassifiedController::class, 'renew'])->name('classified.renew');
        Route::post('/classified/{classified}/upgrade', [ClassifiedController::class, 'upgrade'])->name('classified.upgrade');
        Route::get('dang-tin/{group}', [ClassifiedController::class, 'add_classified'])->name('add-classified');
        Route::post('dang-tin/{group}', [ClassifiedController::class, 'post_add_classified'])->name('post-add-classified');
        Route::get('chinh-sua-tin-dang/{id}', [ClassifiedController::class, 'edit_classified'])->name('edit-classified');
        Route::post('chinh-sua-tin-dang/{id}', [ClassifiedController::class, 'post_edit_classified'])->name('post-edit-classified');

        #Huong dan
        Route::get('huong-dan', [GuideController::class, 'index'])->name('guide');
        Route::get('chi-tiet-huong-dan/{guideUrl}', [GuideController::class, 'detail'])->name('guide-detail');

        #Quản lý thư viện
        Route::get('thu-vien', [UserController::class, 'gallery'])->name('gallery');

        #Cộng đồng
        Route::get('cong-dong', [SocialController::class, 'index'])->name('social');

        Route::group(['prefix' => 'conversations', 'as' => 'conversations.'], function() {
            Route::get('/', [ConversationsController::class, 'index']);
            Route::get('/get-unread-messages', [ConversationsController::class, 'getUnreadMessages']);
            Route::get('/close-conversation', [ConversationsController::class, 'closeConversation']);
            Route::post('/send-basic-chat', [ConversationsController::class, 'sendBasicChat'])->name('sendBasic-chat');
            Route::post('/new-conversation', [ConversationsController::class, 'newConversation']);
            Route::post('/send-conversation-message', [ConversationsController::class, 'sendConversationMessage']);
            Route::post('/open-conversation', [ConversationsController::class, 'openConversation']);
            Route::post('/messages/{message}/remove', [ConversationsController::class, 'removeMessage']);
            Route::get('/{token}', [ConversationsController::class, 'getConversation']);
            Route::get('/{token}/get-messages', [ConversationsController::class, 'getMessages']);
            Route::get('/{token}/get-last-message', [ConversationsController::class, 'getLastMessage']);
            Route::post('/{token}/send-message', [ConversationsController::class, 'sendMessage']);
            Route::post('/{token}/rating', [ConversationsController::class, 'rating']);
            // Route::post('/{token}/add-attach', [ConversationsController::class, 'addAttach']);
            Route::post('/{token}/read-conversation', [ConversationsController::class, 'readConversation']);
        });
    });

    Route::get('gioi-thieu/{user_code}', [UserController::class, 'user_ref_link'])->name('user-ref-link');
    Route::get('active-deposit/{verify_code}', [DepositController::class, 'get_active_deposit'])->name('active-deposit');
    Route::get('unsubscribe-campaign-mail/{token}', [CampaignController::class, 'unsubscribe'])->name('unsubscribe-campaign-mail');
