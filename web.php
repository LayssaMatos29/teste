<?php

use App\Http\Controllers\Cron\SubscriptionPagarmeController;
use App\Http\Controllers\Payments\PaymentTitheController;
use App\Http\Controllers\Payments\PaymentTitheRecurrence;
use App\Http\Controllers\Payments\PaymentCampaignController;
use App\Http\Controllers\Payments\PaymentCampaignProductController;
use App\Http\Controllers\Payments\PaymentOfferEventController;
use App\Http\Controllers\Payments\PaymentOfferIntentionController;
use App\Http\Controllers\Payments\PaymentOfferReservationController;
use App\Http\Controllers\Payments\PaymentOfferSpontaneousController;
use App\Http\Controllers\Payments\PaymentController;
use App\Http\Controllers\Payments\PaymentTypeController;
use App\Http\Controllers\Toin\InternalOperationController as ToinInternalOperationController;
use App\Http\Controllers\Toin\PaymentMethodsController as ToinPaymentMethodsController;
use App\Http\Controllers\Dashboardv2\Web\WhatsController;
use App\Http\Controllers\Dashboardv2\Web\ReportController;
use App\Http\Controllers\Payments\PaymentCampaignRecurrenceController;
use App\Http\Controllers\Web\MemberStatusController;
use App\Http\Controllers\Web\PastoralController;
use App\Http\Controllers\Web\TenantModuleController;
use App\Http\Controllers\Web\BankAccountController;
use App\Http\Controllers\Web\BankController;
use App\Http\Controllers\Web\CampaignController;
use App\Http\Controllers\Web\CampaignProductController;
use App\Http\Controllers\Web\CampaignPromoterController;
use App\Http\Controllers\Web\CashierBillController;
use App\Http\Controllers\Web\CashierClosingController;
use App\Http\Controllers\Web\CashierController;
use App\Http\Controllers\Web\EventTypeOperationController;
use App\Http\Controllers\Web\ChartAccountController;
use App\Http\Controllers\Web\CashierSupplyBleedController;
use App\Http\Controllers\Web\EventSubscriptionController;
use App\Http\Controllers\Web\CostRevenueCenterController;
use App\Http\Controllers\Web\FlyerController;
use App\Http\Controllers\Web\IntentionController;
use App\Http\Controllers\Web\IntentionEventTypeController;
use App\Http\Controllers\Web\InternalOperationController;
use App\Http\Controllers\Web\InventoryMovementsController;
use App\Http\Controllers\Web\NoticeController;
use App\Http\Controllers\Web\NoticeEventController;
use App\Http\Controllers\Web\PasswordResetController;
use App\Http\Controllers\Web\PaymentMethodsController;
use App\Http\Controllers\Web\PlanController;
use App\Http\Controllers\Web\PositionHeldCampaignController;
use App\Http\Controllers\Web\PositionHeldController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\ProductKitController;
use App\Http\Controllers\Web\ProductTypeController;
use App\Http\Controllers\Web\PushNotification;
use App\Http\Controllers\Web\ReservController;
use App\Http\Controllers\Web\EventController;
use App\Http\Controllers\Web\EventTypeController;
use App\Http\Controllers\Web\MemberController;
use App\Http\Controllers\Web\PaymentOptionsController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\RoleController;
use App\Http\Controllers\Web\SettingController;
use App\Http\Controllers\Web\SubscriptionController;
use App\Http\Controllers\Web\TestemonialsController;
use App\Http\Controllers\Web\TypeNoticeController;
use App\Http\Controllers\Web\VerifyEmailController;
use App\Http\Controllers\Web\AttributesController;
use App\Http\Controllers\Web\BillController;
use App\Http\Controllers\Web\CardsController;
use App\Http\Controllers\Web\GraphicsController;
use App\Http\Controllers\Web\ParameterController;
use App\Http\Controllers\Web\GroupController as WebGroupController;
use App\Http\Controllers\Web\ModuleController;
use App\Http\Controllers\Web\MyParishController;
use App\Http\Controllers\Web\OccupationController;
use App\Http\Controllers\Web\PagarmeController;
use App\Http\Controllers\Web\PermissionController;
use App\Http\Controllers\Web\PostbackController;
use App\Http\Controllers\Web\PrayerController;
use App\Http\Controllers\Web\PrayerGroupController;
use App\Http\Controllers\Web\QrcodeController;
use App\Http\Controllers\Web\RecurrenceController;
use App\Http\Controllers\Web\TenantController;
use App\Http\Controllers\Web\TitheInscriptionContactController;
use App\Http\Controllers\Web\TitheInscriptionController;
use App\Http\Controllers\Web\WebController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [WebController::class, 'welcome'])->name('welcome');
Route::get('/tree', [WebController::class, 'tree'])->name('tree');

Route::get('/dominun/deeplink', [WebController::class, 'deeplink'])->name('deep.link');

Route::post('/pagarme/notification', [PostbackController::class, 'postBack'])->name('pagarme.notification');

/* Route::get('teste', function () {
    return \App\Models\Transaction::find(25809)->data_pagarme->acquirer_response_message;
}); */

Auth::routes();

/** Verificação de email */
Route::get('/verify/email', [VerifyEmailController::class, 'verificationNotice'])->name('verification.notice');
Route::post('/email/verification-notification', [VerifyEmailController::class, 'verificationSend'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::get('/email/verify/already-success', [VerifyEmailController::class, 'emailAlreadySuccess'])->name('email.already.success');
Route::get('/email/verify/success', [VerifyEmailController::class, 'emailSuccess'])->name('email.success');

Route::group(['prefix' => 'payment', 'middleware' => 'api.jwt'], function () {
    Route::group(['prefix' => 'tithe'], function () {
        Route::get('/', [PaymentController::class, 'tithe'])->name('payment.tithe');
        Route::get('/payment-methods', [PaymentTypeController::class, 'tithe'])->name('payment.methods.tithe');
        Route::get('/observation', [PaymentTypeController::class, 'observation'])->name('payment.observation.tithe');
        Route::post('creditcard', [PaymentTitheController::class, 'creditCard'])->name('payment.creditcard.tithe');
        Route::post('boleto', [PaymentTitheController::class, 'boleto'])->name('payment.boleto.tithe');
        Route::post('pix', [PaymentTitheController::class, 'pix'])->name('payment.pix.tithe');
        Route::group(['prefix' => 'recurrence'], function () {
            Route::get('/', [PaymentTitheRecurrence::class, 'index'])->name('recurrence.tithe');
            Route::post('/creditcard', [PaymentTitheRecurrence::class, 'creditCard'])->name('recurrence.creditcard.tithe');
        });
    });

    Route::group(['prefix' => 'campaign'], function () {
        Route::get('/', [PaymentController::class, 'campaign'])->name('payment.campaign');
        Route::get('/payment-methods', [PaymentTypeController::class, 'campaign'])->name('payment.methods.campaign');
        Route::post('creditcard', [PaymentCampaignController::class, 'creditCard'])->name('payment.creditcard.campaign');
        Route::post('boleto', [PaymentCampaignController::class, 'boleto'])->name('payment.boleto.campaign');
        Route::post('pix', [PaymentCampaignController::class, 'pix'])->name('payment.pix.campaign');
        Route::group(['prefix' => 'recurrence'], function () {
            Route::get('/', [PaymentController::class, 'campaignRecurrence'])->name('recurrence.campaign');
            Route::post('/creditcard', [PaymentCampaignRecurrenceController::class, 'creditCard'])->name('recurrence.creditcard.campaign');
        });
    });

    Route::group(['prefix' => 'offer-reservation'], function () {
        Route::get('/', [PaymentController::class, 'offerReservation'])->name('payment.offer-reservation');
        Route::get('/payment-methods', [PaymentTypeController::class, 'offerReservation'])->name('payment.methods.offer.reservation');
        Route::post('creditcard', [PaymentOfferReservationController::class, 'creditCard'])->name('payment.creditcard.offer.reservation');
        Route::post('boleto', [PaymentOfferReservationController::class, 'boleto'])->name('payment.boleto.offer.reservation');
        Route::post('pix', [PaymentOfferReservationController::class, 'pix'])->name('payment.pix.offer.reservation');
    });

    Route::group(['prefix' => 'offer-intention'], function () {
        Route::get('/', [PaymentController::class, 'offerIntention'])->name('payment.offer-intention');
        Route::get('/payment-methods', [PaymentTypeController::class, 'offerIntention'])->name('payment.methods.offer.intention');
        Route::post('creditcard', [PaymentOfferIntentionController::class, 'creditCard'])->name('payment.creditcard.offer.intention');
        Route::post('boleto', [PaymentOfferIntentionController::class, 'boleto'])->name('payment.boleto.offer.intention');
        Route::post('pix', [PaymentOfferIntentionController::class, 'pix'])->name('payment.pix.offer.intention');
    });

    Route::group(['prefix' => 'offer-event'], function () {
        Route::get('/', [PaymentController::class, 'offerEvent'])->name('payment.offer-event');
        Route::get('/payment-methods', [PaymentTypeController::class, 'offerEvent'])->name('payment.methods.offer.event');
        Route::post('creditcard', [PaymentOfferEventController::class, 'creditCard'])->name('payment.creditcard.offer.event');
        Route::post('boleto', [PaymentOfferEventController::class, 'boleto'])->name('payment.boleto.offer.event');
        Route::post('pix', [PaymentOfferEventController::class, 'pix'])->name('payment.pix.offer.event');
    });

    Route::group(['prefix' => 'offer-spontaneous'], function () {
        Route::get('/', [PaymentController::class, 'offerSpontaneous'])->name('payment.offerSpontaneous');
        Route::get('/payment-methods', [PaymentTypeController::class, 'offerSpontaneous'])->name('payment.methods.offer.spontaneous');
        Route::post('creditcard', [PaymentOfferSpontaneousController::class, 'creditCard'])->name('payment.creditcard.offer.spontaneous');
        Route::post('boleto', [PaymentOfferSpontaneousController::class, 'boleto'])->name('payment.boleto.offer.spontaneous');
        Route::post('pix', [PaymentOfferSpontaneousController::class, 'pix'])->name('payment.pix.offer.spontaneous');
    });

    Route::group(['prefix' => 'campaign-product'], function () {
        Route::get('/', [PaymentController::class, 'productCampaign'])->name('payment.product.campaign');
        Route::get('/payment-methods', [PaymentTypeController::class, 'productCampaign'])->name('payment.methods.product.campaign');
        Route::post('creditcard', [PaymentCampaignProductController::class, 'creditCard'])->name('payment.creditcard.campaign.product');
        Route::post('boleto', [PaymentCampaignProductController::class, 'boleto'])->name('payment.boleto.campaign.product');
        Route::post('pix', [PaymentCampaignProductController::class, 'pix'])->name('payment.pix.campaign.product');
    });
});

Route::get('/auth/confirm/{token}', [PasswordResetController::class, 'confirm'])->name('confirm');

Route::prefix("dashboard")->group(function () {
    Route::group(['middleware' => ['auth', 'verified']], function () {
        Route::get('/', [WebController::class, 'index'])->name('home');

        /* Reports */
        Route::get('/relatorios', [ReportController::class, 'index'])->name('reports.index');

        /** Troca de tenant  */
        Route::post('/change-tenant', [WebController::class, 'changeTenant'])->name('change-tenant');

        Route::group(['middleware' => 'role:root'], function () {
            /** Settings */
            Route::resource('settings', SettingController::class);
            Route::post('/settings-delete-cover', [SettingController::class, 'destroyCover'])->name('settings.destroy.cover');

            /** Parametros */
            Route::resource('parameters', ParameterController::class);

            /** Permissions */
            Route::resource('permissions', PermissionController::class);

            /** tenants */
            Route::resource('tenants', TenantController::class);

            /* Modules */
            Route::resource('modules', ModuleController::class);


        });

        /** Perfil */
        Route::get('/perfil', [ProfileController::class, 'index'])->name('profile.index');
        Route::put('/perfil/atualizar', [ProfileController::class, 'update'])->name('profile.update');

        /** Endereço */
        Route::get('/perfil/endereco/{id}', [ProfileController::class, 'showAddress'])->name('profile.address');
        Route::get('/perfil/criar/endereco', [ProfileController::class, 'createAddress'])->name('profile.address.create');
        Route::post('/perfil/endereco/{id}', [ProfileController::class, 'updateAddress'])->name('profile.address.update');
        Route::post('/perfil/criar/endereco', [ProfileController::class, 'storeAddress'])->name('profile.address.store');
        Route::delete('/perfil/endereco/{id}', [ProfileController::class, 'deleteAddress'])->name('profile.address.delete');

        /** Create Member */
        Route::get('/members', [MemberController::class, 'index'])->name('member.index');
        Route::get('/members/{id}', [MemberController::class, 'show'])->name('member.show');
        Route::get('/create-member', [MemberController::class, 'create'])->name('member.create');
        Route::put('/create-member/{id}/edit', [MemberController::class, 'update'])->name('member.update');
        Route::post('/create-member', [MemberController::class, 'store'])->name('member.store');
        Route::get('/create-member/{id}/edit', [MemberController::class, 'edit'])->name('member.edit');
        Route::delete('/members/{id}/delete', [MemberController::class, 'destroy'])->name('member.destroy');

        Route::post('/forgot-password/{user}', [MemberController::class, 'forgotPassword'])->name('forgot.password');

        Route::get('/password/member/create', [MemberController::class, 'indexPassword'])->name('password.create.member');
        Route::post('/password/member/create/store', [MemberController::class, 'resetPost'])->name('password.store.member');

        /** Adicionando pastorais á um paroquiano */
        Route::post('/member-pastoral/{user}', [MemberController::class, 'groups'])->name('member.groups');

        /** Adicionando atributos á um paroquiano */
        Route::post('/member-attribute/{user}', [MemberController::class, 'attributes'])->name('member.attributes');

        /** Adicionando funções á um paroquiano */
        Route::post('/member-occupation/{user}', [MemberController::class, 'occupations'])->name('member.occupations');

        /** roles */
        Route::post('/member-roles/{user}', [MemberController::class, 'perfilRoles'])->name('member.roles');

        /** Remove roles */
        Route::post('/member-remove/{user}', [MemberController::class, 'removeRole'])->name('member.remove.roles');

        /**Config routes */
        Route::resource('roles', RoleController::class);

        /** Events Subscription*/
        Route::resource('events-subscriptions', EventSubscriptionController::class);
        Route::get('event-subscription/events', [EventSubscriptionController::class, 'event'])->name('event-subscription.event');

        /** Inscrição */
        Route::get('subscription/{id}', [SubscriptionController::class, 'index'])->name('subscription.index');
        Route::get('events-subscriptions/subscription/{id}', [SubscriptionController::class, 'create'])->name('subscription.create');
        Route::get('events-subscriptions/create/subscription', [SubscriptionController::class, 'createAtt'])->name('subscription.create.att');
        Route::get('events-subscriptions/create/subscription/render-pix', [SubscriptionController::class, 'renderPix'])->name('subscription.render.pix');
        Route::post('events-subscriptions/subscription', [SubscriptionController::class, 'store'])->name('subscription.store');
        Route::delete('subscription/{id}/destroy', [SubscriptionController::class, 'destroy'])->name('subscription.destroy');
        Route::get('events-subscriptions/subscription/edit/{id}', [SubscriptionController::class, 'edit'])->name('subscription.edit');
        Route::put('events-subscriptions/subscription/update/{id}', [SubscriptionController::class, 'update'])->name('subscription.update');
        Route::get('subscriptions/list/{eventType}', [SubscriptionController::class, 'list'])->name('subscription.list');
        Route::get('subscriptions/cancel/{subscription}', [SubscriptionController::class, 'cancelSubscription'])->name('subscription.cancel');
        Route::get('events-subscriptions/subscription/show/{id}', [SubscriptionController::class, 'show'])->name('subscription.show');
        /** Events */
        Route::resource('events', EventController::class);
        Route::get('events/copy/{id}', [EventController::class, 'copy'])->name('events.copy');
        Route::resource('events-types', EventTypeController::class);

        /** Events types-operations */
        Route::get('event-types-operation/{id}', [EventTypeOperationController::class, 'index'])->name('index.event.type.operations');
        Route::get('events-types/operation/{id}', [EventTypeOperationController::class, 'create'])->name('create.event.type.operation');
        Route::post('events-types/operation', [EventTypeOperationController::class, 'store'])->name('store.event.type.operation');
        Route::get('events-types/operation/edit/{id}', [EventTypeOperationController::class, 'edit'])->name('edit.event.type.operation');
        Route::put('events-types/operation/update/{id}', [EventTypeOperationController::class, 'update'])->name('update.event.type.operation');
        Route::delete('event-types-operation/destroy/{id}', [EventTypeOperationController::class, 'destroy'])->name('destroy.event.type.operation');

        /** Tithes */
        // Route::resource('tithes', TitheController::class);

        /** Payments */
        Route::resource('payments-methods', PaymentMethodsController::class);
        Route::resource('payments-options', PaymentOptionsController::class);

        /** Products */
        Route::resource('products', ProductController::class);
        Route::resource('products-types', ProductTypeController::class);

        /** Intentions */
        Route::get('/intention', [IntentionController::class, 'events'])->name('intentions.events');
        Route::get('/intention/v2', [IntentionController::class, 'eventsV2'])->name('intentions.eventsV2');
        Route::post('/intentions/v2', [IntentionController::class, 'indexV2'])->name('intentions.indexV2');
        Route::get('/intention/create', [IntentionController::class, 'create'])->name('intentions.create');
        Route::post('/intention/create', [IntentionController::class, 'storeIntention'])->name('intentions.store');
        Route::get('/intentions/events', [IntentionController::class, 'events'])->name('intentions-events');
        Route::delete('/intentions/v2/{id}/delete', [IntentionController::class, 'destroy'])->name('intentions.destroy');
        Route::get('/intentions-bonus', [IntentionController::class, 'intentionsBonus'])->name('intentions.bonus');

        /** Bill Intention */
        Route::get('/intention/bill/{intention}', [IntentionController::class, 'bill'])->name('intentions.bill');
        Route::post('/intention/bill', [IntentionController::class, 'billStore'])->name('intentions.billStore');

        /** Financial */
        Route::resource('financials', CostRevenueCenterController::class);
        Route::get('financials/nextAccount', [CostRevenueCenterController::class, 'nextAccount'])->name('financial.next');

        /** Bill */
        Route::resource('bills', BillController::class);
        Route::get('/bills/note/{id}', [BillController::class, 'note'])->name('bills.note');
        Route::get('/bills/send-email/{bill}', [BillController::class, 'sendEmail'])->name('bills.send.email');

        /**Pagarme */
        Route::get('/pagarme', [PagarmeController::class, 'index'])->name('pagarme.index');

        /** Operation Internal */
        Route::resource('operations', InternalOperationController::class);

        /** Chart Accounts */
        Route::resource('chart-accounts', ChartAccountController::class);

        /** Bank */
        Route::resource('bank', BankController::class);

        /** Bank Accounts  */
        Route::resource('bank-account', BankAccountController::class);

        /** Campaign */
        Route::resource('campaigns', CampaignController::class);

        /** Position Held */
        Route::resource('position-held', PositionHeldController::class);

        /** Position Held Campaign */
        Route::resource('position-held-campaign', PositionHeldCampaignController::class);

        /** Compor kit */
        Route::get('product-kit/{id}', [ProductKitController::class, 'index'])->name('product-kit.index');
        Route::get('product/product-kit/{id}', [ProductKitController::class, 'create'])->name('product-kit.create');
        Route::post('product/product-kit', [ProductKitController::class, 'store'])->name('product-kit.store');
        Route::get('product/product-kit/edit/{id}', [ProductKitController::class, 'edit'])->name('product-kit.edit');
        Route::put('product/product-kit/update/{id}', [ProductKitController::class, 'update'])->name('product-kit.update');
        Route::delete('product-kit/destroy/{id}', [ProductKitController::class, 'destroy'])->name('product-kit.destroy');

        /** Produto da Campanha */
        Route::get('campaign-product/{id}', [CampaignProductController::class, 'index'])->name('campaign-product.index');
        Route::get('campaign/campaign-product/{id}', [CampaignProductController::class, 'create'])->name('campaign-product.create');
        Route::post('campaign/campaign-product', [CampaignProductController::class, 'store'])->name('campaign-product.store');
        Route::delete('campaign-product/destroy/{id}', [CampaignProductController::class, 'destroy'])->name('campaign-product.destroy');

        /** Promoter da Campanha */
        Route::get('campaign-promoter/{id}', [CampaignPromoterController::class, 'index'])->name('campaign-promoter.index');
        Route::get('campaign/campaign-promoter/{id}', [CampaignPromoterController::class, 'create'])->name('campaign-promoter.create');
        Route::post('campaign/campaign-promoter', [CampaignPromoterController::class, 'store'])->name('campaign-promoter.store');
        Route::delete('campaign-promoter/destroy/{id}', [CampaignPromoterController::class, 'destroy'])->name('campaign-promoter.destroy');
        Route::get('campaign/campaign-promoter/edit/{id}', [CampaignPromoterController::class, 'edit'])->name('campaign-promoter.edit');
        Route::put('campaign/campaign-promoter/update/{id}', [CampaignPromoterController::class, 'update'])->name('campaign-promoter.update');
        Route::get('campaign/campaign-promoter/delivered/{campaignPromoter}', [CampaignPromoterController::class, 'delivered'])->name('campaign-promoter.delivered');
        Route::get('campaign/campaign-promoter/receipt/{campaignPromoter}', [CampaignPromoterController::class, 'receipt'])->name('campaign-promoter.receipt');

        /** Reservas */
        Route::get('reserv/hours', [ReservController::class, 'hours'])->name('reserv.hours');
        Route::post('reservs', [ReservController::class, 'reservs'])->name('reservs');

        /** Vincular tipos de intenção de missa com tipos de eventos */
        Route::get('event-type-intention/{id}', [IntentionEventTypeController::class, 'index'])->name('event-type-intention.index');
        Route::get('event-type/intention/{id}', [IntentionEventTypeController::class, 'create'])->name('event-type-intention.create');
        Route::post('events-type/intention', [IntentionEventTypeController::class, 'store'])->name('event-type-intention.store');
        Route::delete('event-type-intention/destroy/{id}', [IntentionEventTypeController::class, 'destroy'])->name('event-type-intention.destroy');

        /** Notice 'Avisos' */
        Route::resource('notice-types', TypeNoticeController::class);
        Route::resource('notices', NoticeController::class);

        /** Cashier 'Caixa' */
        Route::resource('cashiers', CashierController::class);

        /** CashierBill 'Lançamento de Caixa' */
        Route::resource('cashier-bill', CashierBillController::class);
        Route::get('cashiers/close/{id}', [CashierClosingController::class, 'close'])->name('cashiers.close.index');
        Route::post('cashiers/close', [CashierClosingController::class, 'cashierClose'])->name('cashiers.close.store');

        /** relatório de caixa */
        Route::get('report-cashier/{id}', [CashierBillController::class, 'report'])->name('report.cashier');

        /** Vincular avisos com os eventos */
        Route::get('notice-event/{id}', [NoticeEventController::class, 'index'])->name('notice-event.index');
        Route::get('notice/event/{id}', [NoticeEventController::class, 'create'])->name('notice-event.create');
        Route::post('notice/event', [NoticeEventController::class, 'store'])->name('notice-event.store');
        Route::delete('notice-event/destroy/{id}', [NoticeEventController::class, 'destroy'])->name('notice-event.destroy');

        /** Flayer  "Folheto" */
        Route::resource('flyers', FlyerController::class);

        /** Inventory  */
        Route::resource('inventory', InventoryMovementsController::class);

        /** Plans */
        Route::resource('plans', PlanController::class);

        /** PushNotifications */
        Route::resource('notifications', PushNotification::class);

        /** Attruibutes */
        Route::resource('attributes', AttributesController::class);
        Route::get('members-attributes/{attribute}', [AttributesController::class, 'membersAttributes'])->name('members-attributes');
        Route::post('members-attributes', [AttributesController::class, 'deleteMemberAttribute'])->name('members.attributes.remove');
        Route::post('member-attribute-remove', [AttributesController::class, 'removeAttribute'])->name('member.attribute.remove');

        /* Groups */
        Route::resource('groups', WebGroupController::class);
        Route::get('members-groups/{group}', [WebGroupController::class, 'membersGroups'])->name('members-groups');
        Route::post('members-groups', [WebGroupController::class, 'deleteMemberGroup'])->name('members.groups.remove');
        Route::post('member-group-remove', [WebGroupController::class, 'removeAttribute'])->name('member.group.remove');

        /** Relátorio de produtos (cartelas e bingos) */
        Route::get('campaign-product-report/{id}', [CampaignProductController::class, 'reportProduct'])->name('report.product');

        /** Relátorio de produtos */
        Route::get('relatório-produtos/{campaign}', [CampaignProductController::class, 'reportProducts'])->name('campaign.report.product');

        /** Entrega de produtos */
        Route::get('delivered-produto/{sale}', [CampaignProductController::class, 'deliveredProducts'])->name('delivered.product');

        /** Grupo das orações */
        Route::get('prayer-groups', [PrayerGroupController::class, 'index'])->name('prayer-groups.index');
        Route::post('prayer-groups', [PrayerGroupController::class, 'store'])->name('prayer-groups.store');
        Route::get('prayer-group/{prayerGroup}', [PrayerGroupController::class, 'edit'])->name('prayer-groups.edit');
        Route::put('prayer-group/{prayerGroup}', [PrayerGroupController::class, 'update'])->name('prayer-groups.update');
        Route::delete('prayer-group/{prayerGroup}', [PrayerGroupController::class, 'destroy'])->name('prayer-groups.delete');

        /** Orações */
        Route::get('prayers/{prayerGroup}', [PrayerController::class, 'index'])->name('prayers.index');
        Route::post('prayers', [PrayerController::class, 'store'])->name('prayers.store');
        Route::get('prayers/{prayer}/edit', [PrayerController::class, 'edit'])->name('prayers.edit');
        Route::get('prayers/{prayer}/show', [PrayerController::class, 'show'])->name('prayers.show');
        Route::put('prayers/{prayer}', [PrayerController::class, 'update'])->name('prayers.update');
        Route::delete('prayers/{prayer}', [PrayerController::class, 'destroy'])->name('prayers.delete');

        /** Occupations */
        Route::resource('occupations', OccupationController::class);
        Route::post('member-occupation-remove', [OccupationController::class, 'removeOccupation'])->name('member.occupation.remove');

        /** Testemonials */
        Route::resource('testemonials', TestemonialsController::class);
        Route::post('testemonials/aprovar/{id}', [TestemonialsController::class, 'toApprove'])->name('testemonials.to-approve');

        /** tithe inscriptions */
        Route::group(['prefix' => 'tithe'], function () {
            Route::get('inscriptions', [TitheInscriptionController::class, 'index'])->name('tithe.inscriptions');
            Route::get('inscriptions/{id}', [TitheInscriptionController::class, 'update'])->name('tithe.inscriptions.update');

            Route::get('inscriptions/{titheInscription}/contacts', [TitheInscriptionContactController::class, 'index'])->name('tithe.inscriptions.contacts.index');
            Route::post('inscriptions/{titheInscription}/create', [TitheInscriptionContactController::class, 'store'])->name('tithe.inscriptions.contacts.store');
        });

        /** Pastoral inscriptions */
        Route::group(['prefix' => 'pastoral'], function () {
            Route::get('inscriptions', [PastoralController::class, 'index'])->name('pastoral.inscriptions');
            Route::get('inscriptions/{userGroup}', [PastoralController::class, 'receive'])->name('pastoral.inscriptions.update');
        });

        Route::resource('cards', CardsController::class);

        /** Módulos */
        Route::get('tenant-modules', [TenantModuleController::class, 'index'])->name('module.index');
        Route::get('create-modules', [TenantModuleController::class, 'create'])->name('module.create');
        Route::post('tenant-modules', [TenantModuleController::class, 'store'])->name('module.store');
        Route::get('view-modules/{id}', [TenantModuleController::class, 'show'])->name('module.show');
        Route::get('tenant-modules/{id}', [TenantModuleController::class, 'edit'])->name('module.edit');
        Route::put('tenant-modules/{id}', [TenantModuleController::class, 'update'])->name('module.update');
        Route::delete('tenant-modules/{id}', [TenantModuleController::class, 'destroy'])->name('module.destroy');
        Route::post('create/module/tenant', [TenantModuleController::class, 'tenantModule'])->name('tenant-module.create');
        Route::delete('tenant-modules/delete/{id}', [TenantModuleController::class, 'destroyTenantModule'])->name('module.role.destroy');

        /** Adicionando permissão */
        Route::post('tenant-modules/permission/add', [TenantModuleController::class, 'permissionAdd'])->name('tenant.module.permission.add');

        /** Whats API */
        Route::get('api-whats', [WhatsController::class, 'index'])->name('chat.api');
        Route::get('api-whats/status', [WhatsController::class, 'status'])->name('chat.status');
        Route::post('api-whats/logout', [WhatsController::class, 'logout'])->name('chat.logout');

        Route::group(['prefix' => 'toin', 'as' => 'toin.'], function () {
            /** Operation Internal */
            Route::resource('operations', ToinInternalOperationController::class);
            /** Payments */
            Route::resource('payments-methods', ToinPaymentMethodsController::class);
        });

        /** Cashier supply bleed */
        Route::get('cashiers-supply-bleed/{id}', [CashierSupplyBleedController::class, 'index'])->name('cashiers-supply-bleed.index');
        Route::post('cashiers-supply-bleed', [CashierSupplyBleedController::class, 'store'])->name('cashiers-supply-bleed.store');

        Route::post('export/subscription', [SubscriptionController::class, 'export'])->name('subscription.exports.subscription');

        Route::get('subscription/{id}', [SubscriptionController::class, 'index'])->name('subscription.index');

        Route::get('my-parish', [MyParishController::class, 'index'])->name('my-parish');
        Route::post('my-parish', [MyParishController::class, 'store'])->name('parish.store');
        Route::put('my-parish/{myParish}', [MyParishController::class, 'update'])->name('parish.update');

        /** Recurrence  "Recorrência" */
        Route::resource('recurrence', RecurrenceController::class);

        /** Situação Paroquiano */
        Route::resource('status', MemberStatusController::class);

        /** QrCode */
        Route::get('qrcode', [QrcodeController::class, 'index'])->name('qrcode.index');
        Route::post('qrcode', [QrcodeController::class, 'store'])->name('qrcode.store');

        Route::get('graphics', [GraphicsController::class, 'index'])->name('graphics.index');
    });
});

require_once __DIR__ . '/dashv2.php';
