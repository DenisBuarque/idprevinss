<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\TermController;
use App\Http\Controllers\FranchiseeController;
use App\Http\Controllers\LawyerController;
use App\Http\Controllers\ActionController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\WorksheetController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\FinancialController;
use App\Http\Controllers\AdministrativeController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\HomeController;

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

/*Route::get('/', function () {
    return view('welcome');
});*/


Auth::routes();

Route::get('/', [App\Http\Controllers\SiteController::class, 'index'])->name('site');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->middleware(['auth:sanctum', 'verified'])->name('home');
Route::post('/home/store', [App\Http\Controllers\HomeController::class, 'store'])->middleware(['auth:sanctum', 'verified'])->name('home.store');

Route::get('/admin/users', [UserController::class, 'index'])->middleware(['auth:sanctum', 'verified'])->name('admin.users.index');
Route::get('/admin/user/create', [UserController::class, 'create'])->middleware(['auth:sanctum', 'verified'])->name('admin.users.create');
Route::get('/admin/user/edit/{id}', [UserController::class, 'edit'])->middleware(['auth:sanctum', 'verified'])->name('admin.users.edit');
Route::put('/admin/user/update/{id}', [UserController::class, 'update'])->middleware(['auth:sanctum', 'verified'])->name('admin.users.update');
Route::post('/admin/user/store', [UserController::class, 'store'])->middleware(['auth:sanctum', 'verified'])->name('admin.users.store');
Route::delete('/admin/user/destroy/{id}', [UserController::class, 'destroy'])->middleware(['auth:sanctum', 'verified'])->name('admin.users.destroy');

Route::get('/admin/leads', [LeadController::class, 'index'])->middleware(['auth:sanctum', 'verified'])->name('admin.leads.index');
Route::get('/admin/lead/create', [LeadController::class, 'create'])->middleware(['auth:sanctum', 'verified'])->name('admin.leads.create');
Route::post('/admin/lead/store', [LeadController::class, 'store'])->middleware(['auth:sanctum', 'verified'])->name('admin.leads.store');
Route::get('/admin/lead/edit/{id}', [LeadController::class, 'edit'])->middleware(['auth:sanctum', 'verified'])->name('admin.leads.edit');
Route::put('/admin/lead/update/{id}', [LeadController::class, 'update'])->middleware(['auth:sanctum', 'verified'])->name('admin.leads.update');
Route::get('/admin/lead/show/{id}', [LeadController::class, 'show'])->middleware(['auth:sanctum', 'verified'])->name('admin.leads.show');
Route::delete('/admin/lead/destroy/{id}', [LeadController::class, 'destroy'])->middleware(['auth:sanctum', 'verified'])->name('admin.leads.destroy');
Route::get('/admin/lead/tag/{tag}', [LeadController::class, 'leads'])->middleware(['auth:sanctum', 'verified'])->name('admin.leads.tag');
Route::post('/admin/lead/feedback', [LeadController::class, 'feedback'])->middleware(['auth:sanctum', 'verified'])->name('admin.leads.feedback');

Route::get('/admin/clients', [ClientController::class, 'index'])->middleware(['auth:sanctum', 'verified'])->name('admin.clients.index');
Route::get('/admin/client/create', [ClientController::class, 'create'])->middleware(['auth:sanctum', 'verified'])->name('admin.clients.create');
Route::post('/admin/client/store', [ClientController::class, 'store'])->middleware(['auth:sanctum', 'verified'])->name('admin.clients.store');
Route::get('/admin/client/edit/{id}', [ClientController::class, 'edit'])->middleware(['auth:sanctum', 'verified'])->name('admin.clients.edit');
Route::put('/admin/client/update/{id}', [ClientController::class, 'update'])->middleware(['auth:sanctum', 'verified'])->name('admin.clients.update');
Route::get('/admin/client/show/{id}', [ClientController::class, 'show'])->middleware(['auth:sanctum', 'verified'])->name('admin.clients.show');
Route::delete('/admin/client/delete/{id}', [ClientController::class, 'destroy'])->middleware(['auth:sanctum', 'verified'])->name('admin.clients.destroy');
Route::post('/admin/client/feedback', [ClientController::class, 'feedback'])->middleware(['auth:sanctum', 'verified'])->name('admin.clients.feedback');
Route::get('/admin/client/tag/{tag}', [ClientController::class, 'tag'])->middleware(['auth:sanctum', 'verified'])->name('admin.clients.tag');
Route::delete('/admin/client/remove/photo', [ClientController::class, 'remove'])->middleware(['auth:sanctum', 'verified'])->name('admin.clients.remove.photo');
Route::get('/admin/client/lawyers/{id}', [ClientController::class, 'lawyers'])->middleware(['auth:sanctum', 'verified'])->name('admin.clients.lawyers');
Route::get('/admin/client/documents/{id}', [ClientController::class, 'documents'])->middleware(['auth:sanctum', 'verified'])->name('admin.clients.documents');

Route::get('/admin/term/create/{id}', [TermController::class, 'create'])->middleware(['auth:sanctum', 'verified'])->name('admin.terms.create');
Route::post('/admin/term/store', [TermController::class, 'store'])->middleware(['auth:sanctum', 'verified'])->name('admin.terms.store');
Route::get('/admin/term/edit/{id}', [TermController::class, 'edit'])->middleware(['auth:sanctum', 'verified'])->name('admin.terms.edit');
Route::put('/admin/term/update/{id}', [TermController::class, 'update'])->middleware(['auth:sanctum', 'verified'])->name('admin.terms.update');
Route::delete('/admin/term/delete/{id}', [TermController::class, 'destroy'])->middleware(['auth:sanctum', 'verified'])->name('admin.terms.delete');

Route::get('/admin/franchisees', [FranchiseeController::class, 'index'])->middleware(['auth:sanctum', 'verified'])->name('admin.franchisees.index');
Route::get('/admin/franchisee/create', [FranchiseeController::class, 'create'])->middleware(['auth:sanctum', 'verified'])->name('admin.franchisees.create');
Route::post('/admin/franchisee/store', [FranchiseeController::class, 'store'])->middleware(['auth:sanctum', 'verified'])->name('admin.franchisees.store');
Route::get('/admin/franchisee/edit/{id}', [FranchiseeController::class, 'edit'])->middleware(['auth:sanctum', 'verified'])->name('admin.franchisees.edit');
Route::put('/admin/franchisee/update/{id}', [FranchiseeController::class, 'update'])->middleware(['auth:sanctum', 'verified'])->name('admin.franchisees.update');
Route::delete('/admin/franchisee/delete/{id}', [FranchiseeController::class, 'destroy'])->middleware(['auth:sanctum', 'verified'])->name('admin.franchisees.destroy');

Route::get('/admin/lawyers', [LawyerController::class, 'index'])->middleware(['auth:sanctum', 'verified'])->name('admin.lawyers.index');
Route::get('/admin/lawyer/create', [LawyerController::class, 'create'])->middleware(['auth:sanctum', 'verified'])->name('admin.lawyers.create');
Route::post('/admin/lawyer/store', [LawyerController::class, 'store'])->middleware(['auth:sanctum', 'verified'])->name('admin.lawyers.store');
Route::get('/admin/lawyer/edit/{id}', [LawyerController::class, 'edit'])->middleware(['auth:sanctum', 'verified'])->name('admin.lawyers.edit');
Route::put('/admin/lawyer/update/{id}', [LawyerController::class, 'update'])->middleware(['auth:sanctum', 'verified'])->name('admin.lawyers.update');
Route::delete('/admin/lawyer/delete/{id}', [LawyerController::class, 'destroy'])->middleware(['auth:sanctum', 'verified'])->name('admin.lawyers.destroy');

Route::get('/admin/actions', [ActionController::class, 'index'])->middleware(['auth:sanctum', 'verified'])->name('admin.actions.index');
Route::get('/admin/action/create', [ActionController::class, 'create'])->middleware(['auth:sanctum', 'verified'])->name('admin.actions.create');
Route::post('/admin/action/store', [ActionController::class, 'store'])->middleware(['auth:sanctum', 'verified'])->name('admin.actions.store');
Route::get('/admin/action/edit/{id}', [ActionController::class, 'edit'])->middleware(['auth:sanctum', 'verified'])->name('admin.actions.edit');
Route::put('/admin/action/update/{id}', [ActionController::class, 'update'])->middleware(['auth:sanctum', 'verified'])->name('admin.actions.update');
Route::delete('/admin/action/delete/{id}', [ActionController::class, 'destroy'])->middleware(['auth:sanctum', 'verified'])->name('admin.actions.destroy');

Route::get('/admin/documents', [DocumentController::class, 'index'])->middleware(['auth:sanctum', 'verified'])->name('admin.documents.index');
Route::get('/admin/document/create', [DocumentController::class, 'create'])->middleware(['auth:sanctum', 'verified'])->name('admin.documents.create');
Route::post('/admin/document/store', [DocumentController::class, 'store'])->middleware(['auth:sanctum', 'verified'])->name('admin.documents.store');
Route::get('/admin/document/edit/{id}', [DocumentController::class, 'edit'])->middleware(['auth:sanctum', 'verified'])->name('admin.documents.edit');
Route::put('/admin/document/update/{id}', [DocumentController::class, 'update'])->middleware(['auth:sanctum', 'verified'])->name('admin.documents.update');
Route::delete('/admin/document/delete/{id}', [DocumentController::class, 'destroy'])->middleware(['auth:sanctum', 'verified'])->name('admin.documents.destroy');

Route::get('/admin/worksheets', [WorksheetController::class, 'index'])->middleware(['auth:sanctum', 'verified'])->name('admin.worksheets.index');
Route::get('/admin/worksheet/create', [WorksheetController::class, 'create'])->middleware(['auth:sanctum', 'verified'])->name('admin.worksheets.create');
Route::post('/admin/worksheet/store', [WorksheetController::class, 'store'])->middleware(['auth:sanctum', 'verified'])->name('admin.worksheets.store');
Route::get('/admin/worksheet/edit/{id}', [WorksheetController::class, 'edit'])->middleware(['auth:sanctum', 'verified'])->name('admin.worksheets.edit');
Route::put('/admin/worksheet/update/{id}', [WorksheetController::class, 'update'])->middleware(['auth:sanctum', 'verified'])->name('admin.worksheets.update');
Route::delete('/admin/worksheet/delete/{id}', [WorksheetController::class, 'destroy'])->middleware(['auth:sanctum', 'verified'])->name('admin.worksheets.destroy');

Route::get('/admin/trainings', [TrainingController::class, 'index'])->middleware(['auth:sanctum', 'verified'])->name('admin.trainings.index');
Route::get('/admin/training/create', [TrainingController::class, 'create'])->middleware(['auth:sanctum', 'verified'])->name('admin.trainings.create');
Route::post('/admin/training/store', [TrainingController::class, 'store'])->middleware(['auth:sanctum', 'verified'])->name('admin.trainings.store');
Route::get('/admin/training/edit/{id}', [TrainingController::class, 'edit'])->middleware(['auth:sanctum', 'verified'])->name('admin.trainings.edit');
Route::put('/admin/training/update/{id}', [TrainingController::class, 'update'])->middleware(['auth:sanctum', 'verified'])->name('admin.trainings.update');
Route::delete('/admin/training/delete/{id}', [TrainingController::class, 'destroy'])->middleware(['auth:sanctum', 'verified'])->name('admin.trainings.destroy');
Route::get('/admin/training/download/{id}', [TrainingController::class, 'download'])->middleware(['auth:sanctum', 'verified'])->name('admin.trainings.download');

Route::get('/admin/events', [EventController::class, 'index'])->middleware(['auth:sanctum', 'verified'])->name('admin.events.index');
Route::get('/admin/event/create', [EventController::class, 'create'])->middleware(['auth:sanctum', 'verified'])->name('admin.events.create');
Route::post('/admin/event/store', [EventController::class, 'store'])->middleware(['auth:sanctum', 'verified'])->name('admin.events.store');
Route::get('/admin/event/edit/{id}', [EventController::class, 'edit'])->middleware(['auth:sanctum', 'verified'])->name('admin.events.edit');
Route::put('/admin/event/update/{id}', [EventController::class, 'update'])->middleware(['auth:sanctum', 'verified'])->name('admin.events.update');
Route::delete('/admin/event/delete/{id}', [EventController::class, 'destroy'])->middleware(['auth:sanctum', 'verified'])->name('admin.events.destroy');

Route::get('/admin/tickets', [TicketController::class, 'index'])->middleware(['auth:sanctum', 'verified'])->name('admin.tickets.index');
Route::get('/admin/ticket/create', [TicketController::class, 'create'])->middleware(['auth:sanctum', 'verified'])->name('admin.tickets.create');
Route::post('/admin/ticket/store', [TicketController::class, 'store'])->middleware(['auth:sanctum', 'verified'])->name('admin.tickets.store');
Route::get('/admin/ticket/edit/{id}', [TicketController::class, 'edit'])->middleware(['auth:sanctum', 'verified'])->name('admin.tickets.edit');
Route::put('/admin/ticket/update/{id}', [TicketController::class, 'update'])->middleware(['auth:sanctum', 'verified'])->name('admin.tickets.update');
Route::delete('/admin/ticket/delete/{id}', [TicketController::class, 'destroy'])->middleware(['auth:sanctum', 'verified'])->name('admin.tickets.destroy');
Route::post('/admin/ticket/feedback/{id}', [TicketController::class, 'feedback'])->middleware(['auth:sanctum', 'verified'])->name('admin.tickets.feedback');
Route::get('/admin/ticket/category/{id}', [TicketController::class, 'category'])->middleware(['auth:sanctum', 'verified'])->name('admin.tickets.category');

Route::get('/admin/financials', [FinancialController::class, 'index'])->middleware(['auth:sanctum', 'verified'])->name('admin.financials.index');
Route::get('/admin/financial/autofindos', [FinancialController::class, 'findos'])->middleware(['auth:sanctum', 'verified'])->name('admin.financials.findos');
Route::post('/admin/financial/store', [FinancialController::class, 'store'])->middleware(['auth:sanctum', 'verified'])->name('admin.financials.store');
Route::get('/admin/financial/edit/{id}', [FinancialController::class, 'edit'])->middleware(['auth:sanctum', 'verified'])->name('admin.financials.edit');
Route::put('/admin/financial/update/{id}', [FinancialController::class, 'update'])->middleware(['auth:sanctum', 'verified'])->name('admin.financials.update');
Route::delete('/admin/financial/remove/photo', [FinancialController::class, 'remove'])->middleware(['auth:sanctum', 'verified'])->name('admin.financials.remove.photo');
Route::post('/admin/financial/confirm/payment', [FinancialController::class, 'confirm'])->middleware(['auth:sanctum', 'verified'])->name('admin.financials.confirm.payment');
Route::get('/admin/financial/confirm/{id}', [FinancialController::class, 'pago'])->middleware(['auth:sanctum', 'verified'])->name('admin.financials.pago');
Route::get('/admin/financial/delete/{id}', [FinancialController::class, 'destroy'])->middleware(['auth:sanctum', 'verified'])->name('admin.financials.destroy');
Route::post('/admin/financial/feedback', [FinancialController::class, 'feedback'])->middleware(['auth:sanctum', 'verified'])->name('admin.financials.feedback');

Route::get('/admin/administratives', [AdministrativeController::class, 'index'])->middleware(['auth:sanctum', 'verified'])->name('admin.administratives.index');
Route::get('/admin/administrative/create', [AdministrativeController ::class, 'create'])->middleware(['auth:sanctum', 'verified'])->name('admin.administratives.create');
Route::post('/admin/administrative/store', [AdministrativeController::class, 'store'])->middleware(['auth:sanctum', 'verified'])->name('admin.administratives.store');
Route::get('/admin/administrative/edit/{id}', [AdministrativeController::class, 'edit'])->middleware(['auth:sanctum', 'verified'])->name('admin.administratives.edit');
Route::put('/admin/administrative/update/{id}', [AdministrativeController::class, 'update'])->middleware(['auth:sanctum', 'verified'])->name('admin.administratives.update');
Route::delete('/admin/administrative/delete/{id}', [AdministrativeController::class, 'destroy'])->middleware(['auth:sanctum', 'verified'])->name('admin.administratives.delete');

Route::get('/admin/testimonials', [TestimonialController::class, 'index'])->middleware(['auth:sanctum', 'verified'])->name('admin.testimonials.index');
Route::get('/admin/testimonial/create', [TestimonialController ::class, 'create'])->middleware(['auth:sanctum', 'verified'])->name('admin.testimonials.create');
Route::post('/admin/testimonial/store', [TestimonialController::class, 'store'])->middleware(['auth:sanctum', 'verified'])->name('admin.testimonials.store');
Route::get('/admin/testimonial/edit/{id}', [TestimonialController::class, 'edit'])->middleware(['auth:sanctum', 'verified'])->name('admin.testimonials.edit');
Route::put('/admin/testimonial/update/{id}', [TestimonialController::class, 'update'])->middleware(['auth:sanctum', 'verified'])->name('admin.testimonials.update');
Route::delete('/admin/testimonial/delete/{id}', [TestimonialController::class, 'destroy'])->middleware(['auth:sanctum', 'verified'])->name('admin.testimonials.delete');
