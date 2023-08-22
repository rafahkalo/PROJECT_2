<?php


use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BranchsController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\ComplaintExternalController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\EvaluatioController;
use App\Http\Controllers\ExternalReservations;
use App\Http\Controllers\ExternalTravelController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\GovermentController;
use App\Http\Controllers\OfficesController;
use App\Http\Controllers\OffireController;
use App\Http\Controllers\paymentFatoraController;
use App\Http\Controllers\PaymentTypeController;

use App\Http\Controllers\NotificationDriverController;
use App\Http\Controllers\NotificationUserController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\StarController;
use App\Http\Controllers\TypeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('ShowAllofficefatma', [OfficesController::class, 'ShowAllofficefatma']);

Route::get('getexternaltravelTooffice/{id}', [ExternalTravelController::class, 'getexternaltravelTooffice']);
Route::get('getIdExternalTravel/{id}', [ExternalTravelController::class, 'getIdExternalTravel']);

########### OfficesController ###################
Route::post('requestJoin', [OfficesController::class, 'requestJoin']);
Route::post('loginOffice', [OfficesController::class, 'loginOffice']);
Route::post('JoinRequest', [DriverController::class, 'JoinRequest']);
Route::get('alllBranches', [BranchsController::class, 'alllBranches']);
Route::get('showstars', [StarController::class, 'showStars']);


########### CarController ###################


Route::get('showCar_type', [CarController::class, 'showCar_type']);
Route::get('showcolor', [CarController::class, 'showcolor']);
Route::get('showExterneal', [ExternalTravelController::class, 'showExterneal']);


Route::get('getIdExternalTravel/{id}', [ExternalTravelController::class, 'getIdExternalTravel']);

//////////////////////////////////////////////////////////
Route::get('getIdCar/{id}', [CarController::class, 'getIdCar']);

Route::get('showGoverment', [GovermentController::class, 'showGoverment']);

Route::get('showdetailsOffice/{id}', [OfficesController::class, 'showdetailsOffice']);

Route::get('showAllOffices', [OfficesController::class, 'ShowAlloffice']);

Route::get('getIdOffer/{id}', [OfficesController::class, 'getIdOffer']);

Route::post('register', [AuthController::class, 'registerUser']);
Route::post('login', [AuthController::class, 'loginAdminAndUser']);
Route::post('loginDriver', [DriverController::class, 'loginDriver']);
//////////////////////////////////////////////////////
//////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('addgoverment', [GovermentController::class, 'addGoverment']);
    Route::post('addbranch', [BranchsController::class, 'addBranch']);
    Route::get('ShowAllOffer', [OfficesController::class, 'ShowAllOffer']);
    Route::post('updateCarbyOfficee/{id}', [CarController::class, 'updateCarbyOffice']);
    Route::post('addCarbydriver', [CarController::class, 'AddCarbydriver']);
    Route::post('searchByBranch/{id}', [BranchsController::class, 'searchByBranch']);
    Route::get('getInfoOffice/{id}', [OfficesController::class, 'getInformationOffice']);
    Route::get('getOfficesByStars/{num}', [OfficesController::class, 'getOfficesByStars']);
    Route::post('editStar/{id}', [OfficesController::class, 'editStar']);
    Route::post('updateCardriverbyOffice/{id}', [CarController::class, 'updateCardriverbyOffice']);
    Route::post('addCarsbyOffice', [CarController::class, 'AddCarbyOffice']);
    Route::post('addtypetravel', [TypeController::class, 'addtype']);
    Route::get('ShowType_travel', [TypeController::class, 'showtype_travel']);
    Route::post('addstars', [StarController::class, 'addStars']);
    Route::post('AddOffice', [OfficesController::class, 'AddOffice']);
    Route::post('AddOffer', [OfficesController::class, 'AddOffer']);
    Route::post('updateOffice', [OfficesController::class, 'updateOffice']);
    Route::post('AddReward', [RewardController::class, 'AddReward']);
    Route::get('ShowAllReward', [RewardController::class, 'ShowAllReward']);
    Route::post('UpdateReward', [RewardController::class, 'UpdateReward']);
    Route::get('finishonetravel/{id}', [DriverController::class, 'finishonetravel']);
    Route::get('showAllDriversnotAccept', [DriverController::class, 'showAllDriversnotAccept']);
    Route::get('showAllOfficesnotAccept', [OfficesController::class, 'showAllOfficesnotAccept']);
    Route::get('RefuseOffire/{id}', [OfficesController::class, 'RefuseOffire']);
    Route::post('AcceptOffice/{id}', [OfficesController::class, 'AcceptOffice']);
    Route::delete('RefuseOffice/{id}', [OfficesController::class, 'RefuseOffice']);
    Route::post('searchbyName', [OfficesController::class, 'searchByName']);
    Route::post('UpdateAmountCommuncal/{id}', [OfficesController::class, 'UpdateAmountCommuncal']);
    Route::get('getallTravel', [ExternalTravelController::class, 'getallTravel']);
    Route::post('addExternal_Travel', [ExternalTravelController::class, 'AddExternal_Travel']);
    Route::get('getTravel/{id}', [ExternalTravelController::class, 'getTravels']);
    Route::get('showEvaluation', [EvaluatioController::class, 'showEvaluation']);
    Route::get('showcomplaint_External', [ComplaintExternalController::class, 'showcomplaint_External']);
    Route::get('acceptDriver/{id}', [DriverController::class, 'AcceptDriver']);
    Route::delete('RefuseDriver/{id}', [DriverController::class, 'RefuseDriver']);
    Route::get('showonedriver/{id}', [DriverController::class, 'Showonedriver']);
    Route::get('ShowAlldriver', [DriverController::class, 'ShowAlldriver']);
    Route::get('showTravelStatusTrue', [ExternalTravelController::class, 'showTravelStatusTrue']);
    Route::get('showTravelStatusFalse', [ExternalTravelController::class, 'showTravelStatusFalse']);
    //////////////////by rafah/////////////////
    Route::post('addReservation', [ReservationController::class, 'store']);
    Route::get('acceptReservation/{id}', [ReservationController::class, 'acceptReservation']);
    Route::get('startTrip/{id}', [ReservationController::class, 'startTrip']);
    Route::get('showDetailsoftrip/{id}', [ReservationController::class, 'show']);
    Route::post('destroyReservation/{id}', [ReservationController::class, 'destroy']);
    Route::post('searchByTime', [ReservationController::class, 'searchByTime']);
    Route::get('ToDoTravels', [DriverController::class, 'ToDoTravels']);
    Route::get('FinishTravels', [DriverController::class, 'FinishTravels']);
    Route::get('profile', [DriverController::class, 'profile']);
    Route::get('aviableOrNot', [DriverController::class, 'aviableOrNot']);
    Route::post('updateDriver', [DriverController::class, 'update']);
    Route::post('addCarbydriver', [CarController::class, 'AddCarbydriver']);
    Route::get('showAllCarsToOffice', [CarController::class, 'showAllCarsToOffice']);
    Route::post('payInternal', [ReservationController::class, 'payInternal']);
    Route::post('addWalletUser', [CustomerController::class, 'addWalletUser']);

    //////////////////////TASNEEM///////////////////////////////////////////
    ///add user work

    Route::get('choosefavorite/{star}', [FavoriteController::class, 'store']);

    Route::prefix('External_travels')->group(function () {
        Route::get('show_available_Travels', [ExternalTravelController::class, 'show_available_Travels']);
        Route::get('{external_Travel}/show_available_seating_oneTravel', [SeatController::class, 'show_available_seating_oneTravel']);
        Route::post('{external_Travel}/AreSeatsAvailable ', [SeatController::class, 'check_pre_booking']);
        Route::post('{external_Travel}/externalReservation', [ExternalReservations::class, 'store']);
        Route::get('ExternalReservation/{externalReservation}/PaymentFatora/{paymenFatora}/reservation_sure', [ExternalReservations::class, 'reservation_sure']);
    });
    Route::post('ExternalReservation/{Reservation}/paymentMethod', [paymentFatoraController::class, 'paymentMethod']);


    Route::prefix('UserInformation/ExternalReservations')->group(function () {
        Route::get('showAllUserReservations', [ExternalReservations::class, 'showAllUserReservations']);
        Route::get('showAllOldUserReservations', [ExternalReservations::class, 'showAllOldUserReservations']);
        Route::delete('{externalReservation}/cancelUserReservation', [ExternalReservations::class, 'cancelUserReservation']);
        Route::get('{externalReservation}/showDetailsReservation', [ExternalReservations::class, 'showDetailsReservation']);
    });

    Route::prefix('TypeTravel/{TypeTravel}')->group(function () {
        Route::get('show_offices_favorite', [OfficesController::class, 'show_offices_favorite']);
        Route::get('get_Offires', [OffireController::class, 'get_Offires']);
    });

    Route::prefix('ProfileUser')->group(function () {
        Route::post('updateFavoriteUser', [FavoriteController::class, 'update']);
        Route::get('showFavoriteUser', [FavoriteController::class, 'show']);
    });

    Route::get('showPaymentTypes', [PaymentTypeController::class, 'index']);


    ///////////////////////////////////////////////////////////////////////////
});


Route::post('JoinRequestwithcar', [DriverController::class, 'JoinRequestwithcar']);
Route::post('JoinRequestnotcar', [DriverController::class, 'JoinRequestnotcar']);
Route::post('loginDriver', [DriverController::class, 'loginDriver']);

Route::post('JoinRequestwithcar', [DriverController::class, 'JoinRequestwithcar']);
Route::post('JoinRequestnotcar', [DriverController::class, 'JoinRequestnotcar']);
Route::post('loginDriver', [DriverController::class, 'loginDriver']);
//Rating
Route::post('addRating', [CustomerController::class, 'storeRating']);
//customer by rafah
Route::post('searchByNameOffice', [CustomerController::class, 'searchByNameOffice']);
Route::post('searchByBranchOffice', [CustomerController::class, 'searchByBranchOffice']);
Route::get('showAllOfficesAccept', [CustomerController::class, 'showAllOfficesAccept']);
Route::post('complaintInternals', [CustomerController::class, 'complaintInternals']);
Route::post('complaintExternal', [CustomerController::class, 'complaintExternal']);
Route::get('showMyTripInternal', [CustomerController::class, 'showMyTripInternal']);
Route::get('showAllDriversAccept', [CustomerController::class, 'showAllDriversAccept']);
Route::get('showOneTrip/{id}', [CustomerController::class, 'showOneTrip']);
Route::post('profileuser', [CustomerController::class, 'profile']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('showdetailsOffice/{id}', [OfficesController::class, 'showdetailsOffice']);
});


Route::get('AllNotiDriver',[NotificationDriverController::class,'index']);
Route::get('AllNotiUser',[NotificationUserController::class,'index']);