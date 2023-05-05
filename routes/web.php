<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\CompareController;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Controllers\Backend\BrandController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\User\WishlistController;
use App\Http\Controllers\Backend\BannerController;
use App\Http\Controllers\Backend\CouponController;
use App\Http\Controllers\Backend\SliderController;
use App\Http\Controllers\Frontend\IndexController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\SubCategoryController;
use App\Http\Controllers\Backend\VendorProductController;

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


Route::get('/', [IndexController::class, 'Index']);


// Frontend Product Details All Routes

Route::get('/product/details/{id}/{slug}', [IndexController::class, 'ProductDetails']);
Route::get('/vendor/details/{id}', [IndexController::class, 'VendorDetails'])->name('vendor.details');
Route::get('/vendor/all', [IndexController::class, 'VendorAll'])->name('vendor.all');
Route::get('/product/category/{id}/{slug}', [IndexController::class, 'catWithProducts']);
Route::get('/product/subcategory/{id}/{slug}', [IndexController::class, 'SubCatWiseProduct']);
Route::get('/product/view/modal/{id}', [IndexController::class, 'ProductViewAjax']);
Route::get('/product/view/modal/{id}', [IndexController::class, 'ProductViewAjax']);
/// Add to cart store data
Route::post('/cart/data/store/{id}', [CartController::class, 'AddToCart']);
// Get Data from mini Cart
Route::get('/product/mini/cart', [CartController::class, 'AddMiniCart']);
Route::get('/product/mini/cart', [CartController::class, 'AddMiniCart']);
Route::get('/minicart/product/remove/{rowId}', [CartController::class, 'RemoveMiniCart']);
Route::get('/minicart/product/remove/{rowId}', [CartController::class, 'RemoveMiniCart']);
/// Add to cart store data For Product Details Page
Route::post('/dcart/data/store/{id}', [CartController::class, 'AddToCartDetails']);

/// Add to Wishlist
Route::post('/add-to-wishlist/{product_id}', [WishlistController::class, 'AddToWishList']);
/// Add to Compare
Route::post('/add-to-compare/{product_id}', [CompareController::class, 'AddToCompare']);

/// User All Route
Route::middleware(['auth', 'role:user'])->group(function () {

    // Wishlist All Route
    Route::controller(WishlistController::class)->group(function () {
        Route::get('/wishlist', 'AllWishlist')->name('wishlist');
        Route::get('/get-wishlist-product', 'GetWishlistProduct');
        Route::get('/wishlist-remove/{id}', 'WishlistRemove');
    });

    // Compare All Route
    Route::controller(CompareController::class)->group(function () {

        Route::get('/compare', 'AllCompare')->name('compare');
        Route::get('/get-compare-product', 'GetCompareProduct');
        Route::get('/compare-remove/{id}', 'CompareRemove');
    });
    // Cart All Route
    Route::controller(CartController::class)->group(function () {
        Route::get('/mycart', 'MyCart')->name('mycart');
        Route::get('/get-cart-product', 'GetCartProduct');
        Route::get('/cart-remove/{rowId}', 'CartRemove');
        Route::get('/cart-decrement/{rowId}', 'CartDecrement');
        Route::get('/cart-increment/{rowId}', 'CartIncrement');
    });
}); // end group middleware



Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'UserDashboard'])->name('dashboard');
    Route::post('/user/profile/store', [UserController::class, 'UserProfileStore'])->name('user.profile.store');
    Route::post('/user/update/password', [UserController::class, 'UserUpdatePassword'])->name('user.update.password');

    Route::get('/user/logout', [UserController::class, 'UserLogout'])->name('user.logout');
});


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'role:user'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';


Route::get('/admin/login', [AdminController::class, 'AdminLogin'])->middleware(RedirectIfAuthenticated::class);

Route::get('/vendor/login', [VendorController::class, 'VendorLogin'])->name('vendor.login')->middleware(RedirectIfAuthenticated::class);
Route::get('/become/vendor', [VendorController::class, 'BecomeVendor'])->name('become.vendor');
Route::get('/become/vendor', [VendorController::class, 'BecomeVendor'])->name('become.vendor');
Route::post('/vendor/register', [VendorController::class, 'VendorRegister'])->name('vendor.register');


// Admin Dashboard
Route::middleware(['auth', 'role:admin'])->group(function () {

    // Profile
    Route::get('admin/dashboard', [AdminController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/admin/profile', [AdminController::class, 'AdminProfile'])->name('admin.profile');
    Route::post('/admin/profile/store', [AdminController::class, 'AdminProfileStore'])->name('admin.profile.store');
    Route::get('/admin/change/password', [AdminController::class, 'AdminChangePassword'])->name('admin.change.password');
    Route::post('/admin/update/password', [AdminController::class, 'AdminUpdatePassword'])->name('update.password');
    Route::get('admin/logout', [AdminController::class, 'AdminDestroy'])->name('admin.logout');
    // ===========================================================================================

    // Admin
    Route::get('/inactive/vendor', [AdminController::class, 'InactiveVendor'])->name('inactive.vendor');
    Route::get('/active/vendor', [AdminController::class, 'activeVendor'])->name('active.vendor');
    Route::get('/inactive/vendor/details/{id}', [AdminController::class, 'InactiveVendorDetails'])->name('inactive.vendor.details');
    Route::post('/active/vendor/approve', [AdminController::class, 'ActiveVendorApprove'])->name('active.vendor.approve');
    Route::get('/active/vendor/details/{id}', [AdminController::class, 'ActiveVendorDetails'])->name('active.vendor.details');
    Route::post('/inactive/vendor/approve', [AdminController::class, 'InActiveVendorApprove'])->name('inactive.vendor.approve');




    // ===========================================================================================


    // Brand
    Route::controller(BrandController::class)->group(function () {
        Route::get('all/brand', 'brands')->name('all.brand');
        Route::get('/add/brand', 'AddBrand')->name('add.brand');
        Route::post('/store/brand', 'StoreBrand')->name('store.brand');
        Route::get('/edit/brand/{id}', 'EditBrand')->name('edit.brand');
        Route::post('/update/brand/{id}', 'UpdateBrand')->name('update.brand');
        Route::delete('/brand/{id}', 'DeleteBrand')->name('delete.brand');
    });
    // Category
    Route::controller(CategoryController::class)->group(function () {
        Route::get('all/category', 'categories')->name('all.category');
        Route::get('/add/category', 'addCategory')->name('add.category');
        Route::post('/store/category', 'storeCategory')->name('store.category');
        Route::get('/edit/category/{id}', 'editCategory')->name('edit.category');
        Route::post('/update/category/{id}', 'updateCategory')->name('update.category');
        Route::delete('/category/{id}', 'deleteCategory')->name('delete.category');
    });
    // Sub Category
    Route::controller(SubCategoryController::class)->group(function () {
        Route::get('all/subcategory', 'subCategories')->name('all.subcategory');
        Route::get('/add/subcategory', 'addSubcategory')->name('add.subcategory');
        Route::post('/store/subcategory', 'storeSubcategory')->name('store.subcategory');
        Route::get('/edit/subcategory/{id}', 'editSubcategory')->name('edit.subcategory');
        Route::post('/update/subcategory/{id}', 'updateSubcategory')->name('update.subcategory');
        Route::delete('/subcategory/{id}', 'deleteSubcategory')->name('delete.subcategory');
    });
    // Products
    Route::controller(ProductController::class)->group(function () {
        Route::get('all/product', 'allProduct')->name('all.products');
        Route::get('add/product', 'addProduct')->name('add.product');
        Route::post('store/product', 'storeProduct')->name('store.product');
        Route::get('subcategory/ajax/{category_id}', 'getSubcategory');
        Route::get('/edit/product/{id}', 'editProduct')->name('edit.product');
        Route::post('/update/product', 'updateProduct')->name('update.product');
        Route::post('/update/product/thambnail', 'updateProductThambnail')->name('update.product.thambnail');
        Route::post('/update/product/multiimage', 'UpdateProductMultiimage')->name('update.product.multiimage');
        Route::get('/product/multiimg/delete/{id}', 'MulitImageDelelte')->name('product.multiimg.delete');
        Route::get('/product/inactive/{id}', 'ProductInactive')->name('product.inactive');
        Route::get('/product/active/{id}', 'ProductActive')->name('product.active');
        Route::get('/delete/product/{id}', 'productDelete')->name('delete.product');
    });
    // Slider All Route
    Route::controller(SliderController::class)->group(function () {
        Route::get('/all/slider', 'AllSlider')->name('all.slider');
        Route::get('/add/slider', 'AddSlider')->name('add.slider');
        Route::post('/store/slider', 'StoreSlider')->name('store.slider');
        Route::get('/edit/slider/{id}', 'EditSlider')->name('edit.slider');
        Route::post('/update/slider', 'UpdateSlider')->name('update.slider');
        Route::get('/delete/slider/{id}', 'DeleteSlider')->name('delete.slider');
    });

    // Banner All Route
    Route::controller(BannerController::class)->group(function () {
        Route::get('/all/banner', 'AllBanner')->name('all.banner');
        Route::get('/add/banner', 'AddBanner')->name('add.banner');
        Route::post('/store/banner', 'StoreBanner')->name('store.banner');
        Route::get('/edit/banner/{id}', 'EditBanner')->name('edit.banner');
        Route::post('/update/banner', 'UpdateBanner')->name('update.banner');
        Route::get('/delete/banner/{id}', 'DeleteBanner')->name('delete.banner');
    });
    // Coupon All Route
    Route::controller(CouponController::class)->group(function () {
        Route::get('/all/coupon', 'AllCoupon')->name('all.coupon');
        Route::get('/add/coupon', 'AddCoupon')->name('add.coupon');
        Route::post('/store/coupon', 'StoreCoupon')->name('store.coupon');
        // Route::get('/edit/banner/{id}', 'EditBanner')->name('edit.banner');
        // Route::post('/update/banner', 'UpdateBanner')->name('update.banner');
        // Route::get('/delete/banner/{id}', 'DeleteBanner')->name('delete.banner');
    });
});

// Vendor Dashboard
Route::middleware(['auth', 'role:vendor'])->group(function () {

    Route::get('vendor/dashboard', [VendorController::class, 'VendorDashboard'])->name('vendor.dashboard');
    Route::get('vendor/logout', [VendorController::class, 'VendorDestroy'])->name('vendor.logout');
    Route::get('/vendor/profile', [VendorController::class, 'VendorProfile'])->name('vendor.profile');
    Route::post('/vendor/profile/store', [VendorController::class, 'VendorProfileStore'])->name('vendor.profile.store');
    Route::get('/vendor/change/password', [VendorController::class, 'VendorChangePassword'])->name('vendor.change.password');
    Route::post('/vendor/update/password', [VendorController::class, 'VendorUpdatePassword'])->name('vendor.update.password');


    // Vendor Product
    Route::get('vendor/all/product', [VendorProductController::class, 'vendorAllProduct'])->name('vendor.all.product');
    Route::get('/vendor/add/product', [VendorProductController::class, 'vendorAddProduct'])->name('vendor.add.product');
    Route::get('/vendor/subcategory/ajax/{category_id}', [VendorProductController::class, 'VendorGetSubCategory']);
    Route::post('/vendor/store/product', [VendorProductController::class, 'VendorStoreProduct'])->name('vendor.store.product');
    Route::get('/vendor/edit/product/{id}', [VendorProductController::class, 'VendorEditProduct'])->name('vendor.edit.product');
    Route::post('/vendor/update/product', [VendorProductController::class, 'VendorUpdateProduct'])->name('vendor.update.product');
    Route::post('/vendor/update/product/thambnail', [VendorProductController::class, 'VendorUpdateProductThambnail'])->name('vendor.update.product.thambnail');
    Route::post('/vendor/update/product/multiimage', [VendorProductController::class, 'VendorUpdateProductmultiImage'])->name('vendor.update.product.multiimage');
    Route::post('/vendor/product/multiimg/delete/{id}', [VendorProductController::class, 'VendorMultiimgDelete'])->name('vendor.product.multiimg.delete');
    Route::get('/vendor/product/inactive/{id}', [VendorProductController::class, 'VendorProductInactive'])->name('vendor.product.inactive');
    Route::get('/vendor/product/active/{id}', [VendorProductController::class, 'VendorProductActive'])->name('vendor.product.active');
    Route::get('/vendor/delete/product/{id}', [VendorProductController::class, 'VendorProductDelete'])->name('vendor.delete.product');
});
