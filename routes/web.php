<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\ManageController;
use App\Http\Controllers\Client\CouponController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Frontend\OrderController;
use App\Http\Controllers\Admin\ManageOrderController;
use App\Http\Controllers\Client\RestaurantController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/',[UserController::class, 'Index'])->name('index');

Route::get('/dashboard', function () {
    return view('frontend.dashboard.profile');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/user/profile/store', [UserController::class, 'UserProfileStore'])->name('user.profile.store');
    Route::get('/user/logout', [UserController::class, 'UserLogout'])->name('user.logout');
    Route::get('/user/change/passowrd', [UserController::class, 'UserChangePassword'])->name('user.change.password');
    Route::post('/user/update/passowrd', [UserController::class, 'UserUpdatePassword'])->name('user.update.password');


    Route::controller(HomeController::class)->group(function(){
        Route::get('/all/wishList', 'AllWishList')->name('all.wishList');
        Route::get('/remove/wishList/{id}', 'RemoveWishList')->name('remove.wishlist');
    });


  
});

require __DIR__.'/auth.php';

Route::middleware('admin')->group(function(){
    Route::get('/admin/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.dashboard');
    Route::get('/admin/profile', [AdminController::class, 'AdminProfile'])->name('admin.profile');
    // store the updated profile information of admin
    Route::post('/admin/profile/store',[AdminController::class,'AdminProfileStore'])->name('admin.profile.store');
    //Admin Change password
    Route::get('/admin/change/password',[AdminController::class,'AdminChangePassword'])->name('admin.change.password');
    Route::post('/admin/password/update',[AdminController::class,'AdminPasswordUpdate'])->name('admin.password.update');
});

Route::get('/admin/login', [AdminController::class, 'AdminLogin'])->name('admin.login');
Route::post('/admin/login/submit', [AdminController::class, 'AdminLoginSubmit'])->name('admin.login_submit');
Route::get('/admin/logout', [AdminController::class, 'AdminLogout'])->name('admin.logout');
Route::get('/admin/forget/password',[AdminController::class,'AdminForgetPassword'])->name('admin.forget_password');
Route::post('/admin/password/submit',[AdminController::class,'AdminPasswordSubmit'])->name('admin.password_submit');
Route::get('/admin/reset-password/{token}/{email}',[AdminController::class,'AdminResetPassword']);
Route::post('/admin/reset/password/submit',[AdminController::class,'AdminResetPasswordSubmit'])->name('admin.reset_password_submit');


// CLIENT ROUTE

Route::middleware('client')->group(function(){
    Route::get('/client/dashboard',[ClientController::class,'ClientDashboard'])->name('client.dashboard');
    Route::get('/client/profile',[ClientController::class,'ClientProfile'])->name('client.profile');
    Route::post('/client/profile/store',[ClientController::class,'ClientProfileStore'])->name('client.profile.store');
     //Admin Change password
     Route::get('/client/change/password',[ClientController::class,'ClientChangePassword'])->name('client.change.password');
     Route::post('/client/password/update',[ClientController::class,'ClientPasswordUpdate'])->name('client.password.update');
});
Route::get('/client/login',[ClientController::class,'ClientLogin'])->name('client.login');
Route::get('/client/register',[ClientController::class,'ClientRegister'])->name('client.register');
Route::get('/client/forget/password',[ClientController::class,'ClientForgetPassword'])->name('client.forget_password');
Route::post('/client/register/submit',[ClientController::class,'ClientRegisterSubmit'])->name('client.register_submit');
Route::post('/client/login/submit',[ClientController::class,'ClientLoginSubmit'])->name('client.login_submit');
Route::get('/client/logout', [ClientController::class, 'ClientLogout'])->name('client.logout');
Route::get('/client/forget/password',[ClientController::class,'ClientForgetPassword'])->name('client.forget_password');
Route::post('/client/password/submit',[ClientController::class,'ClientPasswordSubmit'])->name('client.password_submit');
Route::get('/client/reset-password/{token}/{email}',[ClientController::class,'ClientResetPassword']);
Route::post('/client/reset/password/submit',[ClientController::class,'ClientResetPasswordSubmit'])->name('client.reset_password_submit');


//All Admin Category Controller
Route::middleware('admin')->group(function(){
    Route::controller(CategoryController::class)->group(function(){
        Route::get('/all/category','AllCategory')->name('all.category');
        Route::get('/add/category','AddCategory')->name('add.category');
        Route::post('/category/store','CategoryStore')->name('category.store');
        Route::get('/edit/category/{id}','EditCategory')->name('edit.category');
        Route::post('/update/category/{id}','UpdateCategory')->name('update.category');
        Route::get('/delete/category/{id}','DeleteCategory')->name('delete.category');
    });
    
});//end

//All Admin City Route
Route::middleware('admin')->group(function(){
    Route::controller(CityController::class)->group(function(){
        Route::get('/all/city','AllCity')->name('all.city');
        Route::post('/city/store','CityStore')->name('city.store');
        Route::get('/edit/city/{id}','EditCity');
        Route::post('/update/city','UpdateCity')->name('city.update');
        Route::get('/delete/city/{id}','DeleteCity')->name('delete.city');
    });
    
});//end


Route::middleware('admin')->group(function(){
    //Manage Product from admin
    Route::controller(ManageController::class)->group(function(){
        Route::get('/admin/all/product','AdminAllProduct')->name('admin.all.product');
        Route::get('/admin/add/product','AdminAddProduct')->name('admin.add.product');
        Route::post('/admin/store/product','AdminStoreProduct')->name('admin.store.product');
        Route::get('/admin/edit/product/{id}','AdminEditProduct')->name('admin.edit.product');
        Route::post('/admin/update/product/{id}','AdminUpdateProduct')->name('admin.update.product');
        Route::get('/admin/delete/product/{id}','AdminDeleteProduct')->name('admin.delete.product');
    });

    // Manage Restaurant- Pending and Active
    Route::controller(ManageController::class)->group(function(){

        Route::get('/pending/restaurant','PendingRestaurant')->name('pending.restaurant');
        Route::get('/clientchangeStatus','ClientChangeStatus');
        Route::get('/active/restaurant','ActiveRestaurant')->name('active.restaurant');

    });


    Route::controller(ManageController::class)->group(function(){

        Route::get('/all/banner','AllBanner')->name('all.banner');
        Route::post('/store/banner','StoreBanner')->name('store.banner');
        Route::get('/edit/banner/{id}','EditBanner');
        Route::post('/update/banner','UpdateBanner')->name('update.banner');
        Route::get('/delete/banner/{id}','DeleteBanner')->name('delete.banner');

    });


    //ManageOrder By Admin
    Route::controller(ManageOrderController::class)->group(function(){

        Route::get('/pending/order','PendingOrder')->name('pending.order');
        Route::get('/confirm/order', 'ConfirmOrder')->name('confirm.order'); 
        Route::get('/processing/order', 'ProcessingOrder')->name('processing.order'); 
        Route::get('/deliverd/order', 'DeliverdOrder')->name('deliverd.order'); 
        
    });

    Route::controller(ManageOrderController::class)->group(function(){
        Route::get('/admin/order/details/{id}', 'AdminOrderDetails')->name('admin.order.details');  
        Route::get('/pening_to_confirm/{id}', 'PendingToConfirm')->name('pening_to_confirm');
        Route::get('/confirm_to_processing/{id}', 'ConfirmToProcessing')->name('confirm_to_processing'); 
        Route::get('/processing_to_deliverd/{id}', 'ProcessingToDiliverd')->name('processing_to_deliverd'); 

    });

});













// All Client or Restaurant Menu Route
Route::middleware(['client','status'])->group(function(){

    Route::controller(RestaurantController::class)->group(function(){
        Route::get('/all/menu','AllMenu')->name('all.menu');
        Route::get('/add/menu','AddMenu')->name('add.menu');
        Route::post('/store/menu','StoreMenu')->name('store.menu');
        Route::get('/edit/menu/{id}','EditMenu')->name('edit.menu');
        Route::post('/update/menu/{id}','UpdateMenu')->name('update.menu');
        Route::get('/delete/menu/{id}','DeleteMenu')->name('delete.menu');

    });

    
    Route::controller(RestaurantController::class)->group(function(){

        Route::get('/add/product','AddProduct')->name('add.product');
        Route::get('/all/product','AllProduct')->name('all.product');
        Route::post('/store/product','StoreProduct')->name('store.product');
        Route::get('/edit/product/{id}','EditProduct')->name('edit.product');
        Route::post('/update/product/{id}','UpdateProduct')->name('update.product');
        Route::get('/delete/product/{id}','DeleteProduct')->name('delete.product');
       
    });


    Route::controller(RestaurantController::class)->group(function(){

        Route::get('/add/gallery','AddGallery')->name('add.gallery');
        Route::get('/all/gallery','AllGallery')->name('all.gallery');
        Route::post('/store/gallery','StoreGallery')->name('store.gallery');
        Route::get('/edit/gallery/{id}','EditGallery')->name('edit.gallery');
        Route::post('/update/gallery/{id}','UpdateGallery')->name('update.gallery');
        Route::get('/delete/gallery/{id}','DeleteGallery')->name('delete.gallery');
    });

    Route::controller(CouponController::class)->group(function(){

        Route::get('/all/coupon','AllCoupon')->name('all.coupon');
        Route::get('/add/coupon','AddCoupon')->name('add.coupon');
        Route::post('/store/coupon','StoreCoupon')->name('store.coupon');
        Route::get('/edit/coupon/{id}','EditCoupon')->name('edit.coupon');
        Route::post('/update/coupon/{id}','UpdateCoupon')->name('update.coupon');
        Route::get('/delete/coupon/{id}','DeleteCoupon')->name('delete.coupon');
    });
    
});//end

// That will be for all user
Route::get('/changeStatus',[RestaurantController::class,'ChangeStatus']); //end


Route::controller(HomeController::class)->group(function(){

    Route::get('/restaurant/details/{id}','RestaurantDetails')->name('restaurant.details');
    Route::post('/add-wish-list/{id}', 'AddWishList'); 
   
});


Route::controller(CartController::class)->group(function(){

    Route::get('/add/to/cart/{id}', 'AddToCart')->name('add_to_cart'); 
    Route::post('/cart/update-quantity', 'updateCartQuanity')->name('cart.updateQuantity');
    Route::post('/cart/remove', 'CartRemove')->name('cart.remove');  
    Route::post('/apply-coupon', 'ApplyCoupon');
    Route::get('/remove-coupon', 'CouponRemove');
    Route::get('/checkout', 'ShopCheckout')->name('checkout');
});



Route::controller(OrderController::class)->group(function(){

    Route::post('/cash/order', 'CashOrder')->name('cash_order');
   
});