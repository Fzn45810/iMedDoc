<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('getdoctor', 'App\Http\Controllers\Doctor@get_doctor');

Route::post('newpatient', 'App\Http\Controllers\BookingController@add_patient');
Route::post('updatepatient', 'App\Http\Controllers\BookingController@update_patient');
Route::get('getpatient', 'App\Http\Controllers\BookingController@get_all_patient');
Route::get('singlepatient/{id}', 'App\Http\Controllers\BookingController@get_single_patient');

Route::post('waitinglist', 'App\Http\Controllers\WaitingListController@create');
Route::post('waitinglistupdate', 'App\Http\Controllers\WaitingListController@update');
Route::get('getwaitinglist', 'App\Http\Controllers\WaitingListController@GetWaitinglist');
Route::get('get_single_waitinglist/{waitingid}', 'App\Http\Controllers\WaitingListController@get_single_waitinglist');
Route::patch('/updatewaitinglistsataus/{id}', 'App\Http\Controllers\WaitingListController@update_waitinglist_sataus');

Route::post('/chatmessage', 'App\Http\Controllers\ChatController@ChatMessage')->name('chat.message');

Route::post('/sendemail', 'App\Http\Controllers\MailController@SendEmail');
Route::get('getsentemail', 'App\Http\Controllers\MailController@GetAllSendEmail');

Route::put('/archivemail', 'App\Http\Controllers\MailController@ArchivEmail');
Route::get('getarchiveemail', 'App\Http\Controllers\MailController@GetAllArchiveEmail');

Route::post('/draftemail', 'App\Http\Controllers\MailController@DraftEmail');
Route::post('/sentdraftemail', 'App\Http\Controllers\MailController@SentDraftEmail');
Route::get('getdraftemail', 'App\Http\Controllers\MailController@GetDraftEmail');

// Calendar
Route::post('/calendar', 'App\Http\Controllers\CalendarController@create');
Route::post('/calendarappoint', 'App\Http\Controllers\CalendarController@CreateAppointment');
Route::get('/getcalendarappoint', 'App\Http\Controllers\CalendarController@get_calendar_appoint');

Route::patch('/updateappointsataus/{id}/{value}', 'App\Http\Controllers\CalendarController@update_appoint_sataus');

Route::post('/calendarsurgery', 'App\Http\Controllers\CalendarController@create_surgery');
Route::get('/getcalendarsurgery', 'App\Http\Controllers\CalendarController@get_surgery');

Route::patch('/updatesurgerysataus/{id}/{value}', 'App\Http\Controllers\CalendarController@update_surgery_sataus');

Route::post('/calendartasks', 'App\Http\Controllers\CalendarController@create_tasks');
Route::get('/getcalendartasks', 'App\Http\Controllers\CalendarController@get_tasks');
Route::patch('/updatetaskssataus/{id}/{value}', 'App\Http\Controllers\CalendarController@update_tasks_sataus');

Route::get('getcalendar', 'App\Http\Controllers\CalendarController@GetCalendar');
Route::post('/bydatecalendar', 'App\Http\Controllers\CalendarController@getDateCalendar');

Route::post('/contacts', 'App\Http\Controllers\ContactsController@create');
Route::post('/update', 'App\Http\Controllers\ContactsController@update');
Route::get('/getsingle/{id}', 'App\Http\Controllers\ContactsController@single_contact');
Route::get('getcontacts', 'App\Http\Controllers\ContactsController@get');
Route::get('getcontacttype/{type}', 'App\Http\Controllers\ContactsController@contact_type');

Route::post('/addform', 'App\Http\Controllers\FormController@create');
Route::get('/getform', 'App\Http\Controllers\FormController@get');

Route::post('/incomecategory', 'App\Http\Controllers\IncomeCategoryController@create');
Route::get('/getincomecategory', 'App\Http\Controllers\IncomeCategoryController@get');

Route::post('/addhospital', 'App\Http\Controllers\HospitalController@create');
Route::get('/gethospital', 'App\Http\Controllers\HospitalController@get');

Route::post('/addappointdec', 'App\Http\Controllers\AppointDescripController@create');
Route::get('/getappointdec', 'App\Http\Controllers\AppointDescripController@get');

Route::post('/addinsurancomp', 'App\Http\Controllers\InsuranCompanyController@create');
Route::get('/getinsurancomp', 'App\Http\Controllers\InsuranCompanyController@get');

Route::post('/addinsuranplan', 'App\Http\Controllers\InsurancePlaneController@create');
Route::get('/getinsuranplan', 'App\Http\Controllers\InsurancePlaneController@get');
Route::get('/getsingleinsuranplan/{id}', 'App\Http\Controllers\InsurancePlaneController@get_single');

Route::post('/updateinsuranplan', 'App\Http\Controllers\InsurancePlaneController@update');

Route::post('/procedures', 'App\Http\Controllers\ProceduresController@create');
Route::get('/getprocedures', 'App\Http\Controllers\ProceduresController@get');
Route::get('/singleprocedures/{id}', 'App\Http\Controllers\ProceduresController@get_single');

Route::post('/cliniclocation', 'App\Http\Controllers\ClinicLocationController@create');
Route::get('/getcliniclocation', 'App\Http\Controllers\ClinicLocationController@get');

Route::post('/appointtype', 'App\Http\Controllers\AppontTypeController@create');
Route::get('/getappointtype', 'App\Http\Controllers\AppontTypeController@get');

Route::post('/patienttype', 'App\Http\Controllers\BookingController@add_patient_type');
Route::get('/getpatienttype', 'App\Http\Controllers\BookingController@get_patient_type');

Route::post('/contacttype', 'App\Http\Controllers\BookingController@add_contact_type');
Route::get('/getcontacttype', 'App\Http\Controllers\BookingController@get_contact_type');

Route::post('/titletype', 'App\Http\Controllers\BookingController@add_title_type');
Route::get('/gettitletype', 'App\Http\Controllers\BookingController@get_title_type');

Route::post('/bankdetails', 'App\Http\Controllers\BankController@create');
Route::post('/updatebankdetails', 'App\Http\Controllers\BankController@update');
Route::get('/getbankdetails', 'App\Http\Controllers\BankController@get');
Route::get('/singlebankdetails/{id}', 'App\Http\Controllers\BankController@single_get');

Route::post('/invoice', 'App\Http\Controllers\AccountsController@create_invoice');
Route::post('/updateinvoice', 'App\Http\Controllers\AccountsController@update_invoice');
Route::get('getinvoice', 'App\Http\Controllers\AccountsController@get_invoice');
Route::get('singleinvoice/{id}', 'App\Http\Controllers\AccountsController@get_single_invoice');

Route::post('/receipt', 'App\Http\Controllers\AccountsController@create_receipt');
Route::post('/updatereceipt', 'App\Http\Controllers\AccountsController@update_receipt');
Route::get('getreceipt', 'App\Http\Controllers\AccountsController@get_receipt');
Route::get('singlereceipt/{id}', 'App\Http\Controllers\AccountsController@get_single_receipt');
Route::get('/getreceivedfrom/{type}/{id}', 'App\Http\Controllers\AccountsController@get_received_from');

Route::post('/lodgement', 'App\Http\Controllers\AccountsController@create_lodgement');
Route::post('/updatelodgement', 'App\Http\Controllers\AccountsController@update_lodgement');
Route::get('/getlodgement', 'App\Http\Controllers\AccountsController@get_lodgement');
Route::get('/singlelodgement/{id}', 'App\Http\Controllers\AccountsController@get_single__lodgement');

Route::post('/expenses', 'App\Http\Controllers\AccountsController@create_expenses');
Route::post('/updateexpenses', 'App\Http\Controllers\AccountsController@update_expenses');
Route::get('/getexpenses', 'App\Http\Controllers\AccountsController@get_expenses');
Route::get('/singleexpenses/{id}', 'App\Http\Controllers\AccountsController@get_single_expenses');

Route::post('/dictation', 'App\Http\Controllers\DictationController@create_dictation');
Route::post('/updatedictation', 'App\Http\Controllers\DictationController@update_dictation');
Route::get('/getdictation', 'App\Http\Controllers\DictationController@get_dictation');
Route::get('/singledictation/{id}', 'App\Http\Controllers\DictationController@get_single_dictation');

Route::post('/stickynote', 'App\Http\Controllers\StickyNoteController@create_stickynote');
Route::post('/updatestickynotedes', 'App\Http\Controllers\StickyNoteController@update_stickynote_des');
Route::post('/upstickynoteisactive', 'App\Http\Controllers\StickyNoteController@update_stickynote_is_active');
Route::get('/getstickynotes', 'App\Http\Controllers\StickyNoteController@get_sticky_notes');
Route::get('/getsinglestickynote/{id}', 'App\Http\Controllers\StickyNoteController@get_single_sticky_note');
